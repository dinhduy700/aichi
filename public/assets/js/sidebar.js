$(function () {
  let sidebar = $('.sidebar');
  sidebar.off('show.bs.collapse', '.collapse');
  let collapse = sidebar.find('.collapse.show');
  function removeActiveClass() {
    $('.show').removeClass('show');
    $('.active').removeClass('active');
    sidebar.find('.collapse.show').collapse('hide');
  }
  removeActiveClass();
  function getUrl() {
    let url = location.href;
    if (url.charAt(url.length - 1) === '/') {
      url = url.slice(0, -1);
    }
    return url;
  }
  let url = getUrl();

  function addActiveClass(element) {
    if (element.attr('href') === url) {
      element.parents('.nav-item').last().addClass('active');
      if (element.parents('.sub-menu').length) {
        element.closest('.collapse').addClass('show');
        element.addClass('active');
        let collapse = element.closest('.collapse')
        if (collapse.hasClass('sub_menu')) {
          collapse.closest('.menu').addClass('show');
          $('[aria-controls=' + collapse.attr('id') + ']').closest('li').addClass('active');
        }
      }
      if (element.parents('.submenu-item').length) {
        element.addClass('active');
      }
    }
  }
  $('.nav li a', sidebar).each(function () {
    let $this = $(this);
    addActiveClass($this);
  });
  sidebar.on('show.bs.collapse', '.collapse', function (event) {
    if (!$(event.target).hasClass('sub_menu')) {
      sidebar.find('.collapse.show').collapse('hide');
    } else {
      sidebar.find('.collapse.show.sub_menu').collapse('hide');
    }
  });

  let body = $('body');
  $('[data-toggle="minimize"]').on("click", function() {
    if(body.hasClass('sidebar-icon-only')) {
      $('.sub_menu').addClass('d-none');
    } else{
      $('.sub_menu').removeClass('d-none');
    }
  });
})
