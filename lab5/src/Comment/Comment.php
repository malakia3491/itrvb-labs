<?php

namespace Lab3\Comment;

class Comment {
    private string $uuid;
    private string $postUuid;
    private string $authorUuid;
    private string $text;

    public function __construct(string $uuid, string $postUuid, string $authorUuid, string $text) {
        $this->uuid = $uuid;
        $this->postUuid = $postUuid;
        $this->authorUuid = $authorUuid;
        $this->text = $text;
    }

    public function getUuid(): string {
        return $this->uuid;
    }

    public function getPostUuid(): string {
        return $this->postUuid;
    }

    public function getAuthorUuid(): string {
        return $this->authorUuid;
    }

    public function getText(): string {
        return $this->text;
    }

    public function setUuid(string $uuid): void {
        $this->uuid = $uuid;
    }

    public function setPostUuid(string $postUuid): void {
        $this->postUuid = $postUuid;
    }

    public function setAuthorUuid(string $authorUuid): void {
        $this->authorUuid = $authorUuid;
    }

    public function setText(string $text): void {
        $this->text = $text;
    }
}