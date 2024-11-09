<?php

namespace Lab3\Repositories;

use Lab3\Post;

interface PostsRepositoryInterface {
    public function get(string $uuid): ?Post;
    public function save(Post $post): void;
}