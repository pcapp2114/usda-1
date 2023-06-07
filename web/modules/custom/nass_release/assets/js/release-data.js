(function ($, Drupal, drupalSettings) {

  Drupal.behaviors.nassRelease = {
    attach: function (context, settings) {
      console.log(settings);

      var baseurl = window.location.origin;
      var todayURL = baseurl + drupalSettings.nassRelease.releaseData;

    }
  }

}(jQuery, Drupal, drupalSettings));