<?php

class r_item {

  private $table = 'items';
  private $actors_r = 'item_actors';
  private $genres_r = 'item_genres';
  private $hosting_r = 'item_hostings';

  public function save_list($list) {
    foreach ($list as &$item)
      $this->save($item, null, null);
  }

  public function get_last_item_server($server) {
    $cn = new db();
    $cn->conect();
    $query = "SELECT id FROM ".$this->hosting_r." WHERE insert_date in (SELECT MAX(insert_date) from ".$this->hosting_r." WHERE id_hosting = '".$server."') AND id_hosting ='".$server."'";
    $result = $cn->execute($query);
    $cn->disconect();
    return $result['id'];
  }

  public function save($item, $id, $server) {
    $cn = new db();
    $cn->conect();
    $array = $this->parse($item, $cn->getConnection());
    $genres = array();
    $actors = array();
    $flag = true;
    if(count($item->getActors()) > 0) {
      echo "<br/>Actors:<br/>";
      $r_actor = new r_actor();
      $flag = $r_actor->save_list($item->getActors());
      if(count($flag) > 0)
        $actors = array_merge($actors, $flag);
    }
    if(count($item->getGenres()) > 0 && $flag) {
      echo "<br/>Genres:<br/>";
      $r_genre = new r_genre();
      $flag = $r_genre->save_list($item->getGenres());
      if($flag > 0)
        $genres = array_merge($genres, $flag);
    }
    if($flag) {
      $query = 'INSERT INTO '.$this->table
          .' ('. implode(', ', array_keys($array)). ')'
          .' SELECT '. implode(', ', array_values($array));
      if(isset($array['tmdb_id']) && $array['tmdb_id'] != null && $array['tmdb_id'] != 0)
          $query .= ' WHERE NOT EXISTS (SELECT * FROM '.$this->table
          .' WHERE tmdb_id = '.$array['tmdb_id'].')';
      else
          $query .= ' WHERE NOT EXISTS (SELECT * FROM '.$this->table
          .' WHERE poster = '.$array['poster'].')';
      echo "<br/>Item:<br/>";
      $flag = $cn->insert_not_exist($query);
      $this->insert_hosting_data($id, $server, $flag, @$array['tmdb_id'], @$array['poster']);
      if($flag > 0) {
        $this->insert_relations($actors, $genres, $flag);
      }
      return $flag;
    }
    $cn->disconect();
    return $flag;
  }

  private function insert_relations($actors, $genres, $item){
    echo "<br/>Relactions:<br/>";
    $cn = new db();
    $cn->conect();
    if(count($actors) > 0) {
      for ($i=0; $i < count($actors); $i++) {
        $cn->insert_not_exist("INSERT INTO ".$this->actors_r."(id_item, id_actor) VALUES (".$item.",".$actors[$i].")");
      }
    }
    if(count($genres) > 0) {
      for ($i=0; $i < count($genres); $i++) {
        $cn->insert_not_exist("INSERT INTO ".$this->genres_r."(id_item, id_genre) VALUES (".$item.",".$genres[$i].")");
      }
    }
    $cn->disconect();
  }

  private function insert_hosting_data($id, $server, $flag, $tmdb_id, $poster) {
    echo "<br/>Hosting:<br/>";
    if($flag == 0)
      $flag = $this->getIdByTmdbId($tmdb_id, $poster);
    $date = "STR_TO_DATE('".date("Y-m-d h:i:s")."', '%Y-%m-%d %h:%i:%s')";
    $cn = new db();
    $cn->conect();
    $cn->insert_not_exist("INSERT INTO ".$this->hosting_r."(id, id_item, id_hosting, insert_date) VALUES ('".$id."',".$flag.",'".$server."',".$date.")");
    $cn->disconect();
  }

  private function getIdByTmdbId($tmdb_id, $poster) {
    $cn = new db();
    $cn->conect();
    if($tmdb_id)
      $query = "SELECT id FROM ".$this->table." WHERE tmdb_id = ".$tmdb_id;
    else
      $query = "SELECT id FROM ".$this->table." WHERE poster = ".$poster;
    $result = $cn->execute($query);
    $cn->disconect();
    return $result['id'];
  }


  private function parse($item, $cn) {
    $returned = array();
    if($item->getTmdb_id() != null && $item->getTmdb_id() != '')
      $returned['tmdb_id'] = $item->getTmdb_id();
    if($item->getImdb_id() != null && $item->getImdb_id() != '')
      $returned['imdb_id'] = "'".$item->getImdb_id()."'";
    if($item->getName() != null && $item->getName() != '')
      $returned['name'] = "'".mysqli_real_escape_string($cn, $item->getName())."'";
    if($item->getOriginal_name() != null && $item->getOriginal_name() != '')
      $returned['original_name'] = "'".mysqli_real_escape_string($cn, $item->getOriginal_name())."'";
    if($item->getDescription() != null && $item->getDescription() != '')
      $returned['description'] = "'".mysqli_real_escape_string($cn, $item->getDescription())."'";
    if($item->getDate() != null && $item->getDate() != '')
      $returned['date'] = "STR_TO_DATE('".$item->getDate()."', '%Y-%m-%d')";
    if($item->getType() != null && $item->getType() != '')
      $returned['type'] = "'".$item->getType()."'";
    if($item->getPoster() != null && $item->getPoster() != '')
      $returned['poster'] = "'".$item->getPoster()."'";
    if($item->getBackground() != null && $item->getBackground() != '')
      $returned['background'] = "'".$item->getBackground()."'";
    if($item->getDuration() != null && $item->getDuration() != '')
      $returned['duration'] = $item->getDuration();
    if($item->getTrailer() != null && $item->getTrailer() != '')
      $returned['trailer'] = "'".$item->getTrailer()."'";
    if($item->getInsert_date() != null && $item->getInsert_date() != '')
      $returned['insert_date'] = "STR_TO_DATE('".$item->getInsert_date()."', '%Y-%m-%d %h:%i')";
    return $returned;
  }

}

?>
