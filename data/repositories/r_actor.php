<?php

class r_actor {

  private $table = 'actors';

  public function save_list($list) {
    $ids = array();
    foreach ($list as &$actor)
      array_push($ids, $this->save($actor));
    return $ids;
  }

  public function save($actor) {
    $cn = new db();
    $cn->conect();
    $array = $this->parse($actor, $cn->getConnection());
    $query = 'INSERT INTO '.$this->table
        .' ('. implode(', ', array_keys($array)). ')'
        .' SELECT '. implode(', ', array_values($array));
    if(isset($array['tmdb_id']) && $array['tmdb_id'] != null && $array['tmdb_id'] != 0)
        $query .=' WHERE NOT EXISTS (SELECT * FROM '.$this->table
        .' WHERE tmdb_id = '.$array['tmdb_id'].')';
    $flag = $cn->insert_not_exist($query);
    if($flag == 0)
      $flag = $this->getIdByTmdbId($array['tmdb_id']);
    $cn->disconect();
    return $flag;
  }

  public function getIdByTmdbId($tmdb_id) {
    $cn = new db();
    $cn->conect();
    $query = "SELECT id FROM ".$this->table." WHERE tmdb_id = ".$tmdb_id;
    $result = $cn->execute($query);
    $cn->disconect();
    return $result['id'];
  }

  private function parse($actor, $cn) {
    $returned = array();
    if($actor->getTmdb_id() != null && $actor->getTmdb_id() != '')
      $returned['tmdb_id'] = $actor->getTmdb_id();
    if($actor->getImdb_id() != null && $actor->getImdb_id() != '')
      $returned['imdb_id'] = "'".$actor->getImdb_id()."'";
    if($actor->getName() != null && $actor->getName() != '')
      $returned['name'] = "'".mysqli_real_escape_string($cn, $actor->getName())."'";
    if($actor->getGender() != null && $actor->getGender() != '')
      $returned['gender'] = "'".$actor->getGender()."'";
    if($actor->getBirthday() != null && $actor->getBirthday() != '')
      $returned['birthday'] = $actor->getBirthday();
    if($actor->getPlace_of_birth() != null && $actor->getPlace_of_birth() != '')
      $returned['place_of_birth'] = "'".mysqli_real_escape_string($cn, $actor->getPlace_of_birth())."'";
    if($actor->getDeathday() != null && $actor->getDeathday() != '')
      $returned['deathday'] = $actor->getDeathday();
    if($actor->getPhoto() != null && $actor->getPhoto() != '')
      $returned['photo'] = "'".$actor->getPhoto()."'";
    if($actor->getBiography() != null && $actor->getBiography() != '')
      $returned['biography'] = "'".mysqli_real_escape_string($cn, $actor->getBiography())."'";
    return $returned;
  }

}

?>
