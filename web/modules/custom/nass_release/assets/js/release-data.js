(function ($, Drupal, drupalSettings) {

  Drupal.behaviors.nassRelease = {
    attach: function (context, settings) {

      var releaseData = drupalSettings.nassRelease.releaseData;
      var upcomingData = drupalSettings.nassRelease.upcomingData;
      var baseurl = window.location.origin;
      var timeURL = baseurl + drupalSettings.nassRelease.currentTime;      

      var todaysData;
      var todayTime;
      var todayTitle;
      var formattedDate;
      var fulldate;
      var now;
      var filename;
      var todayswrap = $('#todays-release-wrap');
      var upcomingwarp = $('#upcoming-release-data');

      for (var i = 0; i < releaseData.length; i++) {
        todaysData = releaseData[i];
        todayTime = todaysData['time'];
        todayTitle = todaysData['title'];
        filename = todaysData['filename'];
        filename = filename[0]['value'];
        now = todaysData['now'];
        fulldate = todaysData['full_datetime'];
        if (now >= fulldate) {
          todayswrap.append('<div class="release-item-info" data-time="' + todayTime + '"><div class="release-time-title"><div class="release-date-time">' + todayTime + '</div><h4><a href="/data-and-statistics/todays-releases">' + todayTitle + '</a></h4></div><ul class="usa-button-group usa-button-group--segmented release-data"><li class="usa-button-group__item"><button type="button" class="usa-button usa-button--outline"><a href="https://release.nass.usda.gov/reports/'+ filename +'.txt" target="_blank">Text</a></button></li><li class="usa-button-group__item"><button type="button" class="usa-button usa-button--outline"><a href="https://release.nass.usda.gov/reports/'+ filename +'.pdf" target="_blank">PDF</a></button></li><li class="usa-button-group__item"><button type="button" class="usa-button usa-button--outline"><a href="https://release.nass.usda.gov/reports/'+ filename +'.zip" target="_blank">CSV</a></button></li></ul></div>');
        } else {
          todayswrap.append('<div class="release-item-info" data-time="' + todayTime + '"><div class="release-time-title"><div class="release-date-time">' + todayTime + '</div><h4><a href="/data-and-statistics/todays-releases">' + todayTitle + '</a></h4></div><ul class="usa-button-group usa-button-group--segmented release-data"><li class="usa-button-group__item"><span>Pending report...</span></li></ul></div>');
        }
      }

      var releaseToday = releaseData[0]['full_datetime'];
      releaseToday = new Date(releaseToday);
      releaseToday = releaseToday.toString('M-D-YYYY');

      setInterval(function(){
        $.get(timeURL, function (result) {
          for (var i = 0; i < releaseData.length; i++) {
            todaysData = releaseData[i];
            todayTime = todaysData['time'];
            todayTitle = todaysData['title'];
            filename = todaysData['filename'];
            filename = filename[0]['value'];
            fulldate = todaysData['full_datetime'];

            var endTime = fulldate;			
            endTime = (Date.parse(endTime) / 1000);

            now = (Date.parse(result) / 1000);
      
            var timeLeft = endTime - now;
      
            var days = Math.floor(timeLeft / 86400); 
            var hours = Math.floor((timeLeft - (days * 86400)) / 3600);
            var minutes = Math.floor((timeLeft - (days * 86400) - (hours * 3600 )) / 60);
            var seconds = Math.floor((timeLeft - (days * 86400) - (hours * 3600) - (minutes * 60)));
        
            if (hours < "10") { hours = "0" + hours; }
            if (minutes < "10") { minutes = "0" + minutes; }
            if (seconds < "10") { seconds = "0" + seconds; }
      
            // $('#timer-' + i).find('.days').html(days + "<span>Days</span>");
            // $('#timer-' + i).find('.hours').html(hours + "<span>Hours</span>");
            // $('#timer-' + i).find('.minutes').html(minutes + "<span>Minutes</span>");
            // $('#timer-' + i).find('.seconds').html(seconds + "<span>Seconds</span>");		

            if (now >= fulldate) {
              todayswrap.find('div[data-time="' + todayTime + '"]').remove();
              todayswrap.append('<div class="release-item-info" data-time="' + todayTime + '"><div class="release-time-title"><div class="release-date-time">' + todayTime + '</div><h4><a href="/data-and-statistics/todays-releases">' + todayTitle + '</a></h4></div><ul class="usa-button-group usa-button-group--segmented release-data"><li class="usa-button-group__item"><button type="button" class="usa-button usa-button--outline"><a href="https://release.nass.usda.gov/reports/'+ filename +'.txt" target="_blank">Text</a></button></li><li class="usa-button-group__item"><button type="button" class="usa-button usa-button--outline"><a href="https://release.nass.usda.gov/reports/'+ filename +'.pdf" target="_blank">PDF</a></button></li><li class="usa-button-group__item"><button type="button" class="usa-button usa-button--outline"><a href="https://release.nass.usda.gov/reports/'+ filename +'.zip" target="_blank">CSV</a></button></li></ul></div>');
            } else {
              todayswrap.find('div[data-time="' + todayTime + '"]').remove();
              todayswrap.append('<div class="release-item-info" data-time="' + todayTime + '"><div class="release-time-title"><div class="release-date-time">' + todayTime + '</div><h4><a href="/data-and-statistics/todays-releases">' + todayTitle + '</a></h4></div><ul class="usa-button-group usa-button-group--segmented timer" id="timer-' + i + '"><div class="hours">' + hours + '<span>h</span></div><div class="minutes">' + minutes + '<span>m</span></div><div class="seconds">' + seconds + '<span>s</span></div></ul></div>');
            }

          }
        });
      }, 1000);

      for (var i = 0; i < upcomingData.length; i++) {
        todaysData = upcomingData[i];
        todayTime = todaysData['time'];
        todayTitle = todaysData['title'];
        formattedDate = todaysData['formatted_date'];
        upcomingwarp.prepend('<div class="upcoming-item-info"><div class="release-time-title"><div class="release-date-time">' + formattedDate + '<span>|</span>' + todayTime + '</div><h4>' + todayTitle + '</h4></div></div>');
      }

    }
  }

}(jQuery, Drupal, drupalSettings));