(function ($, Drupal, drupalSettings) {

  Drupal.behaviors.nassRelease = {
    attach: function (context, settings) {
      // The rebuilt block renders releases server-side from the release repository.
      // Keep this script available for legacy pages, but do not append duplicate
      // release rows when the server-rendered block is present.
      if ($('#todays-releases[data-nass-release-server-rendered="1"]', context).length || $('#todays-releases[data-nass-release-server-rendered="1"]').length) {
        return;
      }

      if (!drupalSettings.nassRelease) {
        return;
      }

      var releaseData = drupalSettings.nassRelease.releaseData || [];
      var upcomingData = drupalSettings.nassRelease.upcomingData || [];
      var currentTimePath = drupalSettings.nassRelease.currentTime || '';
      var noReleaseTXT = drupalSettings.nassRelease.no_release || 'There are no NASS reports scheduled.';
      var baseurl = window.location.origin;
      var timeURL = currentTimePath.indexOf('http') === 0 ? currentTimePath : baseurl + currentTimePath;
      var runFlag = 0;
      var timeScope;
      var todayswrap = $('#todays-release-wrap');

      if (!todayswrap.length) {
        return;
      }

      function convertTimestamp(timestamp) {
        var d = new Date(timestamp * 1000);
        var yyyy = d.getFullYear();
        var mm = ('0' + (d.getMonth() + 1)).slice(-2);
        var dd = ('0' + d.getDate()).slice(-2);
        var h = d.getHours();
        var min = ('0' + d.getMinutes()).slice(-2);
        var sec = ('0' + d.getSeconds()).slice(-2);
        return yyyy + '-' + mm + '-' + dd + ' ' + h + ':' + min + ':' + sec;
      }

      if (releaseData.length === 0) {
        todayswrap.append('<div class="release-item-info no-release">' + noReleaseTXT + '</div>');
        return;
      }

      if (runFlag === 0) {
        $.get(timeURL, function (result) {
          timeScope = Math.floor(Date.parse(result) / 1000);
          runFlag++;
        });
      }

      setInterval(function () {
        $.get(timeURL, function (result) {
          timeScope = Math.floor(Date.parse(result) / 1000);
        });
      }, 1800000);

      setInterval(function () {
        if (typeof timeScope === 'number') {
          timeScope++;
        }
      }, 1000);

      setInterval(function () {
        if (typeof timeScope !== 'number') {
          return;
        }

        for (var i = 0; i < releaseData.length; i++) {
          var todaysData = releaseData[i];
          var todayTime = todaysData.time;
          var todayTitle = todaysData.title;
          var filename = todaysData.filename;
          var fulldate = new Date(todaysData.full_datetime);
          var todayHost = todaysData.host || '';
          var endTime = Date.parse(fulldate) / 1000;
          var now = timeScope;
          var timeLeft = endTime - now;
          var days = Math.floor(timeLeft / 86400);
          var hours = Math.floor((timeLeft - (days * 86400)) / 3600);
          var minutes = Math.floor((timeLeft - (days * 86400) - (hours * 3600)) / 60);
          var seconds = Math.floor((timeLeft - (days * 86400) - (hours * 3600) - (minutes * 60)));

          if (hours < 10) { hours = '0' + hours; }
          if (minutes < 10) { minutes = '0' + minutes; }
          if (seconds < 10) { seconds = '0' + seconds; }

          now = new Date(convertTimestamp(now));
          todayswrap.find('div[data-title="' + todayTitle + '"]').remove();

          if (now > fulldate) {
            todayswrap.append('<div class="release-item-info" data-title="' + todayTitle + '"><div class="release-time-title"><div class="release-date-time">' + todayTime + '</div><h4><a href="/data-and-statistics/todays-releases">' + todayTitle + '</a></h4></div><ul class="usa-button-group usa-button-group--segmented release-data"><li class="usa-button-group__item"><a class="usa-button usa-button--outline" href="' + todayHost + filename + '.txt" target="_blank">Text</a></li><li class="usa-button-group__item"><a class="usa-button usa-button--outline" href="' + todayHost + filename + '.pdf" target="_blank">PDF</a></li><li class="usa-button-group__item"><a class="usa-button usa-button--outline" href="' + todayHost + filename + '.zip" target="_blank">CSV</a></li></ul></div>');
          }
          else if (Number(hours) > 3) {
            todayswrap.append('<div class="release-item-info" data-title="' + todayTitle + '"><div class="release-time-title"><div class="release-date-time">' + todayTime + '</div><h4><a href="/data-and-statistics/todays-releases">' + todayTitle + '</a></h4></div><ul class="usa-button-group usa-button-group--segmented timer">Pending Report...</ul></div>');
          }
          else {
            todayswrap.append('<div class="release-item-info" data-title="' + todayTitle + '"><div class="release-time-title"><div class="release-date-time">' + todayTime + '</div><h4><a href="/data-and-statistics/todays-releases">' + todayTitle + '</a></h4></div><ul class="usa-button-group usa-button-group--segmented timer" id="timer-' + i + '"><div class="hours">' + hours + '<span>h</span></div><div class="minutes">' + minutes + '<span>m</span></div><div class="seconds">' + seconds + '<span>s</span></div></ul></div>');
          }
        }
      }, 1000);
    }
  };

}(jQuery, Drupal, drupalSettings));
