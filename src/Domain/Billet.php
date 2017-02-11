<?php

namespace writerblog\Domain;

class Billet {
    private $id;
    private $title;
    private $content;
    private $nbComments;

    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getTitle() {
        return $this->title;
    }
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    public function getContent() {
        return $this->content;
    }
    public function setContent($content) {
        $this->content = $content;
        return $this;
    }

    public function getNbComments() {
        return $this->nbComments;
    }
    public function setNbComments($nbComments) {
        $this->nbComments = $nbComments;
        return $this;
    }
}