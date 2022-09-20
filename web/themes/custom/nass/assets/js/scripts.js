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

  $('.tab-titles li').click(function(e) {
    e.preventDefault();
    const active = document.querySelector('.tab-titles li.active');
    if(active){
      active.classList.remove('active');
    }
    e.currentTarget.classList.add('active');
  });

  $('.tab-content li').click(function(e) {
    e.preventDefault();
    const active = document.querySelector('.tab-content li.active');
    if(active){
      active.classList.remove('active');
    }
    e.currentTarget.classList.add('active');
  });

  

}(jQuery));
