function getWeatherAjax(_place, _woeid, _unit)
{
    if (!_unit)
    {
        _unit = 'c';
    }

    $.ajax({
        url: 'ajax-requests/fetch_weather.php',
        data: { place: _place, woeid: _woeid, unit: _unit },
        cache: false,
        beforeSend: function(){
            $('#search-result').html('<img src="img/ajax-loader.gif">');
        },
        success: function(response){
            $('#search-result').html(response);
            $('input[type=radio][value='+_unit+']').attr('checked','checked');
        }
    });

    return true;
}


function getHash(param)
{
    switch (param)
    {
        case 'place':
            return window.location.hash.split("/")[0].replace('#','');
            break;

        case 'woeid':
            return window.location.hash.split("/")[1] || null;
            break;        
    }
}


function getWeatherFromHash()
{
    var place = decodeURIComponent(getHash('place'));
    var woeid = getHash('woeid');
    
    $('form[name=search] input[name=place]').val(place);
    $('form[name=search] input[name=woeid]').val(woeid);

    getWeatherAjax(place, woeid);
    
    window.location.hash = encodeURIComponent(place)+((woeid) ? '/'+woeid : '');

    $('form[name=search] input[name=place]').removeAttr('autofocus');

    return true;
}


$(document).ready(function(){

    $(window).on('load', function(event){
        if (window.location.hash)
        {
           getWeatherFromHash();
        }
    });

    $('form[name=search]').on('submit', function(event){
        event.preventDefault();
        getWeatherAjax($('form[name=search] input[name=place]').val(),'',$('form[name=search] input[name=unit]:checked').val());

        /* track ajax page views and change url */
        window.location.hash = encodeURIComponent($('form[name=search] input[name=place]').val());
        _gaq.push(['_trackPageview', window.location.href.replace(window.location.origin,'')]);
    });

    $(document).on('click', '.alternative-link', function(event){
        event.stopPropagation();
        window.location.hash = $(this).attr('href');
        getWeatherFromHash();
    });

    $(document).on('click', '#open-related-alternatives', function(){
        $('#search-related-alternatives').slideToggle();
    });

    $(document).on('click', 'input[name=unit]', function(){

        if ($('form[name=search] input[name=place]').val() != '')
        {
            $('form[name=search]').submit();
        }

    });

    
});