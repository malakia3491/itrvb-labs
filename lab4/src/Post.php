<?php

namespace Lab3\Post;

class Post 
{
    public string $uuid;
    public string $authorUuid;
    public string $title;
    public string $text;

    public function __construct(string $uuid, string $authorUuid, string $title, string $text) {
        $this->uuid = $uuid;
        $this->authorUuid = $authorUuid;
        $this->title = $title;
        $this->text = $text;
    }
}
