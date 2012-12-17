(function($){

  /* fetches the weather via ajax */
  var getWeatherAjax = function(_place, _woeid, _unit) {
    if (!_unit)
    {
      _unit = 'c';
    }

    var params = {
      place: _place,
      woeid: _woeid,
      unit: _unit
    };

    $.ajax({
      url: 'system/ajax-requests/fetch_weather.php',
      data: params,
      cache: false,
      dataType: 'json',
      beforeSend: function(){
        $('#search-result').html('<img src="img/ajax-loader.gif">');
      },
      success: function(response){
        $('input[type=checkbox][value='+_unit+']').attr('checked','checked');

        /* load mustache.js template and display the results */
        $.get('templates/results.tpl.html', function(template){
          $('#search-result').html(Mustache.to_html(template, response));
        });
      }
    });

    return true;
  };


  /* return parameters from hash */
  var getHash = function(param) {
    if (param === 'place')
        return window.location.hash.split("/")[0].replace('#','');

    if (param === 'woeid')
        return window.location.hash.split("/")[1] || null;
  };


  /* process parameters from hash and fetches weather information automatically. used on page load */
  var getWeatherFromHash = function() {
    var _place = decodeURIComponent(getHash('place'));
    var _woeid = getHash('woeid');
    
    $('#search #place').val(_place);
    $('#search #woeid').val(_woeid);

    getWeatherAjax(_place, _woeid);
    
    window.location.hash = encodeURIComponent(_place)+((_woeid) ? '/'+_woeid : '');

    $('#search #place').removeAttr('autofocus');

    return true;
  };



  $(document).ready(function(){

    /* onload event, to check if the url has parameters. if not, tries to guess geolocation */
    $(window).on('load', function(){
      if (window.location.hash)
      {
        getWeatherFromHash();
      }
      else
      {
        $.getJSON('system/ajax-requests/fetch_geolocation.php', function(response){
          $('#search #place').val(response.result);
          $('#search').submit();
        });
      }
    });


    /* onsubmit form event, prevents page from refresh, fetches the weather information via ajax and updates the hash accordingly to search */
    $('#search').on('submit', function(event){
      event.preventDefault();

      var _place = $('#search #place').val();
      var _woeid = $('#search #woeid').val();
      var _unit = $('#search #unit:checked').val();

      getWeatherAjax(_place, _woeid, _unit);

      window.location.hash = encodeURIComponent(_place);
      _gaq.push(['_trackPageview', window.location.href.replace(window.location.origin,'')]);
    });


    /* if the user wants Imperial units, submit the form again */
    $(document).on('click', '#unit', function(){
          if ($('#search #place').val() !== '')
          {
            $('#search').submit();
          }
    });

    /* clicking on the suggested links will not refresh the page, but it will update the url and fetches the weather to the specified link */
    $(document).on('click', '.alternative-link', function(event){
          event.stopPropagation();
          window.location.hash = $(this).attr('href');
          getWeatherFromHash();
    });

    /* basic toggle to show suggested/alternatives to the actual search */
    $(document).on('click', '#open-related-alternatives', function(event){
          $('#search-related-alternatives').slideToggle();
    });
  });

})(jQuery);