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
        ];
        
        $this->view('admin/articles/index', $data);
    }
    
    public function create() {
        $this->view('admin/articles/create');
    }
    
    public function store() {
        if (!$this->isPost()) {
            $this->redirect('/admin/articles');
            return;
        }
        
        csrf_verify();
        
        $title = sanitize($this->input('title'));
        $content = $this->input('content'); // Allow HTML
        $excerpt = sanitize($this->input('excerpt'));
        $authorName = sanitize($this->input('author_name'));
        $status = $this->input('status', 'draft');
        $isFeatured = $this->input('is_featured', 0);
        
        if (empty($title) || empty($content) || empty($authorName)) {
            flash('error', 'Title, content, and author name are required');
            set_old($_POST);
            $this->redirect('/admin/articles/create');
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
        
        $data = [
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'excerpt' => $excerpt,
            'featured_image' => $featuredImage,
            'author_name' => $authorName,
            'status' => $status,
            'is_featured' => $isFeatured,
            'published_at' => $status === 'published' ? date('Y-m-d H:i:s') : null,
        ];
        
        $id = $this->articleModel->create($data);
        
        flash('success', 'Article created successfully');
        $this->redirect('/admin/articles/' . $id . '/edit');
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
        
        $this->view('admin/articles/edit', $data);
    }
    
    public function update($id) {
        if (!$this->isPost()) {
            $this->redirect('/admin/articles/' . $id . '/edit');
            return;
        }
        
        csrf_verify();
        
        $article = $this->articleModel->find($id);
        
        if (!$article) {
            flash('error', 'Article not found');
            $this->redirect('/admin/articles');
            return;
        }
        
        $title = sanitize($this->input('title'));
        $content = $this->input('content');
        $excerpt = sanitize($this->input('excerpt'));
        $authorName = sanitize($this->input('author_name'));
        $status = $this->input('status', 'draft');
        $isFeatured = $this->input('is_featured', 0);
        
        if (empty($title) || empty($content) || empty($authorName)) {
            flash('error', 'Title, content, and author name are required');
            set_old($_POST);
            $this->redirect('/admin/articles/' . $id . '/edit');
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
        
        $data = [
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'excerpt' => $excerpt,
            'featured_image' => $featuredImage,
            'author_name' => $authorName,
            'status' => $status,
            'is_featured' => $isFeatured,
        ];
        
        // Set published_at if status changed to published
        if ($status === 'published' && $article['status'] !== 'published') {
            $data['published_at'] = date('Y-m-d H:i:s');
        }
        
        $this->articleModel->update($id, $data);
        
        flash('success', 'Article updated successfully');
        $this->redirect('/admin/articles/' . $id . '/edit');
    }
    
    public function delete($id) {
        csrf_verify();
        
        $article = $this->articleModel->find($id);
        
        if (!$article) {
            flash('error', 'Article not found');
            $this->redirect('/admin/articles');
            return;
        }
        
        // Delete featured image
        if ($article['featured_image']) {
            delete_file($article['featured_image']);
        }
        
        $this->articleModel->delete($id);
        
        flash('success', 'Article deleted successfully');
        $this->redirect('/admin/articles');
    }
}
