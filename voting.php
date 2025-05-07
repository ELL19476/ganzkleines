<?php
require 'database.php';

// Example query
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Output users (for testing)
echo '<pre>';
print_r($users);
echo '</pre>';
?>