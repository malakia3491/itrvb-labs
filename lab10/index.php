<?php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use Lab3\Controllers\PostController;
use Lab3\Controllers\LikeController;
use Lab3\Controllers\AuthController;

require './src/Scripts/FillDb.php';
require_once  'vendor/autoload.php';

$jwtSecret = 'your_secret_key';
$pdo = new PDO('sqlite:database/db.db');
$logger = new Logger('name');
$logger->pushHandler(new StreamHandler('logs/' . date('Y-m-d') . '.log'));

$commentsRepository = new Lab3\Repositories\CommentsRepository($pdo, $logger);
$postsRepository = new Lab3\Repositories\PostsRepository($pdo, $logger);
$usersRepository = new Lab3\Repositories\UsersRepository($pdo, $logger);
$likesRepository = new Lab3\Repositories\LikesRepository($pdo, $logger);

$authController = new AuthController($usersRepository, $logger, $jwtSecret);
$postController = new PostController($commentsRepository, $postsRepository, $usersRepository);
$likeController = new LikeController($commentsRepository, $postsRepository, $usersRepository, $likesRepository);

$requestMethod = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

function sendJsonResponse($data, $statusCode = 200) {
    header('Content-Type: application/json');
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}

switch (true) {

    case $requestMethod === 'POST' && $path === '/lab10/seeddb':
        $data = json_decode(file_get_contents('php://input'), true);
        $usersNumber = $data['usersNumber'];
        $postsNumber = $data['postsNumber'];
        seedDatabase($pdo, $usersNumber, $postsNumber);
        break;

    // Маршрут для входа
    case $requestMethod === 'POST' && $path === '/lab10/login':
        $data = json_decode(file_get_contents('php://input'), true);
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';
        $token = $authController->login($username, $password);
        if ($token) {
            sendJsonResponse(['token' => $token]);
        } else {
            sendJsonResponse(['error' => 'Invalid credentials'], 401);
        }
        break;

    // Маршрут для выхода
    case $requestMethod === 'POST' && $path === '/lab10/logout':
        $token = $authController->getTokenFromRequest();
        if ($token) {
            $authController->logout($token);
            sendJsonResponse(['message' => 'Logged out successfully']);
        } else {
            sendJsonResponse(['error' => 'Token not provided'], 400);
        }
        break;

    // Получение лайков для поста
    case $requestMethod === 'GET' && preg_match('/^\/lab10\/posts\/likes$/', $path):
        $uuid = $_GET['uuid'] ?? '';
        $type = "post";
        echo $likeController->getByLikeableUuid($uuid, $type);
        break;

    // Получение лайков для комментария
    case $requestMethod === 'GET' && preg_match('/^\/lab10\/comments\/likes$/', $path):
        $uuid = $_GET['uuid'] ?? '';
        $type = "comment";
        echo $likeController->getByLikeableUuid($uuid, $type);
        break;

    // Добавление лайка
    case $requestMethod === 'POST' && $path === '/lab10/addlike':
        $userUuid = $authController->authenticate();
        if ($userUuid) {
            $data = json_decode(file_get_contents('php://input'), true);
            $data['user_uuid'] = $userUuid;
            echo $likeController->addLike($data);
        } else {
            sendJsonResponse(['error' => 'Unauthorized'], 401);
        }
        break;

    // Добавление комментария
    case $requestMethod === 'POST' && $path === '/lab10/posts/comment':
        $userUuid = $authController->authenticate();
        if ($userUuid) {
            $data = json_decode(file_get_contents('php://input'), true);
            $data['user_uuid'] = $userUuid;
            echo $postController->addComment($data);
        } else {
            sendJsonResponse(['error' => 'Unauthorized'], 401);
        }
        break;

    // Удаление поста
    case $requestMethod === 'DELETE' && preg_match('/^\/lab10\/posts\?uuid=/', $path):
        $userUuid = $authController->authenticate();
        if ($userUuid) {
            $uuid = $_GET['uuid'] ?? '';
            echo $postController->deletePost($uuid);
        } else {
            sendJsonResponse(['error' => 'Unauthorized'], 401);
        }
        break;

    // Маршрут не найден
    default:
        sendJsonResponse(['error' => 'Not Found'], 404);
        break;
}