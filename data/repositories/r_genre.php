<?php

class r_genre {

  private $table = 'genres';

  public function save_list($list) {
    $ids = array();
    foreach ($list as &$genre)
      array_push($ids, $this->save($genre));
    return $ids;
  }

  public function save($genre) {
    $cn = new db();
    $cn->conect();
    $array = $this->parse($genre, $cn->getConnection());
    $query = 'INSERT INTO '.$this->table
        .' ('. implode(', ', array_keys($array)). ')'
        .' SELECT '. implode(', ', array_values($array));
    if(isset($array['tmdb_id']) && $array['tmdb_id'] != null && $array['tmdb_id'] != 0)
        $query .= ' WHERE NOT EXISTS (SELECT * FROM '.$this->table
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

  private function parse($genre, $cn) {
    $returned = array();
    if($genre->getTmdb_id() != null && $genre->getTmdb_id() != '')
      $returned['tmdb_id'] = $genre->getTmdb_id();
    if($genre->getImdb_id() != null && $genre->getImdb_id() != '')
      $returned['imdb_id'] = "'".$genre->getImdb_id()."'";
    if($genre->getName() != null && $genre->getName() != '')
      $returned['name'] = "'".mysqli_real_escape_string($cn, $genre->getName())."'";
     return $returned;
  }

}

?>
