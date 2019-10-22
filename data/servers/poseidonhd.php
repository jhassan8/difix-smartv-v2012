<?php

$finished = false;

function get_categories() {
  $source = gets(@file_get_contents('http://poseidonhd.com'),'<ul class="genres scrolling">','</ul>');
  $count = substr_count($source, '<li class="');
  $generos = array();
  array_push($generos, array('id' => 'inicio', 'name' => 'Inicio'));
  array_push($generos, array('id' => 'series', 'name' => 'Series'));
  for($i=0; $i<$count; $i++){
    $item = gets($source,'<li class="','</li>');
    $id = gets($item,'/genre/','/');
    $name = html_entity_decode(trim(gets($item,'/" >','<')));
    array_push($generos, array('id' => $id, 'name' => $name));
    $source = substr($source, strpos($source,'<li class="')+strlen($item.'</li>'));
  }
  return $generos;
}

function get_items($category, $page, $last) {
  $main = 'https://poseidonhd.com/';
  $movies = array();
  if($category == 'inicio')
    $main .= 'movies';
  else if($category == 'series')
    $main .= 'tvshows';
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
      $id = gets($item,'/movies/','/');
      $type = 'Movie';
    }
    $name = gets($item,'alt="','"');
    $img = gets($item,'src="','"');
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
