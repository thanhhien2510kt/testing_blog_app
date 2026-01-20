<?php
require_once '../app/config/config.php';
require_once '../app/core/Database.php';

$db = new Database();

// Add parent_id column
$sql = "ALTER TABLE categories ADD COLUMN parent_id INT DEFAULT NULL AFTER slug"; // AFTER slug just for order

try {
    $db->query($sql);
    if($db->execute()) {
        echo "Successfully added 'parent_id' column to 'categories' table.<br>";
    } else {
        echo "Failed to add column (or it might already exist).<br>";
    }

    // Add Foreign Key constraint (optional but good practice)
    $sql_fk = "ALTER TABLE categories ADD CONSTRAINT fk_category_parent FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL";
    $db->query($sql_fk);
    if($db->execute()) {
        echo "Successfully added Foreign Key constraint.<br>";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
