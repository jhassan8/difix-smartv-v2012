<?php

function get_categories() {
  $generos = array();
  $source = file_get_contents('https://www.pelisplus.tv/peliculas/');
  $cant = substr_count($source,'<h2 class="Heading--carousel">');
  array_push($generos, array('id' => 'inicio', 'name' => 'Inicio'));
  array_push($generos, array('id' => 'series', 'name' => 'Series'));
  for($i=0;$i<$cant;$i++){
    $item = gets($source,'<h2 class="Heading--carousel">','<svg class="icon icon--all">');
    $id = gets($item,'/peliculas/','/');
    $name = trim(gets($item,'PELÍCULAS DE  ','<a'));
    if($id != "ultimas-peliculas")
      array_push($generos, array('id' => $id, 'name' => $name));
    $source = substr($source, strpos($source,'<h2 class="Heading--carousel">')+strlen($item.'<svg class="icon icon--all">'));
  }
  return $generos;
}

function get_items($category, $page) {
  $main = 'https://www.pelisplus.tv/';
  $movies = array();
  if($category == 'inicio')
    $main .= 'peliculas/ultimas-peliculas';
  else if($category == 'series')
    $main .= $category.'/ultimas-series';
  else
    $main .= 'peliculas/'.$category;
  $source = gets(@file_get_contents($main.'/?page='.$page),'<div class="Posters">','</section>');
  $cant = substr_count($source,'<a ');
  for($i=0;$i<$cant;$i++){
    $item  = gets($source,'<a ','</a>');
    if($category == 'series'){
      $id = gets($item,'/serie/','/');
      $type = 'Serie';
    }
    else{
      $id = gets($item,'/pelicula/','/');
      $type = 'Movie';
    }
    $name = gets($item,'data-title="','"');
    $img = gets($item,'src="','"');
    array_push($movies, array('id' => $id, 'name' => $name, 'poster' => $img, 'type' => $type, 'server' => 'pplus'));
    $source = substr($source, strpos($source,'<a ')+strlen($item.'</a>'));
  }
  return $movies;
}

function get_info($type, $id) {
  if($type == 'serie')
    $source = @file_get_contents('https://www.pelisplus.tv/serie/'.$id.'/');
  else
    $source = @file_get_contents('https://www.pelisplus.tv/pelicula/'.$id.'/');
  $poster = trim(gets($source,'<div class="detail-image"> <img srcset-polyfill="',' '));
  $name = trim(gets($source,'<header class="detail-header"><h1 class="detail-title">','<'));
  $name_en = $name;
  $year = trim(gets($source,'/year/','/'));
  $description = trim(gets($source,'<p class="detail-text">','</p>'));
  $background = trim(gets($source,'<img srcset-polyfill="',' '));
  if($name == '')
    return null;
  if($type == 'serie'){
    $seasons = array();
    $source_season = gets($source,'<div class="owl-carousel"','</div>');
    $cant = substr_count($source_season,'<li ');
    $id_item = gets($source_season,'data-serieid="','"');
    for($i=0;$i<$cant;$i++){
      $item = gets($source_season,'<li ','</li>');
      $season_number = gets($item,'data-id="','"');
      $season_name = "Temporada ".$season_number;
      if($i == 0) {
        $episodes = array();
        $source2 = gets($source,'<div id="episodes-list"','<div id="episode-detail"');
        $cant2 = substr_count($source2,'<article ');
        for($j=0;$j<$cant2;$j++){
          $item2 = gets($source2,'<article ','</article>');
          $episode_name = trim(gets($item2,'data-title="','"'));
          $episode_number = trim(gets($item2,'data-episode-number="','"'));
          $source2 = substr($source2, strpos($source2,'<article ')+strlen($item2.'</article>'));
          array_push($episodes, array('id' => $j+1, 'name' => $episode_name, 'number' => $episode_number));
        }
      }
      else {
        $episodes = array();
        $jsonitems =  json_decode(file_get_contents("https://www.pelisplus.tv/api/episodes?titleId=".$id_item."&seasonNumber=".$season_number));
        for($j=0;$j<count($jsonitems->titles);$j++) {
          array_push($episodes, array('id' => $j+1, 'name' => $jsonitems->titles[$j]->title, 'number' => $jsonitems->titles[$j]->tvSeasonEpisodeNumber));
        }
      }
      array_push($seasons, array('id' => $i+1, 'name' => $season_name, 'number' => $season_number, 'episodes' => $episodes));
      $source_season = substr($source_season, strpos($source_season,'<li ')+strlen($item.'</li>'));
    }
    $view = /*get_view_serie($id,$user,'pplus')*/null;
    return array('id' => $id, 'type'=>$type, 'poster' => $poster, 'name' => $name, 'name_en' => $name_en, 'rated' => 'N/A', 'year' => $year, 'background' => $background, 'description' => $description, 'seasons' => array_reverse($seasons), 'lang' => array(array('id'=>1,'name'=>'Latino')), 'view' => $view, 'server' => 'pplus');
  }
  else{
    $duration = trim(gets($source,'data-item--duration">','<'));
    $trailer = trim(gets($source,'src="https://www.youtube.com/embed/','"'));
    return array('id' => $id, 'poster' => $poster, 'name' => $name, 'name_en' => $name_en, 'rated' => 'N/A', 'background' => $background, 'duration' => $duration, 'year' => $year, 'description' => $description, 'trailer' => $trailer, 'lang' => array(array('id' => 1, 'name' => 'Latino')), 'type'=>$type, 'server' => 'pplus');
  }
}

function get_media($id, $season, $episode) {
  $data = array();
  if($season && $episode){
    $main = "https://www.pelisplus.tv/serie/".$id."/temporada/".$season."/capitulo/".$episode."/";
  }
  else{
    $main = "https://www.pelisplus.tv/pelicula/".$id."/";
    $media = array('links'=>null,'subtitle'=>null);
  }

  $c = curl_init($main);
  curl_setopt($c, CURLOPT_HEADER, 1);
  curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
  $page = curl_exec($c);
  curl_close($c);

  if($season && $episode) {
    $image = trim(str_replace('/original/','/w185/',gets($page,'<img srcset-polyfill="',' ')));
    $synopsis = trim(gets($page,'"og:description" content="','"'));
    $data["info"] = array('image'=>$image,'description'=>$synopsis);
  }

  $links = array();
  $rapidvideo = gets($page,'rapidvideo.com/embed/','"');
  $vidoza = gets($page,'vidoza.net/embed-','.html');
  $streamango = gets($page,'streamango.com/embed/','/');
  $vidlox = gets($page,'https://vidlox.me/embed-','.html');
  $openload = gets($page,'https://openload.co/embed/','/');

  if($rapidvideo)
    array_push($links, array("id" => "rapidvideo", "link" => $rapidvideo));
  if($vidoza)
    array_push($links, array("id" => "vidoza", "link" => $vidoza));
  if($streamango)
    array_push($links, array("id" => "streamango", "link" => $streamango));
  if($vidlox)
    array_push($links, array("id" => "vidlox", "link" => $vidlox));
  if($openload)
    array_push($links, array("id" => "openload", "link" => $openload));
  $data["links"] = $links;

  return $data;
}

?>
