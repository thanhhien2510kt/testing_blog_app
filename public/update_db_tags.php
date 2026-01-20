<?php
require_once '../app/config/config.php';
require_once '../app/core/Database.php';

$db = new Database();

// Tags Table
$sql_tags = "CREATE TABLE IF NOT EXISTS tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// Post_Tags Table
$sql_post_tags = "CREATE TABLE IF NOT EXISTS post_tags (
    post_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (post_id, tag_id),
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

try {
    $db->query($sql_tags);
    if($db->execute()) {
        echo "Successfully created 'tags' table.<br>";
    } else {
        echo "Failed to create 'tags' table.<br>";
    }

    $db->query($sql_post_tags);
    if($db->execute()) {
        echo "Successfully created 'post_tags' table.<br>";
    } else {
        echo "Failed to create 'post_tags' table.<br>";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
