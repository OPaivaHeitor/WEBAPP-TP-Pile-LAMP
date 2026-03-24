<?php
class Author {
    private $first_name;
    private $last_name;
    private $birth_year;
    private $citations = []; // Store citations for the author

    public function __construct($first_name, $last_name, $birth_year) {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->birth_year = $birth_year;
    }

    public function getFullName() {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getBirthYear() {
        return $this->birth_year;
    }

    // Add citation to the author
    public function addCitation($citation) {
        $this->citations[] = $citation;
    }

    // Retrieve all citations for the author
    public function getCitations() {
        return $this->citations;
    }
}
?>