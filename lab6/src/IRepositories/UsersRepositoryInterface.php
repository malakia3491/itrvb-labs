<?php

namespace Lab3\IRepositories;

use Lab3\Domain\User\User;

interface UsersRepositoryInterface {
    public function get(string $uuid): ?User;
    public function save(User $user): void;
    public function delete(string $uuid): void;
}
