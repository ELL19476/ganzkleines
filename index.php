<?php
// Simple routing logic based on the request URI
$request = $_SERVER['REQUEST_URI'];

if ($request === '/voting') {
    include 'voting.php';
} else {
    include 'home.php';
}
?>