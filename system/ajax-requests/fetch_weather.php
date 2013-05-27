<?php 
  header("Content-Type: application/json");

  /* change units between metric and imperial/us */
  function changeUnits($unit="c", $type="speed", $value)
  {
    switch ($type)
    {
      case "speed":
          return (($unit == "c") ? round($value*1.60934)."km/h" : $value."mp/h");
          break;

      case "temp":
          return (($unit == "c") ? $value."ºC" : $value."ºF");
          break;
    }
  }


  if (isset($_GET['place']))
  {
    include("../lib/simpleweather.class.php");

    $place = isset($_GET['place']) ? $_GET['place'] : null;
    $woeid = isset($_GET['woeid']) ? $_GET['woeid'] : null;
    $degrees = isset($_GET['unit']) ? $_GET['unit'] : "c";

    $data = array("has_weather" => false, 
                  "has_alternatives" => false,
                  "has_strike_information" => false);
    
    /* get the weather information with pre-filled vars */
    $weather = new SimpleWeather(array("place" => $place, "woeid" => $woeid, "degrees" => $degrees));

    if ($weather->getResult()->location->city)
    {
      
      /* if the search has some alternative cities, store the alternatives to an array */
      if ($weather->getResult()->search_alternatives)
      {
        $alternatives = array();
        foreach (json_decode($weather->getResult()->search_alternatives) as $alternative)
        {
            array_push($alternatives, array("url" => "#".rawurlencode($alternative->place)."/".$alternative->woeid,
                                            "place" => $alternative->place.(($alternative->region) ? ", ".$alternative->region : "").(($alternative->country) ? ", ".$alternative->country : "")));
        }
      }

      /* fill array with useful information to be parsed with mustache.js */
      $data['has_weather'] = true;
      $data['temp'] = changeUnits($_GET['unit'], "temp", $weather->getResult()->current_condition->temp);
      $data['weather'] = $weather->getResult()->current_condition->text;
      $data['humidity'] = $weather->getResult()->current_condition->humidity;
      $data['location'] = $weather->getResult()->location->city.(($weather->getResult()->location->region) ? ", ".$weather->getResult()->location->region : "").(($weather->getResult()->location->country) ? ", ".$weather->getResult()->location->country : "");
      $data['wind'] = changeUnits($_GET['unit'], "speed", $weather->getResult()->current_condition->wind);

      /* only show alternatives if it exists */
      if (is_array($alternatives) && count($alternatives) > 0)
      {
        $data['has_alternatives'] = true;
        $data['alternatives'] = $alternatives;
      }  


      /* check if there is any strike going on in Portugal. useful for us, portuguese people, trust me. */
      $strikes = json_decode(file_get_contents("http://hagreve.pt/api/v1/strikes"));
      
      if ($strikes && $weather->getResult()->location->country === "Portugal")
      {
        $strikes_going = array();
        foreach ($strikes as $strike)
        {
          if ((time() >= strtotime($strike->start_date) && time() <= strtotime($strike->end_date)) && !$strike->canceled)
          {
            array_push($strikes_going, array("company" => $strike->company->name,
                                              "description" => $strike->description,
                                              "from" => $strike->start_date,
                                              "to" => $strike->end_date,
                                              "source" => $strike->source_link));
          }
        }

        if (is_array($strikes_going) && count($strikes_going) > 0)
        {
          $data['has_strike_information'] = true;
          $data['strikes'] = $strikes_going;
        }
      }

      /* random messages */
      $random_messages = array("GO AHEAD CHAMP",
                                "LOL SURE",
                                "HELL NO",
                                "TOUGH GUY UH",
                                "CALM DOWN");
      shuffle($random_messages);
      $data['random_msg'] = reset($random_messages);

    }

    /* return data json-encoded */
    echo json_encode($data);
  }
?>