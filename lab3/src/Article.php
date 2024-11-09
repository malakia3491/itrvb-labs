<?php

namespace Lab3\Article;

class Article 
{
    public $id;
    public $authorId;
    public $title;
    public $text;

    public function __construct($id, $authorId, $title, $text) {
        $this->id = $id;
        $this->authorId = $authorId;
        $this->title = $title;
        $this->text = $text;
    }
}
