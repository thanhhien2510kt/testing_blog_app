<?php
/*
 * Base Controller
 * Loads the models and views
 */
class Controller {
    // Load model
    public function model($modelString) {
        $modelClient = ucwords($modelString);
        // Require model file
        require_once '../app/models/' . $modelClient . '.php';

        // Instantiate model
        return new $modelClient();
    }

    // Load view
    public function view($view, $data = []) {
        // Inject Global Navigation Data
        // Helper to load model if not present (simple version for this core file)
        if(!class_exists('Category')){
            if(file_exists('../app/models/Category.php')){
                require_once '../app/models/Category.php';
            }
        }
        
        if(class_exists('Category')){
             // We can't easily use $this->model() because we might rely on db connection 
             // which is in the Model base. 
             // We assume the Controller subclass has initialized database or Model base handles it.
             // Actually, `new Category()` works because Category extends Model, and Model __construct creates Database.
             // But we need to be careful about not creating too many connections if Model creates one every time.
             // Checking app/core/Model.php would be wise, but for now assuming standard MVC behavior.
             
             $navCatModel = new Category();
             $allCats = $navCatModel->getCategories();
             
             $tree = [];
             $catsById = [];
             
             // Index by ID
             foreach($allCats as $cat){
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
             
             $data['nav_categories'] = $tree;
        }

        // Check for view file
        if(file_exists('../app/views/' . $view . '.php')) {
            require_once '../app/views/' . $view . '.php';
        } else {
            // View does not exist
            die('View does not exist: ' . $view);
        }
    }
}
