<?php

function response() {
  global $response;
  echo json_encode($response);
  die();
}

function newServer($id, $name) {
  return array(
    "id" => $id,
    "name" => $name
  );
}

function get_response($url) {
  $curl = curl_init($url);
  $data = curl_exec($curl);
  curl_close($curl);
  return $data;
}

function gets($cade,$sepa1,$sepa2){
  if(strpos($cade,$sepa1,0)!==false){
    $po=strpos($cade,$sepa1,0);
    $c=substr($cade,$po+strlen($sepa1));
    if(strpos($c,$sepa2)!==false){
      $mpos=strpos($c,$sepa2);
      $d=substr($c,0,$mpos);
      return $d;
    }
  }
  return '';
}

?>
