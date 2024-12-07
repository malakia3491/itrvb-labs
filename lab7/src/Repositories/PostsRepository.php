<?php

namespace Lab3\Repositories;

use Lab3\Domain\Post\Post;
use Lab3\Exceptions\PostNotFoundException;
use Lab3\IRepositories\PostsRepositoryInterface;
use PDO;    

class PostsRepository implements PostsRepositoryInterface {
    private PDO $connection;

    public function __construct(PDO $connection) {
        $this->connection = $connection;
    }

    public function get(string $uuid): ?Post {
        $query = "SELECT * FROM posts WHERE uuid = :uuid";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':uuid', $uuid);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            throw new PostNotFoundException("Post with UUID {$uuid} not found");
        }

        return new Post(
            $data['uuid'],
            $data['author_uuid'],
            $data['title'],
            $data['text']
        );
    }

    public function save(Post $post): void {
        $query = "INSERT INTO posts (uuid, author_uuid, title, text) VALUES (:uuid, :author_uuid, :title, :text)";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':uuid', $post->getUuid());
        $stmt->bindParam(':author_uuid', $post->getAuthorUuid());
        $stmt->bindParam(':title', $post->getTitle());
        $stmt->bindParam(':text', $post->getText());
        $stmt->execute();
    }

    public function delete(string $uuid): void {
        $statement = $this->connection->prepare('DELETE FROM posts WHERE uuid = :uuid');
        $statement->execute(['uuid' => $uuid]);
    }
}
