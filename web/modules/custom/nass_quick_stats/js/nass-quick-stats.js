(function ($, Drupal, drupalSettings) {
  console.log(drupalSettings);
  Drupal.behaviors.quickStats = {
    attach: function (context, settings) {

      var data;
      var baseurl = window.location.origin;
      var paramsURL = baseurl + drupalSettings.quickStats.params;
      console.log(paramsURL);

      function toTitleCase(str) {
        return str.replace(
          /\w\S*/g,
          function(txt) {
            return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
          }
        );
      }

      $.ajax({
        cache: false,
        method: 'GET',
        url: paramsURL,
        // contentType: 'application/json',
        data: data,
        // dataType: 'json',
        error: function (e, textStatus, errorThrown) {
          console.log('No response');
          console.log(textStatus,errorThrown);
        },
        beforeSend: function () {
          // $('#scl-one-county, #scl-two-county, #scl-dadt-county').empty();
        },
        success: function (data) {

          var sect;
          sect = data.sector_desc;
          console.log(data.sector_desc);
          for (var i = 0; i < sect.length; i++) {
            var sectors = sect[i];
            $('#sector').append('<option>' + toTitleCase(sectors) + '</option>');
          }
        
        }
      }); 

    }
  }
}(jQuery, Drupal, drupalSettings));