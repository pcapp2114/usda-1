(function ($, Drupal, drupalSettings) {

  Drupal.behaviors.nassRelease = {
    attach: function (context, settings) {

      var releaseData = drupalSettings.nassRelease.releaseData;
      var upcomingData = drupalSettings.nassRelease.upcomingData;
      var baseurl = window.location.origin;
      var timeURL = baseurl + drupalSettings.nassRelease.currentTime;   
      var noReleaseTXT = drupalSettings.nassRelease.no_release;

      var todaysData;
      var todayTime;
      var todayTitle;
      var formattedDate;
      var fulldate;
      var timeScope;
      var runFlag = 0;
      var now;
      var host;
      var filename;
      var todayswrap = $('#todays-release-wrap');
      var upcomingwarp = $('#upcoming-release-data');

      function convertTimestamp(timestamp) {
        var d = new Date(timestamp * 1000), // Convert the passed timestamp to milliseconds
            yyyy = d.getFullYear(),
            mm = ('0' + (d.getMonth() + 1)).slice(-2),  // Months are zero based. Add leading 0.
            dd = ('0' + d.getDate()).slice(-2),         // Add leading 0.
            hh = d.getHours(),
            h = hh,
            min = ('0' + d.getMinutes()).slice(-2),     // Add leading 0.
            sec = ('0' + d.getSeconds()).slice(-2),     // Add leading 0.
            time;
    
        time = yyyy + '-' + mm + '-' + dd + ' ' + h + ':' + min + ':' + sec;
        return time;
      }
    
      if (releaseData.length == 0) {
        $(document).ready(function() {
          todayswrap.append('<div class="release-item-info no-release">' + noReleaseTXT + '</div>');
        });
      } else {

        // Run once on page load
        if (runFlag == 0) {
          $.get(timeURL, function (result) {
            timeScope = Date.parse(result);
            timeScope = Math.floor(timeScope / 1000);
            runFlag++;
          });
        }
        
        // Run once every 30 mins to correct time drift
        setInterval(function () {
          $.get(timeURL, function (result) {
            timeScope = Date.parse(result);
            timeScope = Math.floor(timeScope / 1000);
          });
        }, 1800000);

        // Increment seconds on server Unix time
        setInterval(function () {
          timeScope++;
        }, 1000); 

        // Run countdown clock
        setInterval(function () {
          for (var i = 0; i < releaseData.length; i++) {
            todaysData = releaseData[i];
            todayTime = todaysData['time'];
            todayTitle = todaysData['title'];
            filename = todaysData['filename'];
            fulldate = todaysData['full_datetime'];
            fulldate = new Date(fulldate);
            todayHost = todaysData['host'];

            var endTime = fulldate;
            endTime = (Date.parse(endTime) / 1000);

            // Create now variable to create exact current timestamp
            now =  timeScope;

            var timeLeft = endTime - now;
      
            var days = Math.floor(timeLeft / 86400);
            var hours = Math.floor((timeLeft - (days * 86400)) / 3600);
            var minutes = Math.floor((timeLeft - (days * 86400) - (hours * 3600)) / 60);
            var seconds = Math.floor((timeLeft - (days * 86400) - (hours * 3600) - (minutes * 60)));
        
            if (hours < '10') { hours = '0' + hours; }
            if (minutes < '10') { minutes = '0' + minutes; }
            if (seconds < '10') { seconds = '0' + seconds; }

            // Convert to readable timestamp
            now = convertTimestamp(now);
            // Maintain string as date
            now = new Date(now);

            if (now > fulldate) {
              todayswrap.find('div[data-title="' + todayTitle + '"]').remove();
              todayswrap.append('<div class="release-item-info" data-title="' + todayTitle + '"><div class="release-time-title"><div class="release-date-time">' + todayTime + '</div><h4><a href="/data-and-statistics/todays-releases">' + todayTitle + '</a></h4></div><ul class="usa-button-group usa-button-group--segmented release-data"><li class="usa-button-group__item"><button type="button" class="usa-button usa-button--outline"><a href="' + todayHost + '' + filename + '.txt" target="_blank">Text</a></button></li><li class="usa-button-group__item"><button type="button" class="usa-button usa-button--outline"><a href="' + todayHost + '' + filename + '.pdf" target="_blank">PDF</a></button></li><li class="usa-button-group__item"><button type="button" class="usa-button usa-button--outline"><a href="' + todayHost + '' + filename + '.zip" target="_blank">CSV</a></button></li></ul></div>');
            } else {
              if (Number(hours) > Number(03)) {
                todayswrap.find('div[data-title="' + todayTitle + '"]').remove();
                todayswrap.append('<div class="release-item-info" data-title="' + todayTitle + '"><div class="release-time-title"><div class="release-date-time">' + todayTime + '</div><h4><a href="/data-and-statistics/todays-releases">' + todayTitle + '</a></h4></div><ul class="usa-button-group usa-button-group--segmented timer">Pending Report...</ul></div>');
              } else {
                todayswrap.find('div[data-title="' + todayTitle + '"]').remove();
                todayswrap.append('<div class="release-item-info" data-title="' + todayTitle + '"><div class="release-time-title"><div class="release-date-time">' + todayTime + '</div><h4><a href="/data-and-statistics/todays-releases">' + todayTitle + '</a></h4></div><ul class="usa-button-group usa-button-group--segmented timer" id="timer-' + i + '"><div class="hours">' + hours + '<span>h</span></div><div class="minutes">' + minutes + '<span>m</span></div><div class="seconds">' + seconds + '<span>s</span></div></ul></div>');
              }
            }
          }
        }, 1000);
      }

    }
  }

}(jQuery, Drupal, drupalSettings));