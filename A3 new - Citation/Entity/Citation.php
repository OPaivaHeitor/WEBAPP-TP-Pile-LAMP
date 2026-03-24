<?php
class Citation {
    private $text;
    private $author;
    private $date;

    public function __construct($text, $author, $date) {
        if (strlen($text) > 1024) {
            throw new Exception("Citation text cannot exceed 1024 characters.");
        }
        $this->text = $text;
        $this->author = $author;
        $this->date = $date;
    }

    public function getCitation() {
        return '"' . $this->text . '" - ' . $this->author->getFullName() . ', ' . $this->date->format('Y-m-d');
    }

    public function getAuthor() {
        return $this->author;
    }
}
?>