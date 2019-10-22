<?php
ini_set('max_execution_time', 60000000000);

require_once "functions.php";
require_once "db.php";
require_once "init_models.php";
require_once "init_repositories.php";

save_items("animekao");

function save_items($server) {
  //traigo lista a ser procesada
  $to_search = start($server);
  $r_item = new r_item();
  //proceso cada item de la lista y se inserta en base
  foreach($to_search as &$value) {
    //chekeo si existen detallas para le item en base a nombre y poster
    $match = search_tmdb($value);
    if($match != null) {
      $details = json_decode(file_get_contents('http://api.themoviedb.org/3/movie/'.$match->id.'?api_key=68c42a36bf0c5a8323863f8137ea4da2&language=es'));
      $to_save = new item();
      $to_save->__set_data($details->id, $details->imdb_id, $details->title, $details->original_title,
          $details->overview, $details->release_date, 'M', $details->poster_path, $details->backdrop_path, $details->runtime, null,
          date("Y-m-d h:i"));
      $to_insert_genres = array();
      foreach($details->genres as &$gvalue)
        array_push($to_insert_genres, new genre($gvalue->id, null, $gvalue->name));
      $to_save->setGenres($to_insert_genres);
      $to_save->setActors(get_credits($details->id)['actors']);
      $r_item->save($to_save, $value['id'], $server);
    }
    //si no contiene detalles lo inserto con los datos basicos que se obtienen de la web
    else {
      $to_save = new item();
      $to_save->setName($value['name']);
      $to_save->setPoster($value['poster']);
      $to_save->setInsert_date(date("Y-m-d h:i"));
      $to_save->setType('M');
      $r_item->save($to_save, $value['id'], $server);
    }
  }
}

//obtengo los creditos del item
function get_credits($id) {
  $credits = array('actors' => array());
  $data = json_decode(file_get_contents('http://api.themoviedb.org/3/movie/'.$id.'/credits?api_key=68c42a36bf0c5a8323863f8137ea4da2&language=es'));
  foreach($data->cast as &$value) {
    $actor = new actor();
    $actor->setTmdb_id($value->id);
    $actor->setName($value->name);
    $actor->setGender($value->gender);
    $actor->setPhoto($value->profile_path);
    array_push($credits['actors'], $actor);
  }
  return $credits;
}

//obtiene el listado de items
//implementar validacion de last_process
function start($server) {
  require_once "servers/".$server.".php";
  $r_item = new r_item();
  $last = $r_item->get_last_item_server($server);
  // ver como darle uso por el tema de que te trae
  $flag = true;
  $page = 1;
  $movies = array();
  while ($flag) {
    global $finished;
    $items = get_items('inicio', $page, $last);
    if(count($items) != 0)
      $movies = array_merge($movies, $items);
    else
      $flag = false;
    if(/*$page == 5 || */$finished)
     $flag = false;
    $page++;
  }
  return array_reverse($movies);
}

function search_tmdb($item) {
  $list = json_decode(file_get_contents('http://api.themoviedb.org/3/search/movie?api_key=68c42a36bf0c5a8323863f8137ea4da2&page=1&language=es&query='.urlencode($item['name'])))->results;
  $match = null;
  foreach($list as &$value) {
    $id_path = gets($value->poster_path,'/','.');
    if($id_path != '')
      if(strpos($item['poster'], $id_path) !== false) {
        $match = $value;
        break;
      }
  }
  if($match == null) {
    $list = json_decode(file_get_contents('http://api.themoviedb.org/3/search/movie?api_key=68c42a36bf0c5a8323863f8137ea4da2&page=1&language=es&query='.urlencode(str_replace('-',' ',$item['id']))))->results;
    foreach($list as &$value) {
      $id_path = gets($value->poster_path,'/','.');
      if($id_path != '')
        if(strpos($item['poster'], $id_path) !== false) {
          $match = $value;
          break;
        }
    }
  }
  return $match;
}

?>
