<?php

namespace Lab3\Controllers;

use Lab3\IRepositories\CommentsRepositoryInterface;
use Lab3\IRepositories\PostsRepositoryInterface;
use Lab3\IRepositories\UsersRepositoryInterface;
use Lab3\IRepositories\LikesRepositoryInterface;
use Lab3\Domain\Like\Like;

use Ramsey\Uuid\Uuid;

class LikeController {
    private CommentsRepositoryInterface $commentsRepository;
    private PostsRepositoryInterface $postsRepository;
    private UsersRepositoryInterface $usersRepository;
    private LikesRepositoryInterface $likesRepository;

    public function __construct(
        CommentsRepositoryInterface $commentsRepository,
        PostsRepositoryInterface $postsRepository,
        UsersRepositoryInterface $usersRepository,
        LikesRepositoryInterface $likesRepository
    ) {
        $this->commentsRepository = $commentsRepository;
        $this->postsRepository = $postsRepository;
        $this->usersRepository = $usersRepository;
        $this->likesRepository = $likesRepository;
    }

    public function addLike(array $data): string {
        try {
            // Валидация данных
            if (empty($data['user_uuid']) || empty($data['likeable_uuid']) || empty($data['likeable_type'])) {
                http_response_code(400);
                return json_encode(['error' => 'Missing required fields.']);
            }

            // Проверка валидности UUID
            if (!Uuid::isValid($data['user_uuid']) || !Uuid::isValid($data['likeable_uuid'])) {
                http_response_code(400);
                return json_encode(['error' => 'Invalid UUID format.']);
            }

            // Проверка существования
            $likeable_obj = false;
            $user = $this->usersRepository->get($data['user_uuid']);
            if($data['likeable_type'] === 'post')
                $likeable_obj = $this->postsRepository->get($data['likeable_uuid']);
            else
                $likeable_obj = $this->commentsRepository->get($data['likeable_uuid']);
            
            if (!$user || !$likeable_obj) {
                http_response_code(404);
                return json_encode(['error' => 'User or likeable object not found.']);
            }

            // Создание комментария
            $like = new Like(
                Uuid::uuid4()->toString(),
                $data['likeable_uuid'],
                $data['user_uuid'],
                $data['likeable_type']
            );

            $this->likesRepository->save($like);

            http_response_code(201);
            return json_encode(['success' => 'Like added successfully.']);
        } catch (\Exception $e) {
            http_response_code(500);
            return json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getByLikeableUuid(string $uuid, string $type): string {
        try {
            // Валидация данных
            if (empty($uuid) || empty($type)) {
                http_response_code(400);
                return json_encode(['error' => 'Missing required fields.']);
            }

            // Проверка валидности UUID
            if (!Uuid::isValid( $uuid)) {
                http_response_code(400);
                return json_encode(['error' => 'Invalid UUID format.']);
            }

            // Проверка существования
            $likeable_obj = false;
            if($type === 'post')
                $likeable_obj = $this->postsRepository->get($uuid);
            else
                $likeable_obj = $this->commentsRepository->get($uuid);
            
            if (!$likeable_obj) {
                http_response_code(404);
                return json_encode(['error' => 'Likeable object not found.']);
            }

            $data_likes = $this->likesRepository->getByLikeableUuid($uuid, $type);

            http_response_code(200);
            return json_encode(['likes' => $data_likes]);
        } catch (\Exception $e) {
            http_response_code(500);
            return json_encode(['error' => $e->getMessage()]);
        }
    }
}