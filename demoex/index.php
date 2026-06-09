<?php
ini_set('default_charset', 'UTF-8');  
require_once("./vendor/autoload.php");

use App\Routers\Router;

session_start();

$user_id = 0;
$user_name = "";
$user_role = "";

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['user_name'];
    $user_role = $_SESSION['user_role'];
}

$router = new Router();
$url = $_SERVER['REQUEST_URI'];
echo $router->route($url);