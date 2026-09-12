<?php
/**
 * Admin Article Controller
 */
require_once APP_PATH . '/helpers/CloudflareR2.php';

class ArticleController extends Controller {
    private $articleModel;
    
    public function __construct() {
        parent::__construct();
        $this->middleware(AuthMiddleware::class);
        $this->articleModel = new Article();
    }
    
    public function index() {
        $page = $this->input('page', 1);
        $pagination = $this->articleModel->paginate($page, 20, '1=1', [], 'created_at DESC');
        
        $data = [
            'articles' => $pagination['data'],
            'pagination' => $pagination,
            'currentPage' => 'articles',
        ];
        
        $this->view('admin/articles/index', $data);
    }
    
    public function create() {
        $this->view('admin/articles/create/index');
    }
    
    public function store() {
        if (!$this->isPost()) {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }
        
        try {
            $title = sanitize($this->input('title'));
            $content = sanitize_article_content($this->input('content')); // Allow safe blog HTML
            $authorName = sanitize($this->input('author_name'));
            $status = $this->input('status', 'draft');
            
            if (empty($title) || article_plain_text($content) === '' || empty($authorName)) {
                echo json_encode(['success' => false, 'message' => 'Judul, konten, dan nama penulis harus diisi']);
                return;
            }
            
            // Generate unique slug
            $slug = $this->articleModel->generateUniqueSlug($title);
            
            // Handle image upload to R2
            $featuredImage = null;
            if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
                if (R2_ENABLED) {
                    $r2 = new CloudflareR2();
                    
                    // Generate unique filename
                    $fileExtension = pathinfo($_FILES['featured_image']['name'], PATHINFO_EXTENSION);
                    $timestamp = time();
                    $randomString = bin2hex(random_bytes(8));
                    $uniqueFilename = "article-{$timestamp}-{$randomString}.{$fileExtension}";
                    
                    // Upload to R2 in articles folder
                    $r2Key = "articles/{$uniqueFilename}";
                    $uploadResult = $r2->uploadPublic(
                        $_FILES['featured_image']['tmp_name'],
                        $r2Key,
                        $_FILES['featured_image']['type']
                    );
                    
                    if ($uploadResult['success']) {
                        $featuredImage = $uploadResult['url'];
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Gagal mengupload gambar ke R2: ' . $uploadResult['message']]);
                        return;
                    }
                } else {
                    // Fallback to local upload
                    $upload = upload_file($_FILES['featured_image'], 'articles');
                    if ($upload['success']) {
                        $featuredImage = $upload['path'];
                    }
                }
            }
            
            // Generate excerpt from content if not provided
            $excerpt = article_plain_text($content);
            $excerpt = mb_substr($excerpt, 0, 200);
            
            $data = [
                'title' => $title,
                'slug' => $slug,
                'content' => $content,
                'excerpt' => $excerpt,
                'featured_image' => $featuredImage,
                'author_name' => $authorName,
                'status' => $status,
                'is_featured' => 0,
                'published_at' => $status === 'published' ? date('Y-m-d H:i:s') : null,
            ];
            
            $id = $this->articleModel->create($data);
            
            $message = $status === 'draft' ? 'Artikel berhasil disimpan sebagai draft' : 'Artikel berhasil dipublish';
            echo json_encode(['success' => true, 'message' => $message, 'id' => $id]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => sanitize_error($e, 'Gagal menyimpan artikel')]);
        }
    }
    
    public function edit($id) {
        $article = $this->articleModel->find($id);
        
        if (!$article) {
            flash('error', 'Article not found');
            $this->redirect('/admin/articles');
            return;
        }
        
        $data = [
            'article' => $article,
        ];
        
        $this->view('admin/articles/edit/index', $data);
    }
    
    public function update($id) {
        if (!$this->isPost()) {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }
        
        try {
            $article = $this->articleModel->find($id);
            
            if (!$article) {
                echo json_encode(['success' => false, 'message' => 'Artikel tidak ditemukan']);
                return;
            }
            
            $title = sanitize($this->input('title'));
            $content = sanitize_article_content($this->input('content'));
            $authorName = sanitize($this->input('author_name'));
            $status = $this->input('status', 'draft');
            
            if (empty($title) || article_plain_text($content) === '' || empty($authorName)) {
                echo json_encode(['success' => false, 'message' => 'Judul, konten, dan nama penulis harus diisi']);
                return;
            }
            
            // Generate slug if title changed
            $slug = $article['slug'];
            if ($title !== $article['title']) {
                $slug = $this->articleModel->generateUniqueSlug($title, $id);
            }
            
            // Handle image upload to R2
            $featuredImage = $article['featured_image'];
            if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
                if (R2_ENABLED) {
                    $r2 = new CloudflareR2();
                    
                    // Delete old image from R2 if exists
                    if ($featuredImage && strpos($featuredImage, R2_PUBLIC_URL) === 0) {
                        $oldKey = str_replace(R2_PUBLIC_URL . '/', '', $featuredImage);
                        $r2->deletePublic($oldKey);
                    }
                    
                    // Generate unique filename
                    $fileExtension = pathinfo($_FILES['featured_image']['name'], PATHINFO_EXTENSION);
                    $timestamp = time();
                    $randomString = bin2hex(random_bytes(8));
                    $uniqueFilename = "article-{$timestamp}-{$randomString}.{$fileExtension}";
                    
                    // Upload new image to R2
                    $r2Key = "articles/{$uniqueFilename}";
                    $uploadResult = $r2->uploadPublic(
                        $_FILES['featured_image']['tmp_name'],
                        $r2Key,
                        $_FILES['featured_image']['type']
                    );
                    
                    if ($uploadResult['success']) {
                        $featuredImage = $uploadResult['url'];
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Gagal mengupload gambar ke R2: ' . $uploadResult['message']]);
                        return;
                    }
                } else {
                    // Fallback to local upload
                    $upload = upload_file($_FILES['featured_image'], 'articles');
                    if ($upload['success']) {
                        // Delete old image
                        if ($featuredImage) {
                            delete_file($featuredImage);
                        }
                        $featuredImage = $upload['path'];
                    }
                }
            }
            
            // Generate excerpt from content
            $excerpt = article_plain_text($content);
            $excerpt = mb_substr($excerpt, 0, 200);
            
            $data = [
                'title' => $title,
                'slug' => $slug,
                'content' => $content,
                'excerpt' => $excerpt,
                'featured_image' => $featuredImage,
                'author_name' => $authorName,
                'status' => $status,
            ];
            
            // Set published_at if status changed to published or if it's being published now
            if ($status === 'published') {
                if ($article['status'] !== 'published' || empty($article['published_at'])) {
                    $data['published_at'] = date('Y-m-d H:i:s');
                }
            } elseif ($status === 'draft') {
                // Clear published_at if changing to draft
                $data['published_at'] = null;
            }
            
            $this->articleModel->update($id, $data);
            
            $message = $status === 'draft' ? 'Artikel berhasil disimpan sebagai draft' : 'Artikel berhasil dipublish';
            echo json_encode(['success' => true, 'message' => $message]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => sanitize_error($e, 'Gagal mengupdate artikel')]);
        }
    }
    
    public function delete($id) {
        if (!$this->isPost()) {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }
        
        try {
            $article = $this->articleModel->find($id);
            
            if (!$article) {
                echo json_encode(['success' => false, 'message' => 'Artikel tidak ditemukan']);
                return;
            }
            
            // Delete featured image from R2 or local storage
            if ($article['featured_image']) {
                if (R2_ENABLED && strpos($article['featured_image'], R2_PUBLIC_URL) === 0) {
                    $r2 = new CloudflareR2();
                    $key = str_replace(R2_PUBLIC_URL . '/', '', $article['featured_image']);
                    $r2->deletePublic($key);
                } else {
                    delete_file($article['featured_image']);
                }
            }
            
            $this->articleModel->delete($id);
            
            echo json_encode(['success' => true, 'message' => 'Artikel berhasil dihapus']);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => sanitize_error($e, 'Gagal menghapus artikel')]);
        }
    }
}
