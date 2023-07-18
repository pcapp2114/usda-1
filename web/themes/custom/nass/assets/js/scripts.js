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

  // Tab areas on Survey pages
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

// Trigger tabs on Survey pages from URL
  $(function() {
    var url = window.location.href;
    var id = url.substring(url.lastIndexOf('#') + 1);
    // When tab is clicked change the ID in the URL    
    $('.tab-item').on('click', function () {
      var newURL = location.href.split("#")[0];
      window.history.pushState('object', document.title, newURL + "#" + this.id);
    });
    // If ID exist in the URL, click the matching ID on the page.
    if (url.search("#") >= 0) {
      $('#' + id).click();     
    }

  }); 

  $(function() {
    setTimeout(function() {
      $('#block-views-block-by-survey-glossary-block-1 div.view-header > p > span a').trigger('click');
    }, 10);
  });

  $(function() {
    setTimeout(function() {
      $('#block-views-block-methodology-quality-measures-glossary-block-1 div.view-header > p > span a').trigger('click');
    }, 10);
  });

  $(window).scroll(function() {
    if ($(this).scrollTop() > 900) {
      $('#back-to-top').addClass('active');
    } else {
      $('#back-to-top').removeClass('active');
    }
  });

  $('#back-to-top').click(function() {
    $('html, body').animate({scrollTop: 0}, 1200);
  });

  $('#cal-prev').click(function(){
    $('.pager__previous a')[0].click();
  });

  $('#cal-next').click(function(){
    $('.pager__next a')[0].click();
  }); 
  
  $('#reset-cal').click(function(){
    $('.calendar-view-pager__reset a')[0].click();
  });   

  $('#cal-list').on('click', function() {
    $(this).toggleClass('cal');
    $('.calendar-view-table').toggleClass('list-view');
  });

  $('#print-cal').click(function(){
    window.print();
    return false;
  });

  $(document).on('click','#quick-stats-select-all', function(e){
    $('input:checkbox').not(this).prop('checked', this.checked);
  });

  $('.carousel').slick();

  $('.carousel-card-item').on('click', function(){
    var slide = $(this).attr('aria-controls');
    $('.slick-dots').find('[aria-controls='+ slide +']').trigger('click');
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

  Drupal.behaviors.methodologyFunc = {
    attach: function (context, settings) {
      $('.view-methodology-quality-measures-glossary- .views-summary a').click(function () {
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
      $('#block-views-block-methodology-quality-measures-glossary-block-1' + ' .' + param).addClass('active');
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
        $('#edit-submit-conference-presentation-archive, #edit-submit-report-archive, #edit-submit-journal-article-archive').click();
      });
      let searchParams = new URLSearchParams(window.location.search);
      let param = searchParams.get('field_date_value');
      if (searchParams.get('field_date_value') == 'all' || searchParams.get('field_date_value') == '' || searchParams.has('field_date_value') == false) {

      } else {
        $(document).ready(function () {
          $('#year-list').val(param).find("option[value=" + param + "]").attr('selected', true);
          $('#year-list option:contains('+param+')').prop('selected',true);
        });
      }
      $('.ajax-progress').hide();
    }
  };

  // Accordion open/close toggle
  Drupal.behaviors.accordion = {
    attach: function (context, settings) {  
      if ($('.usa-accordion').hasClass('usa-accordion--expand-first')) {
        $('.usa-accordion__heading button').first().attr("aria-expanded", "true");
      }

      if ($('.usa-accordion').hasClass('usa-accordion--expand-all')) {
        $('.usa-accordion__heading button').attr("aria-expanded", "true");
      }
    }
  };

}(jQuery));
