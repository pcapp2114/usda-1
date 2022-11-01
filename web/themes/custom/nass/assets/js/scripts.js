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

}(jQuery));
