// Don't run this in document.ready due to css loading issues
(function($) {

var envoyMobileOptions = {
    menuTitle: '#menu-main',
    containerTitle: '.menu-main-container',
    subMenuDuplicates: false // If parent menu items with sub-menus have unique links, this will add a copy of the parent link into the submenu
};

var x = new envoy.Menu();
x.render(envoyMobileOptions);

})(jQuery);
