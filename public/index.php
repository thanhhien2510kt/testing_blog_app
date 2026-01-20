<?php
require_once '../app/config/config.php';
require_once '../app/helpers/session_helper.php';
require_once '../app/helpers/url_helper.php';

// Load Core Libraries
// Auto-loader for core classes
spl_autoload_register(function($className){
    // Determine path based on if it's a Core library or not.
    // In this simple setup, we check app/core
    if(file_exists('../app/core/' . $className . '.php')){
        require_once '../app/core/' . $className . '.php';
    }
});

// Init Core App
$init = new App();
