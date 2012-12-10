<?php 

	/*
		Name: SimpleWeather
		Description: Just a simple wrapper to get weather information from Yahoo YQL service.
		Author: rafaqueque
		Version: 1.1
	*/
	
	class SimpleWeather
	{
		protected $result;


		function get_geoplaces_yql($place="Lisbon, PT")
		{
			return "http://query.yahooapis.com/v1/public/yql?q=".urlencode(sprintf("select * from geo.places where text = '%s'", $place))."&format=json";
		}

		function get_weather_yql($woeid, $degrees="c")
		{
			return "http://query.yahooapis.com/v1/public/yql?q=".urlencode(sprintf("select * from weather.forecast where woeid=%d and u='%s'", $woeid, $degrees))."&format=json";
		}


		function api_call($yql)
		{
			return json_decode(file_get_contents($yql));
		}



		function __construct($options=array())
		{

			$object = $this->api_call($this->get_geoplaces_yql($options['place']));

			if ($object->query->count > 0)
			{

				if (is_array($object->query->results->place))
				{
					$woeid = $object->query->results->place[0]->woeid;
				}
				else
				{
					$woeid = $object->query->results->place->woeid;
				}

				
				if ($woeid > 0)
				{
					$object = $this->api_call($this->get_weather_yql($woeid, $options['degrees']));

					$data['location'] = $object->query->results->channel->location;
					$data['current_condition'] = $object->query->results->channel->item->condition;
					$data['current_condition']->wind = $object->query->results->channel->wind->speed;
					$data['current_condition']->humidity = $object->query->results->channel->atmosphere->humidity;
					$data['forecast_today'] = $object->query->results->channel->item->forecast[0];
					$data['forecast_tomorrow'] = $object->query->results->channel->item->forecast[0];


					$this->result = json_encode($data);

				}

			}


		}


		function getResult()
		{
			return $this->result ? json_decode($this->result) : null;
		}

	}

?>