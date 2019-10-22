<?php

header('Content-Type: application/json');
$action = @htmlspecialchars($_GET["action"]);
$response = array();

require_once "data/logger.php";
require_once "data/functions.php";
require_once "data/db.php";
require_once "data/session.php";
require_once "data/base.php";

switch($action) {
  case 'login':
    login();
  break;
  case 'start':
    start();
  break;
  case 'home':
    home();
  break;
  case 'categories':
    categories();
  break;
  case 'items':
    items();
  break;
  case 'pelicula':
  case 'serie':
  case 'documental':
    info($action);
  break;
  case 'media':
    media();
  break;
  default:
    $response["status"] = 400;
    $response["message"] = "The (action) is invalid, please verify the data.";
    response();
  break;
}

?>
