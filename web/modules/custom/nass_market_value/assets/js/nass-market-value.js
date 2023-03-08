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
        $('div.' + target + ' a')[0].click();
      });

      $('#us-map g').on('keypress', function(e) {
        if(e.which === 13) {
          $(this).trigger('click');
        }
      });      

    }
  }

})(jQuery, Drupal, drupalSettings);