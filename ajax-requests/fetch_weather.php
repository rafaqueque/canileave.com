<?php 

  if (isset($_GET['place']))
  {
  	include("../lib/simpleweather.class.php");

      $weather = new SimpleWeather(array("place" => $_GET['place'], "degrees" => "c"));

      if ($weather->getResult()->location->city)
      {
          
          echo "LOL SURE, can you handle <b class='colored'>".$weather->getResult()->current_condition->temp."ºC</b> 
                and <b class='colored'>".$weather->getResult()->current_condition->text."</b> weather? 
                Oh, <b class='colored'>".$weather->getResult()->current_condition->humidity."%</b> humidity and <b class='colored'>".round($weather->getResult()->current_condition->wind * 1.60934)."km/h</b> winds too.";
          echo "<br><span class='small-text'>interpreted as: ".$weather->getResult()->location->city.(($weather->getResult()->location->country) ? ", ".$weather->getResult()->location->country : "")."</span>";
      }
      else
      {
          echo "<br>City not found, stupid!";
      }
  }

?>