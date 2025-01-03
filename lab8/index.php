<?php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use Lab3\Controllers\PostController;
use Lab3\Controllers\LikeController;
// require './src/Scripts/FillDb.php';
require_once  'vendor/autoload.php';

$pdo = new PDO('sqlite:database/db.db');
$logger = new Logger('name');
$logger->pushHandler(new StreamHandler('logs/' . date('Y-m-d') . '.log'));

$commentsRepository = new Lab3\Repositories\CommentsRepository($pdo, $logger);
$postsRepository = new Lab3\Repositories\PostsRepository($pdo, $logger);
$usersRepository = new Lab3\Repositories\UsersRepository($pdo, $logger);
$likesRepository = new Lab3\Repositories\LikesRepository($pdo, $logger);

$postController = new PostController($commentsRepository, $postsRepository, $usersRepository);
$likeContoller = new LikeController($commentsRepository, $postsRepository, $usersRepository, $likesRepository);

$requestMethod = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['REQUEST_URI'];

// if ($requestMethod === 'GET' && $path === '/lab8/seed') {
//     seedDatabase($pdo);
// }

if ($requestMethod === 'GET' && preg_match('/^\/lab8\/posts\/likes\?uuid=/', $path)) {
    $uuid = $_GET['uuid'] ?? '';
    $type = "post";
    echo $likeContoller->getByLikeableUuid($uuid, $type);
}

if ($requestMethod === 'GET' && preg_match('/^\/lab8\/comments\/likes\?uuid=/', $path)) {
    $uuid = $_GET['uuid'] ?? '';
    $type = "comment";
    echo $likeContoller->getByLikeableUuid($uuid, $type);
}

if ($requestMethod === 'POST' && $path === '/lab8/addlike') {
    $data = json_decode(file_get_contents('php://input'), true);
    echo $likeContoller->addLike($data);
}

if ($requestMethod === 'POST' && $path === '/lab8/posts/comment') {
    $data = json_decode(file_get_contents('php://input'), true);
    echo $postController->addComment($data);
}

if ($requestMethod === 'DELETE' && preg_match('/^\/lab8\/posts\?uuid=/', $path)) {
    $uuid = $_GET['uuid'] ?? '';
    echo $postController->deletePost($uuid);
}