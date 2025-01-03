<?php

namespace Lab3\Repositories;

use Lab3\Domain\User\User;
use Lab3\IRepositories\UsersRepositoryInterface;
use Psr\Log\LoggerInterface;
use PDO;    

class UsersRepository implements UsersRepositoryInterface {
    private PDO $connection;
    private LoggerInterface $logger;

    public function __construct(\PDO $pdo, LoggerInterface $logger)
    {
        $this->connection = $pdo;
        $this->logger = $logger;
    }

    public function findByUsername($username): ?user {
        $statement = $this->connection->prepare('SELECT * FROM users WHERE username = :username');
        $statement->execute(['username' => $username]);
        $result = $statement->fetch();

        if (!$result) {
            $this->logger->warning('Пользователь с username не найден: ' . $username);
            return null;
        }

        return new User(
            $result['uuid'],
            $result['username'],
            $result['first_name'],
            $result['last_name']
        );
    }

    public function get(string $uuid): ?User {
        $statement = $this->connection->prepare('SELECT * FROM users WHERE uuid = :uuid');
        $statement->execute(['uuid' => $uuid]);
        $result = $statement->fetch();

        if (!$result) {
            $this->logger->warning('Пользователь с UUID не найден: ' . $uuid);
            return null;
        }

        return new User(
            $result['uuid'],
            $result['username'],
            $result['first_name'],
            $result['last_name']
        );
    }

    public function save(User $user): void {
        $statement = $this->connection->prepare(
            'INSERT INTO users (uuid, username, first_name, last_name) VALUES (:uuid, :username, :first_name, :last_name)'
        );

        $statement->execute([
            'uuid' => $user->getUuid(),
            'username' => $user->getUsername(),
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName(),
        ]);
        $this->logger->info('Сохранен пользователь с UUID: ' . $user->getUuid());
    }

    public function delete(string $uuid): void {
        $user = $this->get($uuid);
        if (!$user) {
            $this->logger->warning('Пользователь с UUID не найден для удаления: '. $uuid);
        }
        $statement = $this->connection->prepare('DELETE FROM users WHERE uuid = :uuid');
        $statement->execute(['uuid' => $uuid]);
    }

    public function saveToken(string $userUuid, string $token, int $expiresAt): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO tokens (user_uuid, token, expires_at) VALUES (:user_uuid, :token, :expires_at)'
        );
        $statement->execute([
            'user_uuid' => $userUuid,
            'token' => $token,
            'expires_at' => date('Y-m-d H:i:s', $expiresAt),
        ]);
    }

    public function invalidateToken(string $token): void
    {
        $statement = $this->connection->prepare('DELETE FROM tokens WHERE token = :token');
        $statement->execute(['token' => $token]);
        $this->logger->info('Токен инвалидирован');
    }

    public function getToken($token): array|false
    {
        $statement = $this->connection->prepare('SELECT * FROM tokens WHERE token = :token');
        $statement->execute(['token' => $token]);
        $result = $statement->fetch();
        return $result;
    }
}