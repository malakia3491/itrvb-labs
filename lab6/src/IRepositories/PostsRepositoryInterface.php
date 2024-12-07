<?php

namespace Lab3\IRepositories;

use Lab3\Domain\Post\Post;

interface PostsRepositoryInterface {
    public function get(string $uuid): ?Post;
    public function save(Post $post): void;
    public function delete(string $uuid): void; 
}