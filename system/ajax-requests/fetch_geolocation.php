<?php 
  header("Content-Type: application/json");  

  /* fetch user geolocation through IP, using geoplugin.net */
  $geo = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$_SERVER['REMOTE_ADDR']));

  if ($geo->geoplugin_region)
  {
    /* convert to UTF-8 because the original encoding is a truly mess. this way, works as expected. */
    $geo->geoplugin_city = html_entity_decode(mb_convert_encoding($geo->geoplugin_city, 'UTF-8'), ENT_COMPAT, 'UTF-8');
    $geo->geoplugin_region = html_entity_decode(mb_convert_encoding($geo->geoplugin_region, 'UTF-8'), ENT_COMPAT, 'UTF-8');

    echo json_encode(array("result" => $geo->geoplugin_city ? $geo->geoplugin_city : $geo->geoplugin_region));
  }
  else
  {
    echo json_encode(array("result" => null));
  }
?>