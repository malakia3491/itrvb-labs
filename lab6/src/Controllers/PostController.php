<?php

namespace Lab3\Controllers;

use Lab3\IRepositories\CommentsRepositoryInterface;
use Lab3\IRepositories\PostsRepositoryInterface;
use Lab3\IRepositories\UsersRepositoryInterface;
use Lab3\Domain\Comment\Comment;
use Ramsey\Uuid\Uuid;

class PostController {
    private CommentsRepositoryInterface $commentsRepository;
    private PostsRepositoryInterface $postsRepository;
    private UsersRepositoryInterface $usersRepository;

    public function __construct(
        CommentsRepositoryInterface $commentsRepository,
        PostsRepositoryInterface $postsRepository,
        UsersRepositoryInterface $usersRepository
    ) {
        $this->commentsRepository = $commentsRepository;
        $this->postsRepository = $postsRepository;
        $this->usersRepository = $usersRepository;
    }

    public function addComment(array $data): string {
        try {
            // Валидация данных
            if (empty($data['author_uuid']) || empty($data['post_uuid']) || empty($data['text'])) {
                http_response_code(400);
                return json_encode(['error' => 'Missing required fields.']);
            }

            // Проверка валидности UUID
            if (!Uuid::isValid($data['author_uuid']) || !Uuid::isValid($data['post_uuid'])) {
                http_response_code(400);
                return json_encode(['error' => 'Invalid UUID format.']);
            }

            // Проверка существования автора и статьи
            $author = $this->usersRepository->get($data['author_uuid']);
            $post = $this->postsRepository->get($data['post_uuid']);

            if (!$author || !$post) {
                http_response_code(404);
                return json_encode(['error' => 'Author or post not found.']);
            }

            // Создание комментария
            $comment = new Comment(
                Uuid::uuid4()->toString(),
                $data['post_uuid'],
                $data['author_uuid'],
                $data['text']
            );

            $this->commentsRepository->save($comment);

            http_response_code(201);
            return json_encode(['success' => 'Comment added successfully.']);
        } catch (\Exception $e) {
            http_response_code(500);
            return json_encode(['error' => $e->getMessage()]);
        }
    }

    public function deletePost(string $uuid): string {
        try {
            if (!Uuid::isValid($uuid)) {
                http_response_code(400);
                return json_encode(['error' => 'Invalid UUID format.']);
            }
    
            $post = $this->postsRepository->get($uuid);
            if (!$post) {
                http_response_code(404);
                return json_encode(['error' => 'Post not found.']);
            }
    
            $this->postsRepository->delete($uuid);
    
            http_response_code(200);
            return json_encode(['success' => 'Post deleted successfully.']);
        } catch (\Exception $e) {
            http_response_code(500);
            return json_encode(['error' => $e->getMessage()]);
        }
    }
}