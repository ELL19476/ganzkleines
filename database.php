<?php
$host = 'srv1472.hstgr.io';
$dbname = 'u937524310_ganz_kleines';
$username = 'u937524310_admin';
$password = 'PuJn+A:3';

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // Set error mode to exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle connection error
    ?>
        <div class="alert alert-danger" role="alert">
            <strong>Error:</strong> Could not connect to the database. Please check your credentials.
        </div>
    <?php
}
?>