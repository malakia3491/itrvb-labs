<?php

namespace Lab3\Domain\Like;

class Like
{
    private string $uuid;
    private string $postUuid; // или commentUuid
    private string $userUuid;
    private string $likeableType; // 'post' or 'comment'

    public function __construct(string $uuid, string $postUuid, string $userUuid, string $likeableType = 'post')
    {
        $this->uuid = $uuid;
        $this->postUuid = $postUuid;
        $this->userUuid = $userUuid;
        $this->likeableType = $likeableType;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getPostUuid(): string
    {
        return $this->postUuid;
    }

    public function getUserUuid(): string
    {
        return $this->userUuid;
    }

    public function getLikeableType(): string
    {
        return $this->likeableType;
    }

}