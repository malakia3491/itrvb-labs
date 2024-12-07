<?php

namespace Lab3\IRepositories;

use Lab3\Domain\Like\Like;
use Lab3\Exceptions\LikeAlreadyExistsException;

interface LikesRepositoryInterface
{
    /**
     * @throws LikeAlreadyExistsException
     */
    public function save(Like $like): void;

    public function getByLikeableUuid(string $likeableUuid, string $likeableType): array;
}