<?php
require_once __DIR__ . '/app/includes/db_connect.php';
require_once __DIR__ . '/app/models/StudentModel.php';
require_once __DIR__ . '/app/controllers/MainController.php';

session_start();

use App\Controllers\MainController;

$controller = new MainController($conn);
$routes = [
    'home' => 'home',
    'login' => 'login',
    'logout' => 'logout',
    'register' => 'register',
    'dashboard' => 'dashboard',
    'profile' => 'profile',
    'upload_profile_picture' => 'uploadProfilePicture',
    'change_password' => 'changePassword',
    'create_student' => 'createStudent',
    'edit_student' => 'editStudent',
    'delete_student' => 'deleteStudent',
];

$action = $_GET['action'] ?? 'home';
$method = $routes[$action] ?? 'home';
$controller->$method();
