<?php

namespace Lab3\Tests;

use Lab3\Controllers\PostController;
use Lab3\IRepositories\CommentsRepositoryInterface;
use Lab3\IRepositories\PostsRepositoryInterface;
use Lab3\IRepositories\UsersRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class PostControllerTest extends TestCase {
    private $commentsRepository;
    private $postsRepository;
    private $usersRepository;
    private $controller;

    protected function setUp(): void {
        $this->commentsRepository = $this->createMock(CommentsRepositoryInterface::class);
        $this->postsRepository = $this->createMock(PostsRepositoryInterface::class);
        $this->usersRepository = $this->createMock(UsersRepositoryInterface::class);

        $this->controller = new PostController(
            $this->commentsRepository,
            $this->postsRepository,
            $this->usersRepository
        );
    }

    public function testReturnsSuccessResponse(): void {
        $data = [
            'author_uuid' => Uuid::uuid4()->toString(),
            'post_uuid' => Uuid::uuid4()->toString(),
            'text' => 'Test comment',
        ];

        $this->usersRepository->method('get')->willReturn(true);
        $this->postsRepository->method('get')->willReturn(true);

        $response = $this->controller->addComment($data);

        $this->assertStringContainsString('success', $response);
    }

    public function testReturnsErrorForInvalidUUID(): void {
        $data = [
            'author_uuid' => 'invalid-uuid',
            'post_uuid' => Uuid::uuid4()->toString(),
            'text' => 'Test comment',
        ];

        $response = $this->controller->addComment($data);

        $this->assertStringContainsString('Invalid UUID format', $response);
    }

    public function testReturnsErrorWhenUserNotFound(): void {
        $data = [
            'author_uuid' => Uuid::uuid4()->toString(),
            'post_uuid' => Uuid::uuid4()->toString(),
            'text' => 'Test comment',
        ];

        $this->usersRepository->method('get')->willReturn(null);
        $this->postsRepository->method('get')->willReturn(true);

        $response = $this->controller->addComment($data);

        $this->assertStringContainsString('Author or post not found', $response);
    }

    public function testReturnsErrorWhenMissingFields(): void {
        $data = [
            'author_uuid' => Uuid::uuid4()->toString(),
            'post_uuid' => '',
            'text' => '',
        ];

        $response = $this->controller->addComment($data);

        $this->assertStringContainsString('Missing required fields', $response);
    }
}
