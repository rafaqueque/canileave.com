function getWeather(_place)
{
    $.ajax({
        url: 'ajax-requests/fetch_weather.php',
        data: { place: _place },
        beforeSend: function(){
            $('#search-result').html('<img src="img/ajax-loader.gif">');
        },
        success: function(response){
            $('#search-result').html(response);
        }
    });
}


$(document).ready(function(){

    $(window).on('load', function(event){
        if (window.location.hash)
        {
            var place = decodeURIComponent(window.location.hash.replace('#',''));
            
            $('form[name=search] input[name=place]').val(place);
            getWeather(place);
            
            window.location.hash = encodeURIComponent(place);
        }
        else
        {
            $('form[name=search] input[name=place]').attr('autofocus','autofocus');
        }
    });

    $('form[name=search]').on('submit', function(event){
        event.preventDefault();
        getWeather($('form[name=search] input[name=place]').val());

        /* track ajax page views and change url */
        window.location.hash = encodeURIComponent($('form[name=search] input[name=place]').val());
        _gaq.push(['_trackPageview', window.location.href.replace(window.location.origin,'')]);
    });
    
});