<?php 

function changeUnits($unit="c", $type="speed", $value)
{
  switch ($type)
  {
    case "speed":
        return (($unit == "c") ? round($value*1.60934)."km/h" : $value."mp/h");
        break;

    case "temp":
        return (($unit == "c") ? $value."&#8451;" : $value."&#8457;");
        break;
  }
}

if (isset($_GET['place']))
{
  $json_data = array('state' => false, 'data' => null, 'alternatives' => array());

  include("../lib/simpleweather.class.php");
  
  $place = !empty($_GET['place']) ? $_GET['place'] : '';
  $woeid = !empty($_GET['woeid']) ? $_GET['woeid'] : 0;
  $unit = !empty($_GET['unit']) ? $_GET['unit'] : '';

  $weather = new SimpleWeather(array('place' => $place, 'woeid' => $woeid, 'degrees' => $unit));

  if (is_null($weather->getResult()))
  {
    die(json_encode($json_data));
  }

  $json_data['state'] = true;
    
  if (!empty($weather->getResult()->search_alternatives))
  {
    foreach (json_decode($weather->getResult()->search_alternatives) as $alternative)
    {
      array_push($json_data['alternatives'], array('place' => rawurlencode($alternative->place),
                                                   'woeid' => $alternative->woeid,
                                                   'region' => $alternative->region ? ', ' . $alternative->region : '',
                                                   'country' => $alternative->country ? ', ' . $alternative->country : ''));
    }
  }
  
  $json_data['data'] = array('temp' => changeUnits($unit, 'temp', $weather->getResult()->current_condition->temp),
                             'text' => $weather->getResult()->current_condition->text,
                             'humidity' => $weather->getResult()->current_condition->humidity,
                             'wind_speed' => changeUnits($unit, 'speed', $weather->getResult()->current_condition->wind),
                             'city' => $weather->getResult()->location->city,
                             'region' => isset($weather->getResult()->location->region) ? ', ' . $weather->getResult()->location->region : '',
                             'country' => isset($weather->getResult()->location->country) ? ', ' . $weather->getResult()->location->country : '');

  die(json_encode($json_data));
}

?>