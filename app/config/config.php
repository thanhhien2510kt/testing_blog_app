<?php
// App Root
define('APPROOT', dirname(dirname(__FILE__)));
// URL Root (Dynamic)
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$script_dirname = dirname($_SERVER['SCRIPT_NAME']);
// Remove backslashes for Windows paths to ensure URL is valid
$script_dirname = str_replace('\\', '/', $script_dirname);
define('URLROOT', $protocol . $_SERVER['HTTP_HOST'] . $script_dirname);
// Site Name
define('SITENAME', 'QA Master Blog');

// Database Params
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'blog_db');
