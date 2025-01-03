<?php

namespace Lab3\Repositories;

use Lab3\Domain\Like\Like;
use Lab3\Exceptions\LikeAlreadyExistsException;
use Lab3\IRepositories\LikesRepositoryInterface;
use Psr\Log\LoggerInterface;
use PDO;

class LikesRepository implements LikesRepositoryInterface
{
    private PDO $connection;
    private LoggerInterface $logger;

    public function __construct(PDO $connection, LoggerInterface $logger)
    {
        $this->connection = $connection;
        $this->logger = $logger;
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
        $this->logger->info('Сохранен лайк с UUID: ' . $like->getUuid());
    }

    public function getByLikeableUuid(string $likeableUuid, string $likeableType): array
    {
        $query = "SELECT * FROM likes WHERE likeable_uuid = ? AND likeable_type = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([$likeableUuid, $likeableType]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!$data) {
            $this->logger->warning('Лайки объекта с UUID  не найдены: ' . $likeableUuid);
        }
        return $data;
    }

    public function getByLikeableUuidAndUserUuid(string $likeableUuid, string $userUuid, string $likeableType): array
    {
        $query = "SELECT * FROM likes WHERE likeable_uuid = ? AND likeable_type = ? AND user_Uuid = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([$likeableUuid, $likeableType, $userUuid,]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!$data) {
            $this->logger->warning('Лайки объекта с UUID и пользователя с UUId  не найдены: ' . $likeableUuid . ', ' . $userUuid);
        }
        return $data;
    }
}