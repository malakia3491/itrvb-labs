<?php

use Lab3\Controllers\PostController;

require './src/Scripts/FillDb.php';
require 'vendor/autoload.php';

$pdo = new PDO('sqlite:database/db.db');

$commentsRepository = new Lab3\Repositories\CommentsRepository($pdo);
$postsRepository = new Lab3\Repositories\PostsRepository($pdo);
$usersRepository = new Lab3\Repositories\UsersRepository($pdo);

$postController = new PostController($commentsRepository, $postsRepository, $usersRepository);

$requestMethod = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['REQUEST_URI'];

if ($requestMethod === 'GET' && $path === '/lab6/seed') {
    seedDatabase($pdo);
}

if ($requestMethod === 'POST' && $path === '/lab6/posts/comment') {
    $data = json_decode(file_get_contents('php://input'), true);
    echo $postController->addComment($data);
}

if ($requestMethod === 'DELETE' && preg_match('/^\/lab6\/posts\?uuid=/', $path)) {
    $uuid = $_GET['uuid'] ?? '';
    echo $postController->deletePost($uuid);
}