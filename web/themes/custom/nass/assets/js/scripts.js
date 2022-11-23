(function ($) {
  /** for state nav mobile view **/
  let scrollPos = 100;
  const nav = document.querySelector('.state-nav ul');
  function checkPosition() {
    let navScroll = nav.scrollLeft;
    if (navScroll > scrollPos) {
      // Scrolling UP
      nav.classList.add('is-hidden');
      nav.classList.remove('is-visible');
    } else {
      // Scrolling DOWN
      nav.classList.add('is-visible');
      nav.classList.remove('is-hidden');
    }
  }
  if(nav){
    nav.addEventListener('scroll', checkPosition);
  }

  $('.tab-titles li').click(function (e) {
    e.preventDefault();
    const active = document.querySelector('.tab-titles li.active');
    const content = document.querySelector('.tab-content li.active');
    let data = $(this).data('tab');
    if(active){
      active.classList.remove('active');
    }
    if(content){
      content.classList.remove('active');
    }
    e.currentTarget.classList.add('active');
    $('.tab-content').find('#' + data).addClass('active');
  });

  $('document').ready(function () {
    setTimeout(function() {
      $('#block-views-block-by-survey-glossary-block-1 div.view-header > p > span a').trigger('click');
    }, 10);
  });

  Drupal.behaviors.surveyFunc = {
    attach: function (context, settings) {
      $('.view-by-survey-glossary- .views-summary a').click(function () {
        var activeClass = $(this).attr("class");
        var uri = window.location.href.toString();
        if (uri.indexOf("?") > 0) {
            var clean_uri = uri.substring(0, uri.indexOf("?"));
            window.history.replaceState({}, document.title, clean_uri);
        }
        var refresh = window.location.protocol + "//" + window.location.host + window.location.pathname + '?row=' + activeClass;    
        window.history.pushState({ path: refresh }, '', refresh);
      });
      let searchParams = new URLSearchParams(window.location.search);
      let param = searchParams.get('row');
      $('#block-views-block-by-survey-glossary-block-1' + ' .' + param).addClass('active');
      $('.ajax-progress').hide();
    }
  };

  Drupal.behaviors.asbnoticeFunc = {
    attach: function (context, settings) {
      $(document).ready(function () {
        $('.year-select h3').each(function () {
          var year = $(this).text().trim();
          $(this).attr('data-year', year);
        });
      });
      $('.year-select.all-years').click(function () {
        $('#edit-field-date-value').val('');
        $('#edit-submit-asb-notices-library-archive').click();
      });
      $('.year-select h3').click(function () {
        $(this).addClass('active');
        var activeYear = $(this).text();
        $('#edit-field-date-value').val(activeYear);
        $('#edit-submit-asb-notices-library-archive').click();
      });
      let searchParams = new URLSearchParams(window.location.search);
      let param = searchParams.get('field_date_value');
      if (searchParams.get('field_date_value') == 'all' || searchParams.get('field_date_value') == '' || searchParams.has('field_date_value') == false) {
        $('.year-select.all-years').addClass('active');
      } else {
        $('.year-select').find('h3:contains(' + param + ')').each(function () {
          $(this).addClass('active');
        });
        //$('.view-asb-notices-year-list' + ' .' + param).addClass('active');
      }
      $('.ajax-progress').hide();
    }
  };

  Drupal.behaviors.newsReleaseFunc = {
    attach: function (context, settings) {
      $(document).ready(function () {
        $('.year-select h3').each(function () {
          var year = $(this).text().trim();
          $(this).attr('data-year', year);
        });
      });
      $('.year-select.all-years').click(function () {
        $('#edit-field-date-value').val('');
        $('#edit-submit-library-archive').click();
      });
      $('.year-select h3').click(function () {
        $(this).addClass('active');
        var activeYear = $(this).text();
        $('#edit-field-date-value').val(activeYear);
        $('#edit-submit-library-archive').click();
      });
      let searchParams = new URLSearchParams(window.location.search);
      let param = searchParams.get('field_date_value');
      if (searchParams.get('field_date_value') == 'all' || searchParams.get('field_date_value') == '' || searchParams.has('field_date_value') == false) {
        $('.year-select.all-years').addClass('active');
      } else {
        $('.year-select').find('h3:contains(' + param + ')').each(function () {
          $(this).addClass('active');
        });
      }
      $('.ajax-progress').hide();
    }
  };

  Drupal.behaviors.asbbriefingsFunc = {
    attach: function (context, settings) {
      $(document).ready(function () {
        $('.year-select h3').each(function () {
          var year = $(this).text().trim();
          $(this).attr('data-year', year);
        });
      });
      $('.year-select.all-years').click(function () {
        $('#edit-field-date-and-time-value').val('');
        $('#edit-submit-asb-briefings-library-archive').click();
      });
      $('.year-select h3').click(function () {
        $(this).addClass('active');
        var activeYear = $(this).text();
        $('#edit-field-date-and-time-value').val(activeYear);
        $('#edit-submit-asb-briefings-library-archive').click();
      });
      let searchParams = new URLSearchParams(window.location.search);
      let param = searchParams.get('field_date_and_time_value');
      if (searchParams.get('field_date_and_time_value') == 'all' || searchParams.get('field_date_and_time_value') == '' || searchParams.has('field_date_and_time_value') == false) {
        $('.year-select.all-years').addClass('active');
      } else {
        $('.year-select').find('h3:contains(' + param + ')').each(function () {
          $(this).addClass('active');
        });
      }
      $('.ajax-progress').hide();
    }
  };

  Drupal.behaviors.yearSelectFunc = {
    attach: function (context, settings) {
      $('#year-list').on("change", function(){
        var activeYear = $(this).find('option:selected').val();
        $('#edit-field-conference-value, #edit-field-date-value').val(activeYear);
        $('#edit-submit-conference-presentation-archive, #edit-submit-report-archive').click();
      });
      let searchParams = new URLSearchParams(window.location.search);
      let param = searchParams.get('field_date_value');
      if (searchParams.get('field_date_value') == 'all' || searchParams.get('field_date_value') == '' || searchParams.has('field_date_value') == false) {

      } else {
        $(document).ready(function () {
          console.log(param);
          $('#year-list').val(param).find("option[value=" + param + "]").attr('selected', true);
          $('#year-list option:contains('+param+')').prop('selected',true);
        });
      }
      $('.ajax-progress').hide();
    }
  };

}(jQuery));
