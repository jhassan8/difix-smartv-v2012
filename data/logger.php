<?php

function logger($message) {
  if (!empty($_SERVER['HTTP_CLIENT_IP']))
    $ip = $_SERVER['HTTP_CLIENT_IP'];
  elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
  else
    $ip = $_SERVER['REMOTE_ADDR'];
  $date = date('[j-m-y h:i:s]');
  $log = trim(file_get_contents('log'));
  file_put_contents('log', $log
    .PHP_EOL
    .$date.'['.$ip.'] : '
    .$message
  );
}

?>
