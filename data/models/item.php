<?php

class item {

  private $id;
  private $tmdb_id;
  private $imdb_id;
  private $name;
  private $original_name;
  private $description;
  private $date;
  private $type;
  private $poster;
  private $background;
  private $duration;
  private $trailer;
  private $insert_date;
  private $actors = array();
  private $genres = array();

  public function __set_data($tmdb_id, $imdb_id, $name, $original_name,
      $description, $date, $type, $poster, $background, $duration, $trailer,
      $insert_date) {
    $this->tmdb_id = $tmdb_id;
    $this->imdb_id = $imdb_id;
    $this->name = $name;
    $this->original_name = $original_name;
    $this->description = $description;
    $this->date = $date;
    $this->type = $type;
    $this->poster = $poster;
    $this->background = $background;
    $this->duration = $duration;
    $this->trailer = $trailer;
    $this->insert_date = $insert_date;
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

  public function setOriginal_name($original_name) {
    $this->original_name = $original_name;
  }

  public function getOriginal_name() {
    return $this->original_name;
  }

  public function setDescription($description) {
    $this->description = $description;
  }

  public function getDescription() {
    return $this->description;
  }

  public function setDate($date) {
    $this->date = $date;
  }

  public function getDate() {
    return $this->date;
  }

  public function setType($type) {
    $this->type = $type;
  }

  public function getType() {
    return $this->type;
  }

  public function setPoster($poster) {
    $this->poster = $poster;
  }

  public function getPoster() {
    return $this->poster;
  }

  public function setBackground($background) {
    $this->background = $background;
  }

  public function getBackground() {
    return $this->background;
  }

  public function setDuration($duration) {
    $this->duration = $duration;
  }

  public function getDuration() {
    return $this->duration;
  }

  public function setTrailer($trailer) {
    $this->trailer = $trailer;
  }

  public function getTrailer() {
    return $this->trailer;
  }

  public function setInsert_date($insert_date) {
    $this->insert_date = $insert_date;
  }

  public function getInsert_date() {
    return $this->insert_date;
  }

  public function setActors($actors) {
    $this->actors = $actors;
  }

  public function getActors() {
    return $this->actors;
  }

  public function setGenres($genres) {
    $this->genres = $genres;
  }

  public function getGenres() {
    return $this->genres;
  }

}

?>
