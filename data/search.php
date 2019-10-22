<?php

function search($s) {
  $c = file_get_contents("https://www.google.com/search?q=".str_replace(' ','+',$s));
  preg_match_all('/"r"><a href="(.*?)"/s', $c, $m);
  $r = $m[1];
  for ($i = 0; $i < count($r); $i++) {
    $r[$i] = substr($r[$i],7,(strpos($r[$i],'&am')-7));
    echo $r[$i].'<br />';
    if($i == 3)
      match_servers($r[$i]);
  }
}

function match_servers($u) {
  $c = @file_get_contents($u);
  //load regex on server database
  preg_match_all('/()/s', $c, $m);
  echo json_encode(array_values(array_unique($m[0])));
}

?>
