var envoy = envoy || {};

(function($) {
    
    envoy.Menu = function (){
        this.originalMenu = null;
        this.menu = null;
        this.container = null;
        this.marker = null;
        this.currentState = null;
        this.wrapper = null;
        this.navWrap = null;
        this.subMenu = null;
        this.mobileStyleLocation = null;
        this.baseUrl = this.getBaseUrl();
        this.subMenuDuplicates = null;

    }

    envoy.Menu.NORMAL = 'normal';
    envoy.Menu.MOBILE = 'mobile';

    $.extend(envoy.Menu.prototype, {

        /**
         * Constructs necessary elements and attaches events
         */
        render: function(options) {
            this.originalMenu = $(options.menuTitle);
            this.container = $(options.containerTitle);
            this.menu = this.originalMenu.clone();
            this.wrapper = $('<div id="site-wrap" />');
            this.navWrap = $('<div id="mob-nav-wrap" />');
            this.subMenu = this.menu.find('ul.sub-menu');
            this.currentState = envoy.Menu.NORMAL;
            this.subMenuDuplicates = options.subMenuDuplicates;
            this.subMenuButtonsReady = false;
            this.originalMenuDisplay = this.originalMenu.css('display');
            this.containerDisplay = this.container.css('display');

            this.menu.removeAttr('id').attr('id', 'mobile-menu');

            if (this.mobileStyleLocation === null) {
                this.mobileStyleLocation = this.baseUrl+'nav-mobile-small.css';
                $('head').append($('<link rel="stylesheet" type="text/css" href="'+this.mobileStyleLocation+'" />'));
            }

            $(window).on('load', $.proxy(this.checkWidth, this));
            $(window).on('resize', $.proxy(this.checkWidth, this));
        },

        /**
         * Wraps entire site content in a container
         * div
         */
        createWrapper: function() {
            var div = this.wrapper.get(0);
        
            // Move the body's children into this wrapper
            while (document.body.firstChild) {
                div.appendChild(document.body.firstChild);
            }

            // Append the wrapper to the body
            document.body.appendChild(div);
        },

        /**
         * Removes content from container and detaches it
         */
        removeWrapper: function() {
            var div = this.wrapper.get(0);

            while (div.firstChild) {
                document.body.appendChild(div.firstChild);
            }

            document.body.removeChild(div);
        },
        
        /**
         * At less than 481px, wrap the body in div#site-wrap
         */
        removeMenu: function(){
            var self = this;
            this.originalMenu.css('display', 'none');
            
            this.createWrapper();
            this.navWrap.append(this.menu);
            if (this.subMenuDuplicates) {this.addDuplicates();}
            this.addBackButton();
            $('body').prepend(this.navWrap);
            
            if (this.container) {
                this.container.css('display', 'none');
            }
            
            this.subMenu.siblings().on('click.EnvoyMobile', function(e) {
                var menu = $(this).siblings('ul.sub-menu');
                e.preventDefault();
                self.slideLeft(menu);
                self.setHeight(menu);
                self.testMenuHeight(menu);
            });
        },

        replaceMenu: function() {
            this.originalMenu.css('display', this.originalMenuDisplay);

            if (this.container) {
                this.container.css('display', this.containerDisplay);
            }
            
            if (this.subMenuDuplicates) { this.removeDuplicates(); }
            this.removeBackButton();
            this.navWrap.remove();
        },

        /**
         * Add the 'back' button to any submenus
         */
        addBackButton: function() {
            var self = this;
            self.subMenu.each( function(){
                var button = $("<li class='back-button'><a href='#'>Back&nbsp;&laquo;</a></li>");
                $(this).prepend(button);
                button.on('click.EnvoyMobile', function() {
                    var subMenu = $(this).closest('ul.sub-menu');
                    self.slideRight(subMenu);
                    self.setHeight(subMenu.closest('ul.menu'));
                    self.testMenuHeight(self.menu);
                });
            });
        },

        /**
         * Remove the button for larger screens
         */

        removeBackButton: function(){
            var button = $('.back-button');
            button.off('.EnvoyMobile');
            button.remove();
        },
        
        testMenuHeight: function(item) {
            if (item.height() > $(window).height()) {
                this.navWrap.css('position', 'absolute');
            } else {
                this.navWrap.css('position', 'fixed');
            }
        },
        
        /**
        *Functions for sub-menu styling and animation
        */

        slideLeft: function(x) {
            x.css('display', 'block');
            x.animate({
                left: 0,
                opacity: 1
            });
        },

        slideRight: function(x) {
            x.animate({
                left: '100%',
                opacity: '.25'
            },
            function () {
                x.css('display', 'none');
            });
        },
        
        setHeight: function(item) {
            this.wrapper.animate({
                top: item.height() + 'px'
            });
        },
        
        getBaseUrl: function() {
            var url = $('script[src*=mobile-nav]').attr('src');
            var array = url.split('/');
            url = url.replace( array.pop(), '');
            return url;
        },
        
        addDuplicates: function() {
            this.subMenu.each( function () {
                var self = $(this);
                var clone = self.closest('li').clone();
                self.prepend(clone);
                clone.attr('class', 'duplicate');
                clone.find('ul.sub-menu').remove();
            });
        },
        
        removeDuplicates: function() {
            $('.duplicate').remove();
        },
        
        // Primary resize function
        
        checkWidth: function() {
            // Check to see if the browser width is 480 or less
            if (($(window).width() < 481) && (this.currentState != envoy.Menu.MOBILE)) {
                
                this.removeMenu();

                if (!this.subMenuButtonsReady) {
                    var menu = this.menu;

                    menu.find('ul.sub-menu').closest('li').each(function () {
                        $(this).children('a').prepend('&raquo;&nbsp;');
                    });
                    this.subMenuButtonsReady = true;
                }

                this.testMenuHeight(this.menu);

                this.wrapper.css({
                    top: this.navWrap.height() + 'px'
                });
                
                // Scroll the window the height of mobileNavHeight [animation set at 0 to hide the navigation immediately]
                $('html, body').stop().animate({
                    'scrollTop': this.wrapper.offset().top
                }, 0, 'swing', ((function () {
                    window.location.hash = '#' + this.wrapper.attr('id');
                }).call(this)));

                this.currentState = envoy.Menu.MOBILE;
            
            // Check to see if the browser width is more than 480px wide
            } else if (($(window).width() > 480) && (this.currentState != envoy.Menu.NORMAL)) {
                this.replaceMenu();
                
                this.wrapper.css({
                    // Make sure the top value of #site is 0 so we don't see the space for the mobile nav
                    top: 0 + 'px'
                });

                // Scroll to the absolute top of the page
                $(window).scrollTop(0); //no need for $.animate, on desktop browser it works just fine
                this.removeWrapper();
                this.subMenu.removeAttr('style');
                this.currentState = envoy.Menu.NORMAL;
            }
        
        }
    });
})(jQuery);
