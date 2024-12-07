<?php

use Lab3\Controllers\PostController;
use Lab3\Controllers\LikeController;
// require './src/Scripts/FillDb.php';
require_once  'vendor/autoload.php';


$pdo = new PDO('sqlite:database/db.db');

$commentsRepository = new Lab3\Repositories\CommentsRepository($pdo);
$postsRepository = new Lab3\Repositories\PostsRepository($pdo);
$usersRepository = new Lab3\Repositories\UsersRepository($pdo);
$likesRepository = new Lab3\Repositories\LikesRepository($pdo);

$postController = new PostController($commentsRepository, $postsRepository, $usersRepository);
$likeContoller = new LikeController($commentsRepository, $postsRepository, $usersRepository, $likesRepository);

$requestMethod = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['REQUEST_URI'];

// if ($requestMethod === 'GET' && $path === '/lab7/seed') {
//     seedDatabase($pdo);
// }

if ($requestMethod === 'GET' && preg_match('/^\/lab7\/posts\/likes\?uuid=/', $path)) {
    $uuid = $_GET['uuid'] ?? '';
    $type = "post";
    echo $likeContoller->getByLikeableUuid($uuid, $type);
}

if ($requestMethod === 'GET' && preg_match('/^\/lab7\/comments\/likes\?uuid=/', $path)) {
    $uuid = $_GET['uuid'] ?? '';
    $type = "comment";
    echo $likeContoller->getByLikeableUuid($uuid, $type);
}

if ($requestMethod === 'POST' && $path === '/lab7/addlike') {
    $data = json_decode(file_get_contents('php://input'), true);
    echo $likeContoller->addLike($data);
}

if ($requestMethod === 'POST' && $path === '/lab7/posts/comment') {
    $data = json_decode(file_get_contents('php://input'), true);
    echo $postController->addComment($data);
}

if ($requestMethod === 'DELETE' && preg_match('/^\/lab7\/posts\?uuid=/', $path)) {
    $uuid = $_GET['uuid'] ?? '';
    echo $postController->deletePost($uuid);
}