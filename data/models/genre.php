<?php

class genre {

  private $id;
  private $tmdb_id;
  private $imdb_id;
  private $name;

  public function __construct($tmdb_id, $imdb_id, $name) {
    $this->tmdb_id = $tmdb_id;
    $this->imdb_id = $imdb_id;
    $this->name = $name;
  }

  public function setId($id) {
    $this->id = $id;
  }

  public function getId() {
    return $this->id;
  }

  public function setTmdb_id($tmdb_id) {
    $this->tmdb_id = $tmdb_id;
  }

  public function getTmdb_id() {
    return $this->tmdb_id;
  }

  public function setImdb_id($imdb_id) {
    $this->imdb_id = $imdb_id;
  }

  public function getImdb_id() {
    return $this->imdb_id;
  }

  public function setName($name) {
    $this->name = $name;
  }

  public function getName() {
    return $this->name;
  }

}

?>
