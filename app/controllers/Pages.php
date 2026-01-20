<?php
class Pages extends Controller {
    public function __construct() {
        $this->postModel = $this->model('Post');
    }

    public function index() {
        $limit = 5;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $posts = $this->postModel->getPosts($limit, $page);
        $categories = $this->postModel->getCategories();
        $totalPosts = $this->postModel->getPostCount();
        $totalPages = ceil($totalPosts / $limit);

        $data = [
            'title' => 'QA Master Blog',
            'description' => 'Simple social network built on the TraversyMVC PHP framework',
            'posts' => $posts,
            'categories' => $categories,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ];

        $this->view('pages/index', $data);
    }

    public function about() {
        $data = [
            'title' => 'About Us',
            'description' => 'App to share posts with other users'
        ];

        $this->view('pages/about', $data);
    }

    public function search() {
        $categories = $this->postModel->getCategories();
        $data = [
            'title' => 'Search Results',
            'description' => 'Search results for your query',
            'posts' => [],
            'categories' => $categories
        ];

        if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['q'])) {
            $term = trim($_GET['q']);
            $results = $this->postModel->searchPosts($term);
            $data['posts'] = $results;
            $data['description'] = "Search results for: " . $term;
        }

        $this->view('pages/index', $data);
    }

    public function category($slug) {
        $posts = $this->postModel->getPostsByCategory($slug);
        $categories = $this->postModel->getCategories();

        $data = [
            'title' => 'Category: ' . $slug,
            'description' => 'Posts in this category',
            'posts' => $posts,
            'categories' => $categories
        ];

        $this->view('pages/index', $data);
    }
}
