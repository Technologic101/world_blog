(function ($) {

    /**
     * Fluid element constructor
     * 
     * @param {Object} el
     * @param {Boolean} shouldWrap defaults to false
     * @param {String} wrapper defaults to '<div>'
     * @param {Number} wrapDelay defaults to 250
     */
    function FluidElement (element, shouldWrap, wrapper, wrapDelay) {
        this.element = $(element);
        this.shouldWrap = shouldWrap || false;
        this.wrapper = wrapper || '<div />';
        this.wrapDelay = wrapDelay || 250;
        this.originalWidth = this.element.width();
        this.originalHeight = this.element.height();
        this.aspectRatio = this.calculateAspectRatio();
    }

    FluidElement.prototype = {

        /**
         * @type {Object}
         */
        element: null,

        /**
         * @type {Object}
         */
        parent: null,

        /**
         * @type {Number}
         */
        originalWidth: 0,

        /**
         * @type {Number}
         */
        originalHeight: 0,

        /**
         * @type {Number?}
         */
        aspectRatio: null,

        /**
         * @type {Boolean}
         */
        shouldWrap: false,

        /**
         * @type {String}
         */
        wrapper: '<div />',

        /**
         * @type {Boolean}
         */
        wrapped: false,

        /**
         * @type {Number}
         */
        wrapDelay: 250,

        /**
         * @type {Number}
         */
        wrapDelayId: null,

        /**
         * @return {Number}
         */
        calculateAspectRatio: function () {
            return this.originalWidth / this.originalHeight;
        },

        /**
         * @return {Number}
         */
        calculateNewWidth: function () {
            var parentWidth = Math.round(this.getParent().width());

            if (parentWidth > this.originalWidth) {
                return this.originalWidth;
            }

            return parentWidth;
        },

        /**
         * @return {Number}
         */
        calculateNewHeight: function () {
            return Math.round(this.calculateNewWidth() / this.aspectRatio);
        },

        /**
         * @return void
         */
        onWindowResize: function () {
            this.resizeElement();
            this.maybeWrapElement();
        },

        /**
         * @return void
         */
        resizeElement: function () {
            var el = this.element,
                newWidth = this.calculateNewWidth(),
                newHeight = this.calculateNewHeight();

            el.attr('width', newWidth)
              .attr('height', newHeight);

            el.css({
                width: newWidth,
                height: newHeight
            });
        },

        /**
         * @return void
         */
        maybeWrapElement: function () {
            if (true === this.shouldWrap) {
                clearTimeout(this.wrapDelayId);
                this.wrapDelayId = setTimeout($.proxy(this.onElementWrapDelayTimeout, this), this.wrapDelay);
            }
        },

        /**
         * @return void
         */
        onElementWrapDelayTimeout: function () {
            var el = this.element;

            if (true === this.wrapped) {
                el.unwrap();
                this.wrapped = false;
            } else {
                el.wrap(this.wrapper);
                this.wrapped = true;
            }
        },

        /**
         * @param  {Object} element
         * 
         * @return {Object}
         */
        getParent: function (element) {
            var el;

            if (null === this.parent) {
                if (element) {
                    el = $(element);
                } else {
                    el = this.element.parent();
                }

                if (false === el.is('body') && el.width() > el.parent().width()) {
                    return this.getParent(el.parent());
                }

                this.parent = el;
            }

            return this.parent;
        }
    };

    /**
     * @param {Object} window
     */
    function FluidWindow (window) {
        this.window = window;
    }

    FluidWindow.prototype = {

        /**
         * @type {Object}
         */
        'window': null,

        /**
         * The old width of the window object
         * 
         * @type {Number}
         */
        oldWidth: 0,

        /**
         * Starting operation at less than this number
         * 
         * @type {Number|null}
         */
        startingAt: null,

        /**
         * Collection of fluid elements
         * 
         * @type {Array<FluidElement>}
         */
        fluidElements: [],

        /**
         * @return void
         */
        bind: function () {
            $(this.window).resize($.proxy(this.onWindowResize, this));
        },

        /**
         * Execute window resize
         * 
         * @return {[type]} [description]
         */
        onWindowResize: function () {
            var newWidth = $(this.window).width();

            if (this.startingAt && (this.oldWidth >= this.startingAt && newWidth > this.startingAt)) {
                return;
            }

            if (this.oldWidth != newWidth) {
                $.each(this.fluidElements, function (index, el) {
                    el.onWindowResize();
                });

                this.oldWidth = newWidth;
            }
        }

    };

    var handler = new FluidWindow(window);
    handler.bind();


    /**
     * jQuery Plugin to handle fluid elements
     *
     * Usage: 
     *     $('iframe, object, embed').envoyFluid({
     *         startingAt: 980,
     *         exclude: '.page-178 iframe',
     *         wrap: 'iframe, special',
     *         wrapper: '<div>',
     *         wrapDelay: 250
     *     });
     * 
     * @param  {Object?} options
     *     - @param {Number|null} startingAt the width to start operating at
     *     - @param {String|null} exclude jQuery selector strings to exclude (optional)
     *     - @param {String|null} wrap jQuery selector strings to wrap (optional)
     *     - @param {String} wrapper jQuery string representation of wrap (optional)
     *     - @param {Number} wrapDelay delay in wrapping (optional)
     * 
     * @return void
     */
    $.fn.envoyFluid = function (options) {
        var o = $.extend({
            startingAt: null,
            exclude: null,
            wrap: null,
            wrapper: '<div />',
            wrapDelay: 250
        }, options);

        if (o.exclude) {
            o.exclude = $(o.exclude);
        }

        if (o.wrap) {
            o.wrap = $(o.wrap);
        }

        this.not(o.exclude)
            .each(function (index, element) {
                var el = $(element),
                    shouldWrap = false;

                if (o.wrap && el.is(o.wrap)) {
                    shouldWrap = true;
                }

                if (o.startingAt) {
                    handler.startingAt = parseInt(o.startingAt, 10);
                }

                handler.fluidElements.push(new FluidElement(element, shouldWrap, o.wrapper, o.wrapDelay));
            });
    };

})(jQuery);
