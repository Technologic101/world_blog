/**
 * Some mobile platforms, particularly Android, don't handle
 * initial touch events as hover events. This plugin fixes
 * touch events on Wordpress-based CSS menus by adding a class
 * on touch events, and preventing default behavior. The class must
 * be accomodated in the CSS.
 *
 * Usage:
 *
 * $('.menu').envoyTouchFix();
 * $('.menu').envoyTouchFix('pseudo-hover');
 *
 * @author Tony Chapman (tony@theenvoygroup.com)
 * @copyright 2013 The Envoy Group
 */

(function ($) {
    /**
     * @param  {string} className Touch event class to add to the element
     *                            defaults to "pseudo-hover"
     * @return void
     */
    $.fn.envoyTouchFix = function (className) {
        var className = className || 'pseudo-hover',
            submenuLinks = this.find('ul').siblings('a'),
            allMenuLinks = this.find('a'),
            dottedClassName = ['.', className].join('');

        function removeHover (e) {
            e.preventDefault();
            $(dottedClassName).removeClass(className);
        }

        function handleSubmenuClick (e) {
            if (false === $(this).parent('li').hasClass(className)) {
                e.preventDefault();
                e.stopPropagation();
            }
        }

        submenuLinks.bind('touchstart', function () {
            submenuLinks.on('click', handleSubmenuClick);
        });

        submenuLinks.bind('touchend', function (e) {
            if (false === $(this).parent('li').hasClass(className)) {
                e.preventDefault();
                e.stopPropagation();
            }

            $(dottedClassName).removeClass(className);
            $(this).parent('li').addClass(className);
            $('html').one('touchstart', removeHover);

            submenuLinks.off('click', handleSubmenuClick);
        });

        allMenuLinks.bind('touchstart', function (e) {
            e.stopPropagation();
        });
    };
})(jQuery);
