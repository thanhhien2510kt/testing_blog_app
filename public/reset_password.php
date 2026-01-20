<?php
require_once '../app/config/config.php';
require_once '../app/core/Database.php';

$db = new Database();

$password = 'admin123';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$email = 'admin@example.com';

$db->query('UPDATE users SET password = :password WHERE email = :email');
$db->bind(':password', $hashed_password);
$db->bind(':email', $email);

if($db->execute()) {
    echo "<h1>Password check</h1>";
    echo "Admin password reset successfully to: <b>admin123</b><br>";
    echo "New Hash: " . $hashed_password . "<br>";
    echo "<a href='" . URLROOT . "/users/login'>Go to Login</a>";
} else {
    echo "Failed to reset password.";
}
