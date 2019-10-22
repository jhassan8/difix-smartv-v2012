<?php

$finished = false;

function get_items($category, $page, $last) {
  $main = 'https://series.animekao.com/';
  $movies = array();
  if($category == 'inicio')
    $main .= 'peliculas';
  else if($category == 'series')
    $main .= 'serie';
  else
    $main .= 'genre/'.$category;
  $source = gets(@file_get_contents($main.'/page/'.$page),'<div id="archive-content"','<div class="pagination">');
  $cant = substr_count($source,'<article ');
  for($i=0;$i<$cant;$i++){
    $item  = gets($source,'<article ','</article>');
    if($category == 'series'){
      $id = gets($item,'/tvshows/','/');
      $type = 'Serie';
    }
    else{
      $id = gets($item,'/peliculas/','/');
      $type = 'Movie';
    }
    $name = gets($item,'alt="','"');
    $img = gets($item,'data-src="','"');
    if($id == $last) {
      global $finished;
      $finished = true;
      break;
    }
    array_push($movies, array('id' => $id, 'name' => $name, 'poster' => $img));
    $source = substr($source, strpos($source,'<article ')+strlen($item.'</article>'));
  }
  return $movies;
}

?>
