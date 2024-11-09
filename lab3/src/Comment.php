<?php

namespace Lab3\Comment;

class Comment 
{
    public $id;
    public $authorId;
    public $articleId;
    public $text;

    public function __construct($id, $authorId, $articleId, $text) {
        $this->id = $id;
        $this->authorId = $authorId;
        $this->articleId = $articleId;
        $this->text = $text;
    }
}
