<?php

require_once 'vendor/autoload.php';

use Ramsey\Uuid\Uuid;

function seedDatabase(\PDO $pdo): void {
    $faker = Faker\Factory::create();

    // Очистка таблиц
    $pdo->exec('DELETE FROM comments');
    $pdo->exec('DELETE FROM posts');
    $pdo->exec('DELETE FROM users');

    // Добавление пользователей
    $userUuids = [];
    for ($i = 0; $i < 10; $i++) {
        $uuid = Uuid::uuid4()->toString();
        $userUuids[] = $uuid;
        $stmt = $pdo->prepare('INSERT INTO users (uuid, username, first_name, last_name) VALUES (:uuid, :username, :first_name, :last_name)');
        $stmt->execute([
            ':uuid' => $uuid,
            ':username' => $faker->userName,
            ':first_name' => $faker->firstName,
            ':last_name' => $faker->lastName,
        ]);
    }

    // Добавление постов
    $postUuids = [];
    for ($i = 0; $i < 20; $i++) {
        $uuid = Uuid::uuid4()->toString();
        $postUuids[] = $uuid;
        $stmt = $pdo->prepare('INSERT INTO posts (uuid, author_uuid, title, text) VALUES (:uuid, :author_uuid, :title, :text)');
        $stmt->execute([
            ':uuid' => $uuid,
            ':author_uuid' => $faker->randomElement($userUuids),
            ':title' => $faker->sentence,
            ':text' => $faker->paragraph,
        ]);
    }

    // Добавление комментариев
    for ($i = 0; $i < 50; $i++) {
        $uuid = Uuid::uuid4()->toString();
        $stmt = $pdo->prepare('INSERT INTO comments (uuid, post_uuid, author_uuid, text) VALUES (:uuid, :post_uuid, :author_uuid, :text)');
        $stmt->execute([
            ':uuid' => $uuid,
            ':post_uuid' => $faker->randomElement($postUuids),
            ':author_uuid' => $faker->randomElement($userUuids),
            ':text' => $faker->sentence,
        ]);
    }

    echo "Database seeded successfully!\n";
}