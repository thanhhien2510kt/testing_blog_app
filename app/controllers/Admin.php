<?php
class Admin extends Controller {
    public function __construct() {
        if(!isLoggedIn()) {
            redirect('users/login');
        }
        $this->postModel = $this->model('Post');
        $this->categoryModel = $this->model('Category');
        $this->tagModel = $this->model('Tag');
        $this->commentModel = $this->model('Comment');
        $this->userModel = $this->model('User');
    }

    public function index() {
        $postCount = $this->postModel->getPostCount(false);
        
        $data = [
            'title' => 'Admin Dashboard',
            'postCount' => $postCount
        ];

        $this->view('admin/index', $data);
    }
    
    // List Posts
    public function posts() {
        $filters = [
            'search' => isset($_GET['search']) ? trim($_GET['search']) : '',
            'category_id' => isset($_GET['category']) ? trim($_GET['category']) : '',
            'status' => isset($_GET['status']) ? trim($_GET['status']) : ''
        ];

        // If export requested
        if (isset($_GET['action']) && $_GET['action'] == 'export') {
            $this->export_posts($filters);
            return;
        }

        $posts = $this->postModel->getFilteredPosts($filters, 100, 1); 
        // Note: Using getCategoryHierarchy for the filter dropdown to be consistent with Add Post
        $categories = $this->getCategoryHierarchy(); 
        
        $data = [
            'posts' => $posts,
            'categories' => $categories,
            'filters' => $filters
        ];
        $this->view('admin/posts/index', $data);
    }
    
    // Export Posts
    private function export_posts($filters) {
        $posts = $this->postModel->getFilteredPosts($filters, 1000, 1); 
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=posts_export_' . date('Y-m-d') . '.csv');
        
        $output = fopen('php://output', 'w');
        
        // Add BOM for Excel UTF-8 compatibility
        fputs($output, "\xEF\xBB\xBF");
        
        // Add 'Content' to header
        fputcsv($output, ['ID', 'Title', 'Content', 'Category', 'Status', 'Author', 'Created At']);
        
        foreach($posts as $post) {
            fputcsv($output, [
                $post->postId,
                $post->title,
                $post->content, // Export full content with HTML tags preserved
                isset($post->categoryName) ? $post->categoryName : 'None',
                ucfirst($post->status),
                $post->userName,
                $post->postCreated
            ]);
        }
        
        fclose($output);
        exit;
    }

    // Import Posts
    public function import_posts() {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['import_file'])) {
            $file = $_FILES['import_file']['tmp_name'];
            
            if($_FILES['import_file']['size'] > 0) {
                $file_handle = fopen($file, 'r');
                
                // Check Header
                $header = fgetcsv($file_handle);
                // Basic check if it matches expectation (roughly)
                // Expected: ID, Title, Content, Category, Status, Author, Created At
                
                if($header[1] != 'Title' || $header[2] != 'Content') {
                    flash('post_message', 'Invalid CSV Template', 'alert-danger');
                    redirect('admin/posts');
                }

                $successCount = 0;
                $failCount = 0;
                
                while(($row = fgetcsv($file_handle)) !== FALSE) {
                    $id = trim($row[0]);
                    $title = trim($row[1]);
                    $content = $row[2]; 
                    $categoryName = trim($row[3]);
                    $status = strtolower(trim($row[4]));
                    
                    if(empty($title) || empty($content)) {
                        $failCount++;
                        continue;
                    }

                    // Find Category ID
                    $categoryId = '';
                    if(!empty($categoryName)){
                        $cat = $this->categoryModel->getCategoryByName($categoryName);
                        if($cat){
                            $categoryId = $cat->id;
                        }
                    }

                    // Generate Slug
                    $slug = $this->create_slug($title);
                    
                    // Normalize Status
                    if($status != 'published' && $status != 'draft'){
                        $status = 'draft';
                    }

                    $data = [
                        'title' => $title,
                        'slug' => $slug,
                        'content' => $content,
                        'thumbnail' => '', 
                        'status' => $status,
                        'category_id' => $categoryId
                    ];

                    $result = false;
                    if(!empty($id)) {
                        // UPDATE existing post
                        // Validate if post exists first
                        $existingPost = $this->postModel->getPostById($id);
                        if(!$existingPost){
                            // Post ID not found in DB, skip
                            $failCount++;
                            continue;
                        }
                        
                        $data['id'] = $id;
                        $result = $this->postModel->updatePost($data);
                    } else {
                        // CREATE new post
                        // Check for duplicate Title
                        if($this->postModel->getPostByTitle($title)){
                             // Duplicate title found, skip
                             $failCount++;
                             continue;
                        }

                        $data['user_id'] = $_SESSION['user_id'];
                        $result = $this->postModel->addPost($data);
                    }
                    
                    if($result) {
                        $successCount++;
                    } else {
                        $failCount++;
                    }
                }
                
                fclose($file_handle);
                
                $msg = "Imported $successCount posts successfully.";
                if($failCount > 0) {
                     $msg .= " Failed to import $failCount posts (check duplicates or invalid data).";
                     flash('post_message', $msg, 'alert-warning');
                } else {
                     flash('post_message', $msg);
                }
                
                redirect('admin/posts');
            } else {
                flash('post_message', 'Empty file uploaded', 'alert-danger');
                redirect('admin/posts');
            }
        } else {
             redirect('admin/posts');
        }
    }

    private function create_slug($string) {
        $search = [
            'à', 'á', 'ạ', 'ả', 'ã', 'â', 'ầ', 'ấ', 'ậ', 'ẩ', 'ẫ', 'ă', 'ằ', 'ắ', 'ặ', 'ẳ', 'ẵ',
            'è', 'é', 'ẹ', 'ẻ', 'ẽ', 'ê', 'ề', 'ế', 'ệ', 'ể', 'ễ',
            'ì', 'í', 'ị', 'ỉ', 'ĩ',
            'ò', 'ó', 'ọ', 'ỏ', 'õ', 'ô', 'ồ', 'ố', 'ộ', 'ổ', 'ỗ', 'ơ', 'ờ', 'ớ', 'ợ', 'ở', 'ỡ',
            'ù', 'ú', 'ụ', 'ủ', 'ũ', 'ư', 'ừ', 'ứ', 'ự', 'ử', 'ữ',
            'ỳ', 'ý', 'ỵ', 'ỷ', 'ỹ',
            'đ',
            'À', 'Á', 'Ạ', 'Ả', 'Ã', 'Â', 'Ầ', 'Ấ', 'Ậ', 'Ẩ', 'Ẫ', 'Ă', 'Ằ', 'Ắ', 'Ặ', 'Ẳ', 'Ẵ',
            'È', 'É', 'Ẹ', 'Ẻ', 'Ẽ', 'Ê', 'Ề', 'Ế', 'Ệ', 'Ể', 'Ễ',
            'Ì', 'Í', 'Ị', 'Ỉ', 'Ĩ',
            'Ò', 'Ó', 'Ọ', 'Ỏ', 'Õ', 'Ô', 'Ồ', 'Ố', 'Ộ', 'Ổ', 'Ỗ', 'Ơ', 'Ờ', 'Ớ', 'Ợ', 'Ở', 'Ỡ',
            'Ù', 'Ú', 'Ụ', 'Ù', 'Ũ', 'Ư', 'Ừ', 'Ứ', 'Ự', 'Ử', 'Ữ',
            'Ỳ', 'Ý', 'Ỵ', 'Ỷ', 'Ỹ',
            'Đ',
            ' '
        ];
        $replace = [
            'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
            'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e',
            'i', 'i', 'i', 'i', 'i',
            'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o',
            'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u',
            'y', 'y', 'y', 'y', 'y',
            'd',
            'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
            'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e',
            'i', 'i', 'i', 'i', 'i',
            'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o',
            'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u',
            'y', 'y', 'y', 'y', 'y',
            'd',
            '-'
        ];
        
        $string = str_replace($search, $replace, $string);
        $string = strtolower($string);
        $string = preg_replace('/[^a-z0-9\-]/', '', $string);
        $string = preg_replace('/-+/', '-', $string);
        $string = trim($string, '-');
        
        return $string;
    }

    // Add Post
    public function add_post() {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Sanitize POST array
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data = [
                'title' => trim($_POST['title']),
                'content' => trim(filter_input(INPUT_POST, 'content', FILTER_UNSAFE_RAW)), // Allow HTML
                'category_id' => isset($_POST['category_id']) ? trim($_POST['category_id']) : '',
                'status' => trim($_POST['status']),
                'thumbnail' => '', // Handle file upload later
                'user_id' => $_SESSION['user_id'],
                'slug' => strtolower(str_replace(' ', '-', trim($_POST['title']))), // Simple slug
                'title_err' => '',
                'content_err' => ''
            ];

             // Handle File Upload (Simple version)
             if(!empty($_FILES['thumbnail']['name'])){
                $target_dir = "../public/uploads/";
                $target_file = $target_dir . basename($_FILES["thumbnail"]["name"]);
                if(move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $target_file)){
                    $data['thumbnail'] = basename($_FILES["thumbnail"]["name"]);
                }
             }

            // Validate
            if(empty($data['title'])){ $data['title_err'] = 'Please enter title'; }
            if(empty($data['content'])){ $data['content_err'] = 'Please enter content'; }

            if(empty($data['title_err']) && empty($data['content_err'])){
                if($this->postModel->addPost($data)){
                    flash('post_message', 'Post Added');
                    redirect('admin/posts');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('admin/posts/add', $data);
            }

        } else {
            $categories = $this->getCategoryHierarchy();
            $data = [
                'title' => '',
                'content' => '',
                'categories' => $categories
            ];
            $this->view('admin/posts/add', $data);
        }
    }

    // Edit Post
    public function edit_post($id) {
         if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data = [
                'id' => $id,
                'title' => trim($_POST['title']),
                'content' => trim(filter_input(INPUT_POST, 'content', FILTER_UNSAFE_RAW)), // Allow HTML
                'category_id' => isset($_POST['category_id']) ? trim($_POST['category_id']) : '',
                'status' => trim($_POST['status']),
                'slug' => strtolower(str_replace(' ', '-', trim($_POST['title']))),
                'thumbnail' => trim($_POST['existing_thumbnail']), // Default to existing
                'title_err' => '',
                'content_err' => ''
            ];

             // Handle File Upload
             if(!empty($_FILES['thumbnail']['name'])){
                $target_dir = "../public/uploads/";
                $target_file = $target_dir . basename($_FILES["thumbnail"]["name"]);
                if(move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $target_file)){
                    $data['thumbnail'] = basename($_FILES["thumbnail"]["name"]);
                }
             }

            if(empty($data['title'])){ $data['title_err'] = 'Please enter title'; }
            if(empty($data['content'])){ $data['content_err'] = 'Please enter content'; }

            if(empty($data['title_err']) && empty($data['content_err'])){
                if($this->postModel->updatePost($data)){
                    flash('post_message', 'Post Updated');
                    redirect('admin/posts');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('admin/posts/edit', $data);
            }

        } else {
            $post = $this->postModel->getPostById($id);
            $categories = $this->getCategoryHierarchy();

            // Check if post exists
            if($post->author_id != $_SESSION['user_id'] && $_SESSION['user_role'] != 'admin'){
                redirect('posts');
            }

            $data = [
                'id' => $id,
                'title' => $post->title,
                'content' => $post->content,
                'status' => $post->status,
                'category_id' => $post->categoryId, // Pass current category
                'thumbnail' => $post->thumbnail,
                'categories' => $categories
            ];
            $this->view('admin/posts/edit', $data);
        }
    }

    // Delete Post
    public function delete_post($id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            if($this->postModel->deletePost($id)){
                flash('post_message', 'Post Removed');
                redirect('admin/posts');
            } else {
                die('Something went wrong');
            }
        } else {
            redirect('admin/posts');
        }
    }

    // Category Methods
    public function categories() {
        $categories = $this->categoryModel->getCategoriesWithCount();
        $tags = $this->tagModel->getTagsWithCount();
        
        $data = [
            'categories' => $categories,
            'tags' => $tags
        ];
        $this->view('admin/categories/index', $data);
    }

    public function export_categories() {
        $categories = $this->categoryModel->getCategories();
        
        // Build ID -> Name map
        $catMap = [];
        foreach($categories as $cat){
            $catMap[$cat->id] = $cat->name;
        }

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=categories_export_' . date('Y-m-d') . '.csv');
        
        $output = fopen('php://output', 'w');
        fputs($output, "\xEF\xBB\xBF"); // BOM
        
        fputcsv($output, ['ID', 'Name', 'Slug', 'Parent Name']);
        
        foreach($categories as $cat) {
            $parentName = '';
            if(!empty($cat->parent_id) && isset($catMap[$cat->parent_id])){
                $parentName = $catMap[$cat->parent_id];
            }

            fputcsv($output, [
                $cat->id,
                $cat->name,
                $cat->slug,
                $parentName
            ]);
        }
        
        fclose($output);
        exit;
    }

    public function import_categories() {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['import_file'])) {
             $file = $_FILES['import_file']['tmp_name'];
            
            if($_FILES['import_file']['size'] > 0) {
                $file_handle = fopen($file, 'r');
                fgetcsv($file_handle); // Skip Header

                $count = 0;
                $failCount = 0;

                while(($row = fgetcsv($file_handle)) !== FALSE) {
                    $name = trim($row[1]);
                    $slug = trim($row[2]);
                    $parentName = trim($row[3]); 

                    if(empty($name)) continue;

                    // Check duplicate
                    if($this->categoryModel->getCategoryByName($name)) {
                        $failCount++;
                        continue;
                    }

                    if(empty($slug)) {
                        $slug = $this->create_slug($name);
                    }

                    // Check duplicate slug
                    if($this->categoryModel->getCategoryBySlug($slug)) {
                         $failCount++;
                         continue;
                    }

                    // Resolve Parent ID
                    $parentId = null;
                    if(!empty($parentName)){
                        $parentCat = $this->categoryModel->getCategoryByName($parentName);
                        if($parentCat){
                            $parentId = $parentCat->id;
                        }
                    }

                    $data = [
                        'name' => $name,
                        'slug' => $slug,
                        'parent_id' => $parentId
                    ];

                    if($this->categoryModel->addCategory($data)) {
                        $count++;
                    } else {
                        $failCount++;
                    }
                }
                fclose($file_handle);
                $msg = "Imported $count categories.";
                if($failCount > 0) $msg .= " Skipped $failCount duplicates.";
                flash('category_message', $msg);
            }
        }
        redirect('admin/categories');
    }
    
    // Add Tag
    public function add_tag() {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data = [
                'name' => trim($_POST['name']),
                'slug' => strtolower(str_replace(' ', '-', trim($_POST['name'])))
            ];

            if(!empty($data['name'])){
                if($this->tagModel->addTag($data)){
                    flash('category_message', 'Tag Added');
                    redirect('admin/categories');
                } else {
                    die('Something went wrong');
                }
            } else {
                flash('category_message', 'Tag Name Empty', 'alert alert-danger');
                redirect('admin/categories');
            }
        } else {
            redirect('admin/categories');
        }
    }

    // Delete Tag
    public function delete_tag($id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            if($this->tagModel->deleteTag($id)){
                flash('category_message', 'Tag Removed');
                redirect('admin/categories');
            } else {
                die('Something went wrong');
            }
        } else {
            redirect('admin/categories');
        }
    }

    // Add Category
    public function add_category() {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data = [
                'name' => trim($_POST['name']),
                'slug' => strtolower(str_replace(' ', '-', trim($_POST['name']))),
                'parent_id' => !empty($_POST['parent_id']) ? trim($_POST['parent_id']) : null,
                'name_err' => ''
            ];

            if(empty($data['name'])){ $data['name_err'] = 'Please enter name'; }

            if(empty($data['name_err'])){
                if($this->categoryModel->addCategory($data)){
                    flash('category_message', 'Category Added');
                    redirect('admin/categories');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('admin/categories/add', $data);
            }

        } else {
            $categories = $this->categoryModel->getCategories();
            $data = [
                'name' => '',
                'parent_id' => '',
                'categories' => $categories,
                'name_err' => ''
            ];
            $this->view('admin/categories/add', $data);
        }
    }

    // Edit Category
    public function edit_category($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'slug' => strtolower(str_replace(' ', '-', trim($_POST['name']))),
                'parent_id' => !empty($_POST['parent_id']) ? trim($_POST['parent_id']) : null,
                'name_err' => ''
            ];

            if(empty($data['name'])){ $data['name_err'] = 'Please enter name'; }

            if(empty($data['name_err'])){
                if($this->categoryModel->updateCategory($data)){
                    flash('category_message', 'Category Updated');
                    redirect('admin/categories');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('admin/categories/edit', $data);
            }

        } else {
            $category = $this->categoryModel->getCategoryById($id);
            $categories = $this->categoryModel->getCategories();

            $data = [
                'id' => $id,
                'name' => $category->name,
                'parent_id' => $category->parent_id,
                'categories' => $categories,
                'name_err' => ''
            ];
            $this->view('admin/categories/edit', $data);
        }
    }

    // Delete Category
    public function delete_category($id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            if($this->categoryModel->deleteCategory($id)){
                flash('category_message', 'Category Removed');
                redirect('admin/categories');
            } else {
                die('Something went wrong');
            }
        } else {
            redirect('admin/categories');
        }
    }

    // List Comments
    public function comments() {
        $comments = $this->commentModel->getComments();
        $data = [
            'comments' => $comments
        ];
        $this->view('admin/comments/index', $data);
    }

    // Approve Comment
    public function approve_comment($id) {
         if($this->commentModel->updateStatus($id, 'approved')){
            flash('comment_message', 'Comment Approved');
            redirect('admin/comments');
        } else {
            die('Something went wrong');
        }
    }

    // Delete Comment
    public function delete_comment($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            if($this->commentModel->deleteComment($id)){
                flash('comment_message', 'Comment Removed');
                redirect('admin/comments');
            } else {
                die('Something went wrong');
            }
        } else {
            redirect('admin/comments');
        }
    }

    // List Users
    public function users() {
        $users = $this->userModel->getUsers();
        $data = [
            'users' => $users
        ];
        $this->view('admin/users/index', $data);
    }

    // Add User
    public function add_user() {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'role' => trim($_POST['role']),
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // Validate Email
            if(empty($data['email'])){
                $data['email_err'] = 'Please enter email';
            } else {
                // Check email
                if($this->userModel->findUserByEmail($data['email'])){
                     $data['email_err'] = 'Email is already taken';
                }
            }

            // Validate Name
            if(empty($data['name'])){
                $data['name_err'] = 'Please enter name';
            }

            // Validate Password
            if(empty($data['password'])){
                $data['password_err'] = 'Please enter password';
            } elseif(strlen($data['password']) < 6){
                $data['password_err'] = 'Password must be at least 6 characters';
            }

            // Validate Confirm Password
            if(empty($data['confirm_password'])){
                $data['confirm_password_err'] = 'Please confirm password';
            } else {
                if($data['password'] != $data['confirm_password']){
                    $data['confirm_password_err'] = 'Passwords do not match';
                }
            }

            if(empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])){
                // Hash Password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                // Register User
                if($this->userModel->addUser($data)){
                    flash('user_message', 'User Added');
                    redirect('admin/users');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('admin/users/add', $data);
            }

        } else {
            $data = [
                'name' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'role' => 'user',
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];
            $this->view('admin/users/add', $data);
        }
    }

    // Edit User
    public function edit_user($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'role' => trim($_POST['role']),
                'name_err' => '',
                'email_err' => '',
                'password_err' => ''
            ];

            // Validate Name
            if(empty($data['name'])){
                $data['name_err'] = 'Please enter name';
            }
            
            // Validate Email
             if(empty($data['email'])){
                $data['email_err'] = 'Please enter email';
            }

            // Validate Password (optional)
            if(!empty($data['password'])){
                 if(strlen($data['password']) < 6){
                    $data['password_err'] = 'Password must be at least 6 characters';
                }
            }

            if(empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err'])){
                 // Hash Password if provided
                if(!empty($data['password'])){
                    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                }

                if($this->userModel->updateUser($data)){
                    flash('user_message', 'User Updated');
                    redirect('admin/users');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('admin/users/edit', $data);
            }

        } else {
            $user = $this->userModel->getUserById($id);
            
             $data = [
                'id' => $id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'password' => '',
                'name_err' => '',
                'email_err' => '',
                'password_err' => ''
            ];
            $this->view('admin/users/edit', $data);
        }
    }

    // Delete User
    public function delete_user($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            if($this->userModel->deleteUser($id)){
                flash('user_message', 'User Removed'); // Need to handle flash in view if not present
                redirect('admin/users');
            } else {
                die('Something went wrong');
            }
        } else {
            redirect('admin/users');
        }
    }
    // Helper to get hierarchical categories
    private function getCategoryHierarchy() {
        $categories = $this->categoryModel->getCategories();
        
        $tree = [];
        $catsById = [];
        // Clone objects to avoid modifying original references if used elsewhere (though in PHP objects are passed by ref)
        // actually we want to modify them for the view.
        foreach($categories as $cat){
            $cat->children = [];
            $catsById[$cat->id] = $cat;
        }
        
        // Build Tree
        foreach($catsById as $id => $cat){
            if($cat->parent_id && isset($catsById[$cat->parent_id])){
                $catsById[$cat->parent_id]->children[] = $cat;
            } else {
                $tree[] = $cat;
            }
        }
        
        $sorted = [];
        $this->flattenTree($tree, $sorted);
        return $sorted;
    }

    private function flattenTree($nodes, &$list, $depth = 0) {
        foreach ($nodes as $node) {
            $prefix = str_repeat('&nbsp;&nbsp;', $depth * 2); 
            // Using &nbsp; for HTML display in select option might check escape. 
            // Select options treat &nbsp; as literal if escaped. 
            // Better use standard spaces or dashes. User requested indentation.
            // But usually <option> trims leading whitespace. 
            // &nbsp; works if raw, or special char like - 
            // Let's use "-- " as planned.
            $prefix = str_repeat('-- ', $depth);
            
            $cleanName = trim(str_replace('Testing', '', $node->name));
            
            // Create a display copy to not mess up original name if saved back (not relevant here as it's for display)
            // Actually modifying $node->name is safest for the view which just echoes it.
            $node->name = $prefix . $cleanName;
            
            $list[] = $node;
            
            if (!empty($node->children)) {
                $this->flattenTree($node->children, $list, $depth + 1);
            }
        }
    }
}
