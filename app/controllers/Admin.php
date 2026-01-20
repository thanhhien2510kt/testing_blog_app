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
        $posts = $this->postModel->getPosts(100, 1, false); // Get many posts for admin table, include drafts
        $categories = $this->categoryModel->getCategories(); // Fetch categories for filter
        $data = [
            'posts' => $posts,
            'categories' => $categories
        ];
        $this->view('admin/posts/index', $data);
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
            $categories = $this->postModel->getCategories();
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
            $categories = $this->postModel->getCategories();

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

    // List Categories & Tags (Combined View)
    public function categories() {
        $categories = $this->categoryModel->getCategoriesWithCount();
        $tags = $this->tagModel->getTagsWithCount();
        
        $data = [
            'categories' => $categories,
            'tags' => $tags
        ];
        $this->view('admin/categories/index', $data);
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
}
