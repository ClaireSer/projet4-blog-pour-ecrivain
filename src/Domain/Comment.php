<?php

namespace writerblog\Domain;

use writerblog\Domain\Billet;
use writerblog\Domain\User;

class Comment {
    private $id;
    private $content;
    private $author;
    private $billet;

    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
        return $this;
    }
    
    public function getContent() {
        return $this->content;
    }
    public function setContent($content) {
        $this->content = $content;
        return $this;
    }

    public function getAuthor() {
        return $this->author;
    }
    public function setAuthor(User $author) {
        $this->author = $author;
        return $this;
    }

    public function getBillet() {
        return $this->billet;
    }
    public function setBillet(Billet $billet) {
        $this->billet = $billet;
        return $this;
    }
}