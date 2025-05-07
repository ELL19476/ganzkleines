<?php
// Simple routing logic based on the request URI
$request = $_SERVER['REQUEST_URI'];

if ($request === '/voting') {
    include 'voting.php';
} elseif ($request === '/login') {
    include 'login/index.php';
} elseif ($request === '/register') {
    include 'login/register.php';
} elseif ($request === '/logout') {
    session_start();
    session_destroy();
    header("Location: /");
    exit;
} else {
    include 'home.php';
}
?>