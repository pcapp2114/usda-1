(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.marketValue = {
    attach: function (context, settings) { 

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

      $(document).on('click', '.state', function (e) {
        e.preventDefault();    
        var target = $(this).attr('id');
        console.log(target);
        $('div.' + target + ' a')[0].click();
        // var selector = target[2];
        // var scrollTarget = $('#'+selector);
        // $('html, body').stop().animate({
        //   'scrollTop': scrollTarget.offset().top
        // }, 700, 'swing', function () {
        //   window.location.hash = target;
        // });
      });

      $('.paragraph--type--regional-office:last').addClass('last');

      // $('#statesList').on('change', function() {
      //   var value = $(this).val();
      //   $('#' + value).trigger('click');
      // });

      // $('#States g').on('keypress', function(e) {
      //   if(e.which === 13) {
      //     $(this).trigger('click');
      //   }
      // });

      $('#block-views-block-regional-office-list-block-1 .office-list-row').on('keypress', function(e) {
        if(e.which === 13) {
          $(this).trigger('click');
        }
      });      

    }
  }

})(jQuery, Drupal, drupalSettings);