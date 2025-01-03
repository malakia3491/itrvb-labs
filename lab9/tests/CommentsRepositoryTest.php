<?php

use PHPUnit\Framework\TestCase;
use Monolog\Logger;
use Monolog\Handler\TestHandler;

use Lab3\IRepositories\CommentsRepositoryInterface;
use Lab3\Repositories\CommentsRepository;
use Lab3\Exceptions\CommentNotFoundException;
use Lab3\Domain\Comment\Comment;


class CommentsRepositoryTest extends TestCase {
    private CommentsRepositoryInterface $repository;

    protected function setUp(): void {
        $pdo = new PDO('sqlite::memory:');
        $pdo->exec("CREATE TABLE comments (
            uuid TEXT PRIMARY KEY,
            post_uuid TEXT,
            author_uuid TEXT,
            text TEXT
        )");
        $testHandler = new TestHandler();
        $logger = new Logger('test', [$testHandler]);
        $this->repository = new CommentsRepository($pdo, $logger);
    }

    public function testCommentIsSavedToRepository(): void {
        $comment = new Comment('1', 'post-uuid', 'author-uuid', 'Test Comment');
        $this->repository->save($comment);

        $savedComment = $this->repository->get('1');
        $this->assertEquals($comment, $savedComment);
    }

    public function testRepositoryFindsCommentByUUID(): void {
        $comment = new Comment('2', 'post-uuid', 'author-uuid', 'Another Comment');
        $this->repository->save($comment);

        $foundComment = $this->repository->get('2');
        $this->assertEquals($comment, $foundComment);
    }

    public function testRepositoryThrowsExceptionIfCommentNotFound(): void {
        $this->expectException(CommentNotFoundException::class);
        $this->repository->get('non-existent-uuid');
    }
}