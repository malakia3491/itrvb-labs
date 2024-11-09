<?php

namespace Lab3\Comment;

class Comment 
{
    public string $uuid;
    public string $postUuid;
    public string $authorUuid;
    public string $text;

    public function __construct(string $uuid, string $postUuid, string $authorUuid, string $text) {
        $this->uuid = $uuid;
        $this->postUuid = $postUuid;
        $this->authorUuid = $authorUuid;
        $this->text = $text;
    }
}
