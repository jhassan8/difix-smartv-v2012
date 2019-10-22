<?php

function isContinue() {
  global $response;
  if(isset($_SERVER['HTTP_TOKEN'])) {
    $token = @htmlspecialchars($_SERVER['HTTP_TOKEN']);
    $cn = new db();
    $cn->conect();
    $item = $cn->token($token);
    $cn->disconect();
    if($item['STATE'])
      return;
  }
  $response["status"] = 401;
  $response["message"] = "You are not authenticated, session does not exist or expires.";
  response();
  die();
}

function start() {
  global $response;
  logger('start');
  if(isset($_POST['mac']) && strlen($_POST['mac']) == 12 && isset($_POST['token'])) {
      $mac = @htmlspecialchars($_POST['mac']);
      $token = @htmlspecialchars($_POST['token']);
      logger('mac: '.$mac.', token: '.$token);
      $cn = new db();
      $cn->conect();
      $item = $cn->start($mac,$token);
      $cn->disconect();
      if($item['STATE']){
      $response['data'] = array(
          'user'=>strtolower($item['USERNAME']),
          'firs_name'=>ucfirst(strtolower($item['FIRST_NAME'])),
          'last_name'=>ucfirst(strtolower($item['LAST_NAME'])),
          'type'=>strtolower($item['TYPE']),
          'token'=>strtolower($item['TOKEN'])
        );
        $response["status"] = 200;
      }
      else{
        $response["status"] = 400;
        $response["message"] = "The data is invalid, please verify the data.";
      }
  }
  else{
    $response["status"] = 400;
    $response["message"] = "The data is invalid, please verify the data.";
  }
  response();
}

function login() {
  global $response;
  logger('login');
  if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['mac'])) {
    $username = @htmlspecialchars($_POST['username']);
    $password = @htmlspecialchars($_POST['password']);
    $mac = @htmlspecialchars($_POST['mac']);
    logger('params mac: '.$mac.', user: '.$username.', pass: '.$password);
    if(strlen($mac) == 12 && strlen($username) > 3 && strlen($password) > 3){
      $cn = new db();
      $cn->conect();
      $item = $cn->login($mac,$username,$password);
      $cn->disconect();
      if($item['STATE']){
        $response['data'] = array(
          'user'=>strtolower($item['USERNAME']),
          'firs_name'=>ucfirst(strtolower($item['FIRST_NAME'])),
          'last_name'=>ucfirst(strtolower($item['LAST_NAME'])),
          'type'=>strtolower($item['TYPE']),
          'token'=>strtolower($item['TOKEN'])
        );
        $response["status"] = 200;
      }
      else{
        $response["status"] = 400;
        $response["message"] = "The data is invalid, please verify the data.";
      }
    }
    else {
      $response["status"] = 400;
      $response["message"] = "The data is invalid, please verify the data.";
    }
  }
  else {
    $response["status"] = 400;
    $response["message"] = "The (username, password) is required.";
  }
  response();
}

?>
