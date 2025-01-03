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
}