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
        var scrollTarget = $('#'+target);
        $('html, body').stop().animate({
          'scrollTop': scrollTarget.offset().top
        }, 700, 'swing', function () {
          window.location.hash = target;
        });
      });

      $('.office-list-row').each(function () {
        var text = $(this).find('span').text();
        text = text.replace(/\s+/g, '-').toLowerCase();
        $(this).addClass(text).attr('tabindex', '0');
        console.log(text);
      });

      $(document).on('click', '.office-list-row', function (e) {
        e.preventDefault();    
        var target = $(this).attr('class').split(' ');
        var selector = target[2];
        var scrollTarget = $('#'+selector);
        $('html, body').stop().animate({
          'scrollTop': scrollTarget.offset().top
        }, 700, 'swing', function () {
          window.location.hash = target;
        });
      });

      $('.paragraph--type--regional-office:last').addClass('last');

      $('#statesList').on('change', function() {
        var value = $(this).val();
        $('#' + value).trigger('click');
      });

      $('#States g').on('keypress', function(e) {
        if(e.which === 13) {
          $(this).trigger('click');
        }
      });

      $('#block-views-block-regional-office-list-block-1 .office-list-row').on('keypress', function(e) {
        if(e.which === 13) {
          $(this).trigger('click');
        }
      });      

    }
  }

})(jQuery, Drupal, drupalSettings);