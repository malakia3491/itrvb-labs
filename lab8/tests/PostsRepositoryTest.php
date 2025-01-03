<?php

use PHPUnit\Framework\TestCase;
use Monolog\Logger;
use Monolog\Handler\TestHandler;

use Lab3\IRepositories\PostsRepositoryInterface;
use Lab3\Repositories\PostsRepository;
use Lab3\Exceptions\PostNotFoundException;
use Lab3\Domain\Post\Post;

class PostsRepositoryTest extends TestCase {
    private PostsRepositoryInterface $repository;

    protected function setUp(): void {
        $pdo = new PDO('sqlite::memory:');
        $pdo->exec("CREATE TABLE posts (
            uuid TEXT PRIMARY KEY,
            author_uuid TEXT,
            title TEXT,
            text TEXT
        )");
        $testHandler = new TestHandler();
        $logger = new Logger('test', [$testHandler]);
        $this->repository = new PostsRepository($pdo, $logger);
    }

    public function testPostIsSavedToRepository(): void {
        $post = new Post('1', 'author-uuid', 'Test Title', 'Test Text');
        $this->repository->save($post);

        $savedPost = $this->repository->get('1');
        $this->assertEquals($post, $savedPost);
    }

    public function testRepositoryFindsPostByUUID(): void {
        $post = new Post('2', 'author-uuid', 'Another Title', 'Another Text');
        $this->repository->save($post);
        $foundPost = $this->repository->get('2');
        $this->assertEquals($post, $foundPost);
    }

    public function testRepositoryThrowsExceptionIfPostNotFound(): void {
        $this->expectException(PostNotFoundException::class);
        $this->repository->get('non-existent-uuid');
    }
}