<?php
class Posts extends Controller {
    public function __construct() {
        $this->postModel = $this->model('Post');
        $this->userModel = $this->model('User');
    }

    public function index() {
        redirect('pages/index');
    }

    public function add() {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Sanitize POST array
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'title' => trim($_POST['title']),
                'content' => trim($_POST['content']),
                'user_id' => $_SESSION['user_id'],
                'category_id' => trim($_POST['category_id']),
                'thumbnail' => '', 
                'status' => 'published',
                'slug' => '',
                'title_err' => '',
                'content_err' => ''
            ];

            // Validate Title
            if(empty($data['title'])){
                $data['title_err'] = 'Please enter title';
            }

            // Validate Body
            if(empty($data['content'])){
                $data['content_err'] = 'Please enter content text';
            }

            // Make sure no errors
            if(empty($data['title_err']) && empty($data['content_err'])){
                // Validated
                $data['slug'] = $this->createSlug($data['title']);
                
                if($this->postModel->addPost($data)){
                    flash('post_message', 'Post Added');
                    redirect('admin/index');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $categories = $this->postModel->getCategories();
                $data['categories'] = $categories;
                $this->view('posts/add', $data);
            }

        } else {
            $categories = $this->postModel->getCategories();
            $data = [
                'title' => '',
                'content' => '',
                'category_id' => '',
                'categories' => $categories
            ];
  
            $this->view('posts/add', $data);
        }
    }

    public function createSlug($string){
       $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
       return strtolower($slug);
    }

    public function show($id) {
        $post = $this->postModel->getPostById($id);
        $user = $this->userModel->getUserById($post->author_id);

        $data = [
            'post' => $post,
            'user' => $user
        ];

        $this->view('posts/show', $data);
    }
}
