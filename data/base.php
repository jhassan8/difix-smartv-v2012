<?php

function servers() {
  return array(
      newServer("pplus", "pelisplus"),
      newServer("phd", "poseidonHD")
  );
}

function categories() {
  global $response;
  if(isset($_GET['server'])) {
    isContinue();
    $id = @htmlspecialchars($_GET['server']);
    $response["data"] = base_categories($id);
    $response["status"] = 200;
  }
  else {
    $response["status"] = 400;
    $response["message"] = "The server is required, please verify the data.";
  }
  response();
}

function base_categories($id) {
  global $response;
  switch ($id) {
    case 'pplus':
      require_once "servers/pelisplus.php";
    break;
    case 'phd':
      require_once "servers/poseidonHD.php";
    break;
    default:
      $response["status"] = 400;
      $response["message"] = "The server is invalid, please verify the data.";
      response();
    break;
  }
  return get_categories();
}

function items() {
  global $response;
  if(isset($_GET['server']) && isset($_GET['category']) && isset($_GET['page'])) {
    isContinue();
    $id = @htmlspecialchars($_GET['server']);
    $category = @htmlspecialchars($_GET['category']);
    $page = @htmlspecialchars($_GET['page']);
    $response["data"] = base_items($id, $category, $page);
    $response["status"] = 200;
  }
  else {
    $response["status"] = 400;
    $response["message"] = "The (server, category, page) is required, please verify the data.";
  }
  response();
}

function base_items($id, $category, $page) {
  switch ($id) {
    case 'pplus':
      require_once "servers/pelisplus.php";
    break;
    case 'phd':
      require_once "servers/poseidonHD.php";
    break;
    default:
      $response["status"] = 400;
      $response["message"] = "The server is invalid, please verify the data.";
      response();
    break;
  }
  return get_items($category, $page);
}

function info($type) {
  global $response;
  if(isset($_GET['server']) && isset($_GET['id'])) {
    isContinue();
    $id = @htmlspecialchars($_GET['server']);
    $item = @htmlspecialchars($_GET['id']);
    $response["data"] = base_info($id, $item, $type);
    $response["status"] = 200;
  }
  else {
    $response["status"] = 400;
    $response["message"] = "The (server, id) is required, please verify the data.";
  }
  response();
}

function base_info($id, $item, $type) {
  switch ($id) {
    case 'pplus':
      require_once "servers/pelisplus.php";
    break;
    case 'phd':
      require_once "servers/poseidonHD.php";
    break;
    default:
      $response["status"] = 400;
      $response["message"] = "The server is invalid, please verify the data.";
      response();
    break;
  }
  return get_info($type, $item);
}

function media() {
  global $response;
  if(isset($_GET['server']) && isset($_GET['id'])) {
    isContinue();
    $id = @htmlspecialchars($_GET['server']);
    $item = @htmlspecialchars($_GET['id']);
    $season = @htmlspecialchars($_GET['season']);
    $episode = @htmlspecialchars($_GET['episode']);
    $response["data"] = base_media($id, $item, $season, $episode);
    $response["status"] = 200;
  }
  else {
    $response["status"] = 400;
    $response["message"] = "The (server, id) is required, please verify the data.";
  }
  response();
}

function base_media($id, $item, $season, $episode) {
  switch ($id) {
    case 'pplus':
      require_once "servers/pelisplus.php";
    break;
    case 'phd':
      require_once "servers/poseidonHD.php";
    break;
    default:
      $response["status"] = 400;
      $response["message"] = "The server is invalid, please verify the data.";
      response();
    break;
  }
  return get_media($item, $season, $episode);
}

function home() {
  logger('home');
  global $response;
  isContinue();
  $servers = servers();
  $categories = base_categories($servers[1]["id"]);
  $new = json_decode('{"id":299536,"name":"Vengadores: Infinity War","original_name":"Avengers: Infinity War","background":"https:\/\/image.tmdb.org\/t\/p\/original\/bOGkgRGdhrBYJSLpXaxhXVstddV.jpg","image":"https:\/\/image.tmdb.org\/t\/p\/w154\/rDLmMzkaFMFWF1UnnlboMfifVXM.jpg","description":"El todopoderoso Thanos ha despertado con la promesa de arrasar con todo a su paso, portando el Guantelete del Infinito, que le confiere un poder incalculable."}');
  $items = json_decode(file_get_contents('data/mocks/home.json'))/*base_items($servers[0]["id"], $categories[0]["id"], 1)*/;
  $response["status"] = 200;
  $response["data"] = array(
    "new" => $new,
    "servers" => $servers,
    "categories" => $categories,
    "items" => $items
  );
  response();
}

?>
