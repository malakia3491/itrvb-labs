<?php

require __DIR__ . '/vendor/autoload.php';

use Lab3\User;
use Lab3\Article;
use Lab3\Comment;

use Faker\Factory;

$faker = Factory::create();

$user = new User(1, $faker->firstName, $faker->lastName);
$article = new Article(1, $user->id, $faker->sentence, $faker->paragraph);
$comment = new Comment(1, $user->id, $article->id, $faker->sentence);

echo "User: {$user->firstName} {$user->lastName}\n";
echo "Article: {$article->title} - {$article->text}\n";
echo "Comment: {$comment->text}\n";