<?php

namespace Lab3\Repositories;

use Lab3\Domain\User\User;
use Lab3\IRepositories\UsersRepositoryInterface;
use PDO;    

class UsersRepository implements UsersRepositoryInterface {
    private PDO $connection;

    public function __construct(\PDO $pdo) {
        $this->connection = $pdo;
    }

    public function get(string $uuid): ?User {
        $statement = $this->connection->prepare('SELECT * FROM users WHERE uuid = :uuid');
        $statement->execute(['uuid' => $uuid]);
        $result = $statement->fetch();

        if (!$result) {
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
    }

    public function delete(string $uuid): void {
        $statement = $this->connection->prepare('DELETE FROM users WHERE uuid = :uuid');
        $statement->execute(['uuid' => $uuid]);
    }
}
