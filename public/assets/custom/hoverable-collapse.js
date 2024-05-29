(function($) {
  'use strict';
  //Open submenu on hover in compact sidebar mode and horizontal menu mode
  $(document).on('mouseenter mouseleave', '.sidebar .nav-item', function (ev) {
    let body = $('body');
    let sidebarIconOnly = body.hasClass("sidebar-icon-only");
    let sidebarFixed = body.hasClass("sidebar-fixed");
    if (!('ontouchstart' in document.documentElement)) {
      if (sidebarIconOnly) {
        if (sidebarFixed) {
          if (ev.type === 'mouseenter') {
            body.removeClass('sidebar-icon-only');
          }
        } else {
          let $menuItem = $(this);
          if (ev.type === 'mouseenter') {
            $menuItem.addClass('hover-open');
            if ($('.hover-open').length == 2) {
              $($menuItem).find('div.sub_menu').removeClass('d-none');
            }
          } else {
            $menuItem.removeClass('hover-open');
            if(!$menuItem.closest('.sub_menu').length) {
              $('.sub_menu').addClass('d-none');
            }
          }
        }
      }
    }
  });
})(jQuery);