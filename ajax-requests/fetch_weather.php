<?php 

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

	$weather = new SimpleWeather(array("place" => $_GET['place'], "woeid" => $_GET['woeid'], "degrees" => $_GET['unit']));

	if ($weather->getResult()->location->city)
	{

		if ($weather->getResult()->search_alternatives)
		{
			$alternatives = array();
			foreach (json_decode($weather->getResult()->search_alternatives) as $alternative)
			{
					array_push($alternatives, "<a class='alternative-link' href='#".rawurlencode($alternative->place)."/".$alternative->woeid."'>".$alternative->place.(($alternative->region) ? ", ".$alternative->region : "").(($alternative->country) ? ", ".$alternative->country : "")." &rarr;</a>");
			}
		}


		echo "<span class='big-text uppercase'>
		LOL SURE, can you handle <b class='colored'>".changeUnits($_GET['unit'], "temp", $weather->getResult()->current_condition->temp)."</b> 
		and <b class='colored'>".$weather->getResult()->current_condition->text."</b> weather? 
		Oh, <b class='colored'>".$weather->getResult()->current_condition->humidity."%</b> humidity and <b class='colored'>".changeUnits($_GET['unit'], "speed", $weather->getResult()->current_condition->wind)."</b> winds too.
		</span>";

		echo "<div id='notes-box'><span class='normal-text faded'>";
		echo "Displaying results for <b>".$weather->getResult()->location->city.(($weather->getResult()->location->region) ? ", ".$weather->getResult()->location->region : "").(($weather->getResult()->location->country) ? ", ".$weather->getResult()->location->country : "")."</b>.";

		if (is_array($alternatives))
		{
			echo "<br><br>Not the city you want? <a id='open-related-alternatives' href='javascript:;'>Click here</a> and choose a suggested search from the list. If you see duplicates or the city you clicked doesn't report any weather information, it's <b>Yahoo! YQL</b>'s fault.";
			echo "<br><div id='search-related-alternatives' style='display:none;padding:5px;'>".implode("<br>", $alternatives)."</div>";
		}
		else
		{
			echo "<br><br>No related alternatives fetched.";
		}

		echo "</span></div>";


	}
	else
	{
		echo "<span class='big-text uppercase'>No no, no weather!</span><br><span class='normal-text faded'>#note: Yahoo! sometimes doesn't show weather information even if the city shows up on the suggestions/alternatives list.</span>";
	}
}

?>