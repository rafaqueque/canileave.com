<!DOCTYPE html>
<html>
  <head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# 
                  website: http://ogp.me/ns/website#">
    <meta charset="UTF-8" />
    <meta property="og:type" content="blog"> 
    <meta property="og:url" content="http://canileave.com/"> 
    <meta property='og:title' content='Can I leave my home safely? — Real-time Weather Report' />
    <meta property='og:site_name' content='Can I leave my home safely? — Real-time Weather Report' />
    <meta property='og:description' content='Real-time weather report.' />
    <meta property="og:image" content="http://canileave.com/img/cloud.jpg">
    <meta name="description" content="Real-time weather report.">
    <meta name="keywords" content="weather, real time, live, weather report, climate, report">
    <title>Can I leave my home safely? — Real-time Weather Report</title>

    <link href="css/style.css" rel="stylesheet" media="screen">
    <script src="http://www.geoplugin.net/javascript.gp" type="text/javascript"></script>
  </head>
  <body>
    
    <div class='top-right'>
      a useless website created by <b>rafaqueque</b> * <a target='_blank' href='http://rafael.pt'>rafael.pt</a>
    </div>
    <div style='clear:both'></div>


    <div class='container'>
      <form method='get'>
        Hey <input type='text' class='input-big' name='place' placeholder='city...' value='<?php echo $_GET['place']; ?>'>, can I leave my home safely?
      </form>
      
      <span class='small-text'>#protip: type the city name above and press enter.</span><br>

      <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://canileave.com" data-text="Can I leave my home safely? — Real-time Weather Report —">Tweet</a>
      <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

      <iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fcanileave.com&amp;send=false&amp;layout=button_count&amp;width=100&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100px; height:21px;" allowTransparency="true"></iframe>
      <br>


      <?php 

        if (isset($_GET['place']))
        {
        	include("lib/simpleweather.class.php");

            $weather = new SimpleWeather(array("place" => $_GET['place'], "degrees" => "c"));

            if ($weather->getResult())
            {
                echo "<span class='small-text'>interpreted as: ".$weather->getResult()->location->city.(($weather->getResult()->location->country) ? ", ".$weather->getResult()->location->country : "")."</span><br>";
                echo "LOL SURE, can you handle <b class='colored'>".$weather->getResult()->current_condition->temp."ºC</b> 
                      and <b class='colored'>".$weather->getResult()->current_condition->text."</b> weather? 
                      Oh, <b class='colored'>".$weather->getResult()->current_condition->humidity."%</b> humidity and <b class='colored'>".round($weather->getResult()->current_condition->wind * 1.60934)."km/h</b> winds too.";
            }
            else
            {
                echo "<br><br>City not found, stupid!";
            }
        }

      ?>
    </div>

    <script type="text/javascript">

      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-36932606-1']);
      _gaq.push(['_setDomainName', 'canileave.com']);
      _gaq.push(['_trackPageview']);

      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();

    </script>
  </body>
</html>