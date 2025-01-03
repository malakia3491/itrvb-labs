<?php

namespace Lab3\IRepositories;

use Lab3\Domain\User\User;

interface UsersRepositoryInterface {
    public function findByUsername($username): ?user;
    public function get(string $uuid): ?User;
    public function save(User $user): void;
    public function delete(string $uuid): void;
    public function saveToken(string $userUuid, string $token, int $expiresAt): void;
    public function invalidateToken(string $token): void;
    public function getToken($token): array|false;
}