<?php
/**
 * Admin Article Controller
 */
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
            $content = $this->input('content'); // Allow HTML
            $authorName = sanitize($this->input('author_name'));
            $status = $this->input('status', 'draft');
            
            if (empty($title) || empty($content) || empty($authorName)) {
                echo json_encode(['success' => false, 'message' => 'Judul, konten, dan nama penulis harus diisi']);
                return;
            }
            
            // Generate unique slug
            $slug = $this->articleModel->generateUniqueSlug($title);
            
            // Handle image upload
            $featuredImage = null;
            if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
                $upload = upload_file($_FILES['featured_image'], 'articles');
                if ($upload['success']) {
                    $featuredImage = $upload['path'];
                }
            }
            
            // Generate excerpt from content if not provided
            $excerpt = strip_tags($content);
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
            $content = $this->input('content');
            $authorName = sanitize($this->input('author_name'));
            $status = $this->input('status', 'draft');
            
            if (empty($title) || empty($content) || empty($authorName)) {
                echo json_encode(['success' => false, 'message' => 'Judul, konten, dan nama penulis harus diisi']);
                return;
            }
            
            // Generate slug if title changed
            $slug = $article['slug'];
            if ($title !== $article['title']) {
                $slug = $this->articleModel->generateUniqueSlug($title, $id);
            }
            
            // Handle image upload
            $featuredImage = $article['featured_image'];
            if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
                $upload = upload_file($_FILES['featured_image'], 'articles');
                if ($upload['success']) {
                    // Delete old image
                    if ($featuredImage) {
                        delete_file($featuredImage);
                    }
                    $featuredImage = $upload['path'];
                }
            }
            
            // Generate excerpt from content
            $excerpt = strip_tags($content);
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
            
            // Set published_at if status changed to published
            if ($status === 'published' && $article['status'] !== 'published') {
                $data['published_at'] = date('Y-m-d H:i:s');
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
            
            // Delete featured image
            if ($article['featured_image']) {
                delete_file($article['featured_image']);
            }
            
            $this->articleModel->delete($id);
            
            echo json_encode(['success' => true, 'message' => 'Artikel berhasil dihapus']);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => sanitize_error($e, 'Gagal menghapus artikel')]);
        }
    }
}
