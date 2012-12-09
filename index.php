<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Can I leave?</title>
    <link href="css/style.css" rel="stylesheet" media="screen">
  </head>
  <body>
    
    <div class='container'>
      
      <form method='get'>
        Hey <input type='text' class='input-big' name='place' placeholder='city...' value='<?php echo $_GET['place']; ?>'>, can I leave my home safely?
      </form>
      <br><br>


      <?php 

        if ($_GET)
        {
            include("lib/simpleweather.class.php");

            $weather = new SimpleWeather(array("place" => $_GET['place'], "degrees" => "c"));

            if ($weather->getResult())
            {
                echo "LOL SURE, can you handle <b class='colored'>".$weather->getResult()->current_condition->temp."ºC</b> and <b class='colored'>".$weather->getResult()->current_condition->text."</b> weather?";
            }
            else
            {
                echo "City not found, stupid!";
            }
        }

      ?>
    </div>

    <script type="text/javascript">

      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-36919486-1']);
      _gaq.push(['_trackPageview']);

      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();

    </script>
  </body>
</html>