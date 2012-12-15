<?php 
    
include("../lib/simpleweather.class.php");

$geo = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$_SERVER['REMOTE_ADDR']));

if ($geo->geoplugin_region)
{
  $geo->geoplugin_city = html_entity_decode(mb_convert_encoding($geo->geoplugin_city, 'UTF-8'), ENT_COMPAT, 'UTF-8');
  $geo->geoplugin_region = html_entity_decode(mb_convert_encoding($geo->geoplugin_region, 'UTF-8'), ENT_COMPAT, 'UTF-8');

  echo $geo->geoplugin_city ? $geo->geoplugin_city : $geo->geoplugin_region;
}
else
{
  echo null;
}
        
?>