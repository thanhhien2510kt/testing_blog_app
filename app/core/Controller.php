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
        // Check for view file
        if(file_exists('../app/views/' . $view . '.php')) {
            require_once '../app/views/' . $view . '.php';
        } else {
            // View does not exist
            die('View does not exist: ' . $view);
        }
    }
}
