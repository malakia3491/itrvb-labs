<?php

namespace Lab3\Repositories;

use Lab3\Domain\Like\Like;
use Lab3\Exceptions\LikeAlreadyExistsException;
use Lab3\IRepositories\LikesRepositoryInterface;
use PDO;

class LikesRepository implements LikesRepositoryInterface
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(Like $like): void
    {
        $query = "INSERT INTO likes (uuid, likeable_uuid, user_uuid, likeable_type) VALUES (?, ?, ?, ?)";
        $stmt = $this->connection->prepare($query);
        try {
            $stmt->execute([$like->getUuid(), $like->getPostUuid(), $like->getUserUuid(), $like->getLikeableType()]);
        } catch (\PDOException $e) {
            if ($e->getCode() == '23505') { // SQLite's unique constraint violation
                throw new LikeAlreadyExistsException("Like already exists for this user and post/comment.");
            }
            throw $e; 
        }
    }

    public function getByLikeableUuid(string $likeableUuid, string $likeableType): array
    {
        $query = "SELECT * FROM likes WHERE likeable_uuid = ? AND likeable_type = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([$likeableUuid, $likeableType]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByLikeableUuidAndUserUuid(string $likeableUuid, string $userUuid, string $likeableType): array
    {
        $query = "SELECT * FROM likes WHERE likeable_uuid = ? AND likeable_type = ? AND user_Uuid = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([$likeableUuid, $likeableType, $userUuid,]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}