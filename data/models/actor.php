<?php

class actor {

  private $id;
  private $tmdb_id;
  private $imdb_id;
  private $name;
  private $gender;
  private $birthday;
  private $place_of_birth;
  private $deathday;
  private $photo;
  private $biography;

  public function __set_data($id, $tmdb_id, $imdb_id, $name, $gender,
      $birthday, $place_of_birth, $deathday, $photo, $biography) {
    $this->id = $id;
    $this->tmdb_id = $tmdb_id;
    $this->imdb_id = $imdb_id;
    $this->name = $name;
    $this->gender = $gender;
    $this->birthday = $birthday;
    $this->place_of_birth = $place_of_birth;
    $this->deathday = $deathday;
    $this->photo = $photo;
    $this->biography = $biography;
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

  public function setGender($gender) {
    $this->gender = $gender;
  }

  public function getGender() {
    return $this->gender;
  }

  public function setBirthday($birthday) {
    $this->birthday = $birthday;
  }

  public function getBirthday() {
    return $this->birthday;
  }

  public function setPlace_of_birthe($place_of_birth) {
    $this->place_of_birth = $place_of_birth;
  }

  public function getPlace_of_birth() {
    return $this->place_of_birth;
  }

  public function setDeathday($deathday) {
    $this->deathday = $deathday;
  }

  public function getDeathday() {
    return $this->deathday;
  }

  public function setPhoto($photo) {
    $this->photo = $photo;
  }

  public function getPhoto() {
    return $this->photo;
  }

  public function setBiography($biography) {
    $this->biography = $biography;
  }

  public function getBiography() {
    return $this->biography;
  }

}

?>
