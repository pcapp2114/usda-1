(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.regionalOffices = {
    attach: function (context, settings) { 

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

      $('#block-views-watershed-regions .views-row a').on('keypress', function(e) {
        if(e.which === 13) {
          $(this).trigger('click');
        }
      });
      
    }
  }

})(jQuery, Drupal, drupalSettings);