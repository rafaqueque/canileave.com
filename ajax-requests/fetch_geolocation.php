<?php 
    
    include("../lib/simpleweather.class.php");

    $geo = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$_SERVER['REMOTE_ADDR']));

    if ($geo->geoplugin_region)
    {
        echo $geo->geoplugin_city ? $geo->geoplugin_city : $geo->geoplugin_region;
    }
    else
    {
        echo null;
    }
        
?>