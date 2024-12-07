<?php

namespace Lab3\Repositories;

use Lab3\Domain\Comment\Comment;
use Lab3\Exceptions\CommentNotFoundException;
use Lab3\IRepositories\CommentsRepositoryInterface;
use PDO;

class CommentsRepository implements CommentsRepositoryInterface {
    private PDO $connection;

    public function __construct(PDO $connection) {
        $this->connection = $connection;
    }

    public function get(string $uuid): ?Comment {
        $query = "SELECT * FROM comments WHERE uuid = :uuid";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':uuid', $uuid);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            throw new CommentNotFoundException("Comment with UUID {$uuid} not found");
        }

        return new Comment(
            $data['uuid'],
            $data['post_uuid'],
            $data['author_uuid'],
            $data['text']
        );
    }

    public function save(Comment $comment): void {
        $query = "INSERT INTO comments (uuid, post_uuid, author_uuid, text) VALUES (:uuid, :post_uuid, :author_uuid, :text)";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':uuid', $comment->getUuid());
        $stmt->bindParam(':post_uuid', $comment->getPostUuid());
        $stmt->bindParam(':author_uuid', $comment->getAuthorUuid());
        $stmt->bindParam(':text', $comment->getText());
        $stmt->execute();
    }

    public function delete(string $uuid): void {
        $statement = $this->connection->prepare('DELETE FROM comments WHERE uuid = :uuid');
        $statement->execute(['uuid' => $uuid]);
    }
}
