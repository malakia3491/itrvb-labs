<?php

namespace Lab3\IRepositories;

use Lab3\Comment\Comment;

interface CommentsRepositoryInterface {
    public function get(string $uuid): ?Comment;
    public function save(Comment $comment): void;
}
