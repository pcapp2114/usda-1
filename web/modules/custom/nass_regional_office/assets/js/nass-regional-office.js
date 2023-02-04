(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.regionalOffices = {
    attach: function (context, settings) { 
      var color;
      var states;
      var state;
      var region_name;
      var class_name;
      var office = [];
      office = drupalSettings.regionalOffices;
      office = JSON.parse(office);

      for (let i = 0; i < office.length; i++) {
        region_name = office[i]['region_name'][0]['value'];
        class_name = region_name.replace(/\s+/g, '-').toLowerCase();
        states = office[i]['state_abrev'];
        color = office[i]['region_color'];
        for (let x = 0; x < states.length; x++) {
          state = states[x];
          $('#' + state + ' path').css({ fill: color[0]['color'] });
          $('#' + state).addClass(class_name);

        }
      }

      $(document).on('click', '#States g', function (e) {
        e.preventDefault();
      
        var target = this.className.baseVal;
        console.log(target);
        var scrollTarget = $('#'+target);
        console.log(scrollTarget);
        $('html, body').stop().animate({
          'scrollTop': scrollTarget.offset().top
        }, 700, 'swing', function () {
          window.location.hash = target;
        });
      });

      $('.paragraph--type--regional-office:last').addClass('last');

    }
  }

})(jQuery, Drupal, drupalSettings);