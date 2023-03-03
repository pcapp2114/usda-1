(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.regionalOffices = {
    attach: function (context, settings) {
      let regions = drupalSettings.region_map;
      regions = JSON.parse(regions);

      Object.values(regions).forEach(function (region) {
        const regionName = region.name;
        const regionColor = region.color;
        const regionUrl = region.url;
        const states = region.states;
        const className = regionName.replace(/\s+/g, '-').toLowerCase();
        states.forEach(function (state) {
            $('#' + state + ' path').css({fill: regionColor});
            $('#' + state).addClass(className);
        });
      });
    }
  };
})(jQuery, Drupal, drupalSettings);
