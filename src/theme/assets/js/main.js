(function($, undefined) {
    "use strict";
    /**
     * Shared variables
     */

    var ua = navigator.userAgent.toLowerCase(),
        platform = navigator.platform.toLowerCase(),
        $window = $(window),
        $document = $(document),
        $html = $('html'),
        $body = $('body'),

        
        android_ancient = (ua.indexOf('mozilla/5.0') !== -1 && ua.indexOf('android') !== -1 && ua.indexOf('applewebKit') !== -1) && ua.indexOf('chrome') === -1,
        apple = ua.match(/(iPad|iPhone|iPod|Macintosh)/i),
        webkit = ua.indexOf('webkit') != -1,

        isiPhone = false,
        isiPod = false,
        isAndroidPhone = false,
        android = false,
        iOS = false,
        isIE = false,
        ieMobile = false,
        isSafari = false,
        isMac = false,
        isWindows = false,
        isiele10 = false,

        firefox = ua.indexOf('gecko') != -1,
        safari = ua.indexOf('safari') != -1 && ua.indexOf('chrome') == -1,

        is_small = $('.js-nav-trigger').is(':visible'),

        windowHeight = $window.height(),
        windowWidth = $window.width(),
        documentHeight = $document.height(),
        orientation = windowWidth > windowHeight ? 'portrait' : 'landscape',

        filmWidth,
        contentWidth,
        sidebarWidth,

        latestKnownScrollY = window.scrollY,
        latestKnownScrollX = window.scrollX,

        latestKnownMouseX = 0,
        latestKnownMouseY = 0,

        latestDeviceAlpha = 0,
        latestDeviceBeta = 0,
        latestDeviceGamma = 0,

        ticking = false,
        horToVertScroll = false,

        globalDebug = false,
        $reviewsParent = null;



    $('.js-widget-gallery').magnificPopup({
        delegate: '.listing-gallery__item', // child items selector, by clicking on it popup will open
        type: 'image',
        image: {
            titleSrc: function(item) {
                var output = '';

                output += item.el.find('img').attr('caption');
                output += '<span class="mfp-description">' + item.el.find('img').attr('description') + '</span>';

                return output;
            }
        },
        gallery: {
            enabled: true,
            tCounter: '<span class="mfp-counter">%curr%/%total%</span>',
            arrowMarkup: '<div class="gallery-arrow  gallery-arrow-%dir%  is--ready">' + $('.arrow-icon-svg').html() + '</div>'
        }
    });

    $('.listing-gallery__all').on('click', function(e) {
        e.preventDefault();
        $('.js-widget-gallery').magnificPopup('open');
    });





    function platformDetect() {

        var isIE = typeof(is_ie) !== "undefined" || (!(window.ActiveXObject) && "ActiveXObject" in window),
            isiele10 = ua.match(/msie (9|([1-9][0-9]))/i),
            isie9 = ua.match(/msie (9)/i);

        iOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;

        if (isIE) {
            $html.addClass('is--ie');
        }

        if (isiele10) {
            $html.addClass('is--iele10');
        }

        if (isie9) {
            $html.addClass('is--ie9');
        }

        if (/Edge\/12./i.test(navigator.userAgent)) {
            $html.addClass('is--edge');
        }

        if (iOS) {
            $html.addClass('is--ios');
        }
    }
    // /* ====== ON DOCUMENT READY ====== */

    $(document).ready(function() {
        init();

        // [name] is the name of the event "click", "mouseover", ..
        // same as you'd pass it to bind()
        // [fn] is the handler function
        $.fn.bindFirst = function(name, selector, fn) { 
            // bind as you normally would
            // don't want to miss out on any jQuery magic
            this.on(name, selector, fn);

            // Thanks to a comment by @Martin, adding support for
            // namespaced events too.
            this.each(function() {
                var handlers = $._data(this, 'events')[name.split('.')[0]];
                // take out the handler we just inserted from the end
                var handler = handlers.pop();
                // move it at the beginning
                handlers.splice(0, 0, handler);
            });
        };

        $('.job_filters').bindFirst('click', '.reset', function() {
            $('.active-tags').empty();
            $('.tags-select').find(':selected').each(function(i, obj) {
                $(obj).attr('selected', false);
            });
            $('.tags-select').trigger("chosen:updated");

            $('input[name="search_keywords"]').each(function(i, obj) {
                $(obj).val('').trigger('chosen:updated');
            });
        });

    }); 


    function customizerOptionsPadding() {
        var $updatable = $('.page-listings .js-header-height-padding-top'),
            $map = $('.map'),
            $jobFilters = $(' .job_filters .search_jobs div.search_location'),
            $findMeButton = $('.findme'),
            headerHeight = $('.site-header').outerHeight();

        // set padding top to certain elements which is equal to the header height

        $updatable.css('paddingTop', '');
        $updatable.css('paddingTop', headerHeight);

        if ($('#wpadminbar').length) {
            headerHeight += $('#wpadminbar').outerHeight();
        }

        $map.css('top', headerHeight);
        $jobFilters.css('top', headerHeight);
        $findMeButton.css('top', headerHeight + 70);
    }

    function init() {
        platformDetect();
        browserSupport();
        browserSize();

        eventHandlers();

        var headerPaddingBottom = parseInt($('.site-header').css('paddingTop')) + $('.secondary-menu').outerHeight();
        $('.site-header').css('paddingBottom', headerPaddingBottom);

        customizerOptionsPadding();

        $('html').addClass('is--ready');

        var $email = $('input#account_email'),
            $target = $('.field.account-sign-in'), 
            $fieldset;

        if ($email.length && $target.length) {
            $fieldset = $email.closest('fieldset');
            $email.insertAfter($target);
            $fieldset.remove();
        }

        var $uploader = $('.wp-job-manager-file-upload');

        $uploader.each(function(i, obj) {
            var $input = $(obj),
                id = $(obj).attr('id'),
                $label = $('label[for="' + id + '"]'),
                $btn = $('<div class="uploader-btn"><div class="spacer"><div class="text">' + BitcoinParams.strings['wp-job-manager-file-upload'] + '</div></div></div>').insertAfter($input);

            $btn.on('click', function() {
                $label.trigger('click'); 
            });
        });

        $('#main_image').on('change', function(e) {
            var self = this;
            var this_logo = $('#company_logo').val();

            if (this_logo === '') {
                var url = self.value;
            }
        });

        if ($('#job_preview').length) {
            $body.addClass('single-job_listing single-job_listing_preview').removeClass('page-add-listing');
            $('.page').removeClass('page');
            $('.listing-map').css({
                display: '',
                height: ''
            });
            singleListingMapHeight();
            $window.trigger('pxg:refreshmap');
            $('#job_preview').css('opacity', 1);
        }

        $('.btn--filter').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            if ($body.hasClass('show-filters')) {
                $window.scrollTop(0);
            }
            $body.toggleClass('show-filters');
        });

        $('.btn--view').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $body.toggleClass('show-map');
            $('html, body').scrollTop(0);
            setTimeout(function() {
                $window.trigger('pxg:refreshmap');
            });
        });

        if ($('#job_package_selection').length) {
            $body.addClass('page-package-selection');

            var $nopackages = $('.no-packages');

            if ($nopackages.length) {
                var $form = $nopackages.closest('#job_package_selection');

                if ($form.length) {
                    $nopackages.insertAfter($form);
                    $form.remove();
                }
            }
        }

        detectLongMenu();
        moveListingStickySidebar();
        singleListingMapHeight();
        moveSingleListingReviews();
        moveSingleListingClaimWidget();

        if ($('.search-field-wrapper.has--menu').length) {
            searchSuggestionsTrigger();
        }

        $reviewsParent = $('.widget_listing_comments').parent();

        $('.showlogin').off('click').on('click', function() {
            $('.login-container').slideToggle();
        });
    }
     

    const Sliders = (function($){
        const defaultSettings = {
            container: '.js-gallery__slider'
        }

        function init(options = {}){
            let settings = new Object;
            $.extend(settings, defaultSettings, options);
            const slidersContainer = settings.container instanceof Element ? settings.container : document.querySelectorAll(settings.container);

            Array.from(slidersContainer).map( slider => {
                imagesLoaded(slider, function(){
                    $(slider).slick();
                })
            })

        }

        return {
            init: init
        }

    })(jQuery)


    
    const Likes = (function($) {
        function init(){
            var stop = false; 
            $(".likes-count").click(function(event) {
                event.preventDefault();
                if(stop) return
                var $this = $(this);
                stop = true;
                $.post(BitcoinParams.ajax.url, {
                    action: BitcoinParams.ajax.likes_action,
                    ID: $this.data("post-id")
                }).done(function(response) {
                    stop = false
                if (response.data.liked) {
                    $this.addClass("likes-count--active");

                    var n = $this.find(".likes-count__number").text() || 0;

                    if (n) $this.find(".likes-count__number").text(parseInt(n) + 1);
                } else {
                    $this.removeClass("likes-count--active");

                    var n = $this.find(".likes-count__number").text() || 0
                    if (n) $this
                        .find(".likes-count__number")
                        .text(parseInt(n) <= 0 ? 0 : parseInt(n) - 1);
                }
                });
            });
        }

        return {
            init: init
        }
    })(jQuery);


    const Share = (function($) {
        function init(){
            var shareServices = {
                twitter: function() {
                    window.open('http://twitter.com/intent/tweet?text=' + jQuery("h2.text-title").text() + ' ' + window.location,
                        "twitterWindow",
                        "width=650,height=350");
                    return false;
                },

                // Facebook

                facebook: function(){
                    window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(location.href),
                        'facebookWindow',
                        'width=650,height=350');
                    return false;
                },

                // Pinterest

                pinterest: function(){
                    window.open('http://pinterest.com/pin/create/bookmarklet/?description=' + jQuery("h2.text-title").text() + ' ' + encodeURIComponent(location.href),
                        'pinterestWindow',
                        'width=750,height=430, resizable=1');
                    return false;
                },

                // Google Plus

                google: function(){
                    window.open('https://plus.google.com/share?url=' + encodeURIComponent(location.href),
                        'googleWindow',
                        'width=500,height=500');
                    return false;
                }
            }

            $('.entry-share__links').on('click', 'a', function(e){
                e.preventDefault();
                var $href = $(e.currentTarget),
                    service = $href.data('share');

                if( typeof shareServices[service] === 'function'){
                    shareServices[service]();
                }
            })


        }
        
        return {
            init: init
        }
    })(jQuery);

    const BitcoinPlot = (function($, Highcharts) {
        
        var allTimeData = [], threeMonth = [], oneYear = [], sevenDay = [], oneMonth = [];  

        function getMonthName (month){
            let months = ['Jan','Feb','Mrch','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            return months[ month || 0 ];
        }
          

        function printPlot(data, el){


            Highcharts.chart('bitcoin-plot', {
                chart: {
                    zoomType: 'x',
                    backgroundColor: 'transparent',
                },
                title: {
                    text: ''
                },
                subtitle: {
                    text: ''
                },
                xAxis: {
                    visible: false,
                },
                yAxis: {
                    visible: false
                },
                legend: {
                    enabled: false
                },
                plotOptions: {
                    area: {
                        fillColor: {
                            linearGradient: {
                                x1: 0,
                                y1: 1,
                                x2: 0,
                                y2: 0
                            },
                            stops: [
                                [0, 'rgba(255,255,255,0)'],
                                [1, 'rgba(255,255,255,0.5)']
                            ]
                        },
                        marker: {
                            radius: 2
                        },
                        lineWidth: 1.5,
                        states: {
                            hover: {
                                lineWidth: 2
                            }
                        },
                        threshold: null
                    },
                    series: {
                        lineColor: 'rgba(255,255,255, .6)',
                        dataLabels: {
                            enabled: true,
                            backgroundColor: 'transparent',
                            borderColor: 'transparent',
                            formatter: function() {
                                if (this.point.x == this.series.data.length - 1 || this.point.x == 0) {
                                    let lPrice = String.prototype.split.call(this.y,'').length,
                                        fPrice= String.prototype.substring.call(this.y, lPrice - 3, 0),
                                        sPrice =  String.prototype.substr.call(this.y, -3),
                                        time = new Date(this.key);
                                    return '<tspan class="bitcoin-shortcode__plot-time">' +  getMonthName(time.getMonth())  + ' ' +time.getFullYear() + '</tspan> <br/> <tspan dy="20" class="bitcoin-shortcode__plot-price">' + this.series.name + ( lPrice > 3 ? fPrice + ' ' : '' )  + sPrice + '</tspan>';
                                } else {
                                    return null;
                                }
                            },
                            style: {
                                color: '#FFFFFF',
                                textOutline: 'none'
                            },
                        }
                    }
                },
                    
                tooltip: {
                    pointFormat: "${point.y:.2f}",
            
                    formatter: function() {
                        var time = new Date(this.key);
                        return '<p class="bitcoin-shortcode__plot-time">' + time.getDate() + ' ' + getMonthName(time.getMonth())  + ' ' +time.getFullYear() + '</p> <br/> <p dy= "20" class="bitcoin-shortcode__plot-price">' + this.series.name + this.y + '</p>';
                    },
                    backgroundColor: 'rgba(255, 255, 255, 1)',
                    borderWidth: 1,
                    style: {
                        color: '#a0b0bb',
                        textOutline: 'none'
                    },
                    borderRadius: 4,
                    borderColor: '#fff',
                    shape: 'rect'
                },
                series: [{
                    type: 'area',
                    name: '$',
                    data: data
                }]
            });

            Array.from($('.bitcoin-shortcode__plot-price')).forEach(el => $(el).attr('dy','20'));

        }

        function bindActions(context){
            var canGo = true
            $('.bitcoin-shortcode__plot-change__timeframe', context).on('click touchend', function(e){
                e.preventDefault();
                if(!canGo) return -1
                canGo =false;
                setTimeout(() => {canGo = true;}, 1000);

                $('.bitcoin-shortcode__plot-change__timeframe', context).removeClass('active');

                $(this).addClass('active');
                var timeframe = $(this).data('timeframe');
                switch (timeframe) {
                    case 'all':
                        printPlot(allTimeData);
                        break;
                    case '7day':
                        printPlot(sevenDay);
                        break;
                    case '1month':
                        printPlot(oneMonth);
                        break;
                    case '3month':
                        printPlot(threeMonth);
                        break;
                    case '1year':
                        printPlot(oneYear);
                        break;
                    default:
                        printPlot(allTimeData);
                        break;
                }
                


            })
        }

        function getData(context){
            $.getJSON( "https://index.bitcoin.com/api/v0/history?span=all",  function( data ) {
                var last = 0, now = 0, 
                    maxLenght = 800, 
                    initialLenght =  data.length, all =0,
                    redundant = (data.length - maxLenght);


                if (initialLenght > maxLenght ){
                    for(var i = 0; i < initialLenght-1; i++){
                        
                        now = Math.round(redundant*i*i*i/initialLenght/initialLenght/initialLenght);
                        data.splice(i - now , now-last);
                        data[i- now][1] = Math.round(data[i- now][1] / 100);
                        last = now;
                        
                    }
                }
                sevenDay  = data.slice(0, 7).reverse();
                oneMonth  = data.slice(0, 1*31).reverse();
                threeMonth  = data.slice(0, 3*31).reverse();
                oneYear  = data.slice(0, 1*365).reverse();
                allTimeData = data.reverse();
                printPlot(allTimeData, context);

                bindActions(context);
            });
        }

        
        function init(){
            Array.from($('.bitcoin-shortcode__plot')).forEach(element => {
                getData(element);
            })
        }

        return {
            init: init
        }
    })(jQuery, Highcharts);



    var Coinmarketcap = (function($){
        function init() {
            Array.from($('#coinmarketcap')).forEach(element => {
                Array.from($(element).find('> li')).map(el => {
                    let url = new URL($(el).data('url'));
                    fetch(url.href, {
                        method: 'GET',
                        mode: 'cors'
                    }).then(res => res.json()).then( data => {
                        $(el).find('.widget-coinmarketcap__price').text(data[0].price_usd +  " $");
                        $(el).find('.widget-coinmarketcap__name').text(data[0].name);
                    });
                })
            })
        }

        return {
            init: init
        }

    })(jQuery);



    var BitcoinSlider = (function($){
        function init() {
            var container = (defaultOptions.container instanceof $)? defaultOptions.container : $(defaultOptions.container) ;
            container.each(function(i){
                initialize(this);
            });
        };

        var defaultOptions = {
            container : '.bitcoin__slider',
        };
        
        function initialize($container){
            var options = Object.assign({},defaultOptions, _getHTMLdata($container));
            options.container = $container;
            $( '.bitcoin__slider-in', options.container ).imagesLoaded( (function() {

                $( '#' + options.id ).slick({
                    slidesToShow: options.slidesToShow || 1,
                    slidesToScroll: options.slidesToScroll || 1,
                    dots: options.dots || false,
                    centerMode: options.centerMode || false,
                    focusOnSelect: options.focusOnSelect || false,
                    arrows: options.arrows || false,
                    adaptiveHeight: options.adaptiveHeight || false,
                    autoplay: options.autoplay || false,
                    autoplaySpeed :options.autoplaySpeed || 3000,
                    centerPadding : options.centerPadding + 'px' || '50px',
                    draggable : options.draggable || true,
                    fade : options.fade || false,
                    variableWidth : options.variableWidth || false,
                    vertical: options.vertical || false,
                    speed: options.speed || 300,
                    responsive: [
                        {
                            breakpoint: 1024,
                            settings: {
                                slidesToShow: Math.min(3,options.slidesToShow),
                                slidesToScroll: 1
                            }
                        },
                        {
                            breakpoint: 767,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1
                            }
                        }
                    ]
                });
            }));


        }

        function _getHTMLdata($el){
            $el = ($el instanceof $)? $el : $($el) ;
            var data = $el.data('slider'),
            options = {};
            if( typeof data === 'object' ){
                for (var key in data){
                    if (data.hasOwnProperty(key)) {
                        var val = data[key];
                        if( $.isNumeric(data[key])  )
                            val = parseInt(data[key]);
                        if( data[key] == 'yes')
                            val = true;
                        options[key] = val;
                    }
                }
            }
            else
                console.log(" Incorrect HTML Data ");

            return options
        }

        return {
            init: init
        }

    })(jQuery);

    // /* ====== ON WINDOW LOAD ====== */
    $window.load(function() {
        $('html').addClass('is--loaded');
 
        Sliders.init();
        Likes.init();
        Share.init();
        BitcoinPlot.init();
        Coinmarketcap.init();
        BitcoinSlider.init();

        tooltipTrigger();
        keepSubmenusInViewport(); 
        $('.js-menu-trigger').on('touchstart click', toggleMenu);

        if (windowWidth < 900) {
            HandleSubmenusOnTouch.initSidebarMenu();
        } else {
            // HandleSubmenusOnTouch.initHorizontalMenu();
        }

        if ($('.site-header .search-form').is(':visible')) {
            handleMobileHeaderSearch();
        }
        

        frontpageVideoInit();

        loginWithAjaxHandlers();

        var $featuredVideo = $('.entry-featured video');
        if ($featuredVideo.length) {
            makeVideoPlayableInline($featuredVideo.get(0), /* hasAudio */ false);

            $body.one('touchstart', function() {
                $featuredVideo.get(0).play();
            })
        }

        $window.trigger('pxg:refreshmap');
    });

    // /* ====== ON RESIZE ====== */

    function requestTick() {
        ticking ? ticking = true : requestAnimationFrame(update);
    }

    function update() {
        // do stuff
        ticking = false;
    }

    function eventHandlers() {
        $window.on('debouncedresize', function() {
            browserSize();
            detectLongMenu();
            moveListingStickySidebar();
            singleListingMapHeight();
            moveSingleListingReviews();

            customizerOptionsPadding();

            setTimeout(function() {
                $window.trigger('update:map');
                $window.trigger('pxg:refreshmap');
            });

            if (Modernizr.touchevents) {
                if (windowWidth < 900) {
                    HandleSubmenusOnTouch.initSidebarMenu();
                } else {
                    // HandleSubmenusOnTouch.initHorizontalMenu();
                }
            }
        });

        $window.on('scroll', function() {
            latestKnownScrollY = $window.scrollTop();
            latestKnownScrollX = $window.scrollLeft();
            // requestTick();
        });

        $(window).on('mousemove', function(e) {
            latestKnownMouseX = e.clientX;
            latestKnownMouseY = e.clientY;
            // requestTick();
        });

        $(window).on('deviceorientation', function(e) {
            latestDeviceAlpha = e.originalEvent.alpha;
            latestDeviceBeta = e.originalEvent.beta;
            latestDeviceGamma = e.originalEvent.gamma;
            // requestTick();
        });

        handleHiddenFacets();
        handleLongSubMenus();
        hideCategoryDescription();

        // After FacetWP fetches new items,
        // scroll listings page to top to see
        // all new loaded items.
        if ($body.is('.page-listings')) {
            $(document).on('facetwp-loaded', function() {
                TweenLite.to(window, 1, {
                    scrollTo: 0
                });
            });
        }
    }
    /* ====== HELPER FUNCTIONS ====== */


    /**
     * Detect what platform are we on (browser, mobile, etc)
     */

    function browserSupport() {
        $.support.touch = 'ontouchend' in document;
        $.support.svg = (
            document.implementation.hasFeature("http://www.w3.org/TR/SVG11/feature#BasicStructure", "1.1")
        ) ? true : false;
        $.support.transform = getSupportedTransform();

        $html
            .addClass($.support.touch ? 'touch' : 'no-touch')
            .addClass($.support.svg ? 'svg' : 'no-svg')
            .addClass(!!$.support.transform ? 'transform' : 'no-transform');
    }

    function browserSize() {
        windowHeight = $window.height();
        windowWidth = $window.width();
        documentHeight = $document.height();
        orientation = windowWidth > windowHeight ? 'portrait' : 'landscape';
    }

    function getSupportedTransform() {
        var prefixes = ['transform', 'WebkitTransform', 'MozTransform', 'OTransform', 'msTransform'];
        for (var i = 0; i < prefixes.length; i++) {
            if (document.createElement('div').style[prefixes[i]] !== undefined) {
                return prefixes[i];
            }
        }
        return false;
    }

    /**
     * Handler for the back to top button
     */
    function scrollToTop() {
        $('a[href="#top"]').click(function(event) {
            event.preventDefault();
            event.stopPropagation();

            TweenMax.to($(window), 1, {
                scrollTo: {
                    y: 0,
                    autoKill: true
                },
                ease: Power3.easeOut
            });
        });
    }

    /**
     * function similar to PHP's empty function
     */

    function empty(data) {
        if (typeof(
                data
            ) == 'number' || typeof(
                data
            ) == 'boolean') {
            return false;
        }
        if (typeof(
                data
            ) == 'undefined' || data === null) {
            return true;
        }
        if (typeof(
                data.length
            ) != 'undefined') {
            return data.length === 0;
        }
        var count = 0;
        for (var i in data) {
            // if(data.hasOwnProperty(i))
            //
            // This doesn't work in ie8/ie9 due the fact that hasOwnProperty works only on native objects.
            // http://stackoverflow.com/questions/8157700/object-has-no-hasownproperty-method-i-e-its-undefined-ie8
            //
            // for hosts objects we do this
            if (Object.prototype.hasOwnProperty.call(data, i)) {
                count++;
            }
        }
        return count === 0;
    }

    function toggleMenu(e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }

        $('body').toggleClass('nav-is-open');

        $('body').toggleClass('overlay-is-open');

        if ($('body').hasClass('overlay-is-open')) {
            $('body').width($('body').width());
            $('body').css('overflow', 'hidden');
        } else {
            $('body').removeAttr('style');
        }
    }

    function closeMenu() {
        $('body').removeClass('nav-is-open');
        $('body').removeClass('overlay-is-open');
        $('body').removeAttr('style');
    }

    // Set the height of the single listing map
    function singleListingMapHeight() {
        if (windowWidth > 900) {
            var $listingMap = $('.listing-sidebar--top .widget_listing_sidebar_map:first-child .listing-map');

            if ($('.entry-featured-image').length && $listingMap.length) {
                var featuredTop = $('.entry-featured-image').offset().top;
                var featuredHeight = $('.entry-featured-image').height();
                var featuredBottom = featuredTop + featuredHeight;
                var mapFeaturedDistance = $listingMap.offset().top - featuredBottom + 1;
                var headerHeight = $('.single_job_listing .entry-header').outerHeight();
                var mapComputedHeight = headerHeight - mapFeaturedDistance;
                $listingMap.height(mapComputedHeight);

                $window.trigger('pxg:refreshmap');
            }
        }
    }


    // Move listing sticky sidebar under header on mobile
    function moveListingStickySidebar() {

        var $sidebarTop = $('.listing-sidebar--top'),
            $sidebarBottom = $('.listing-sidebar--bottom'),
            isTop = $sidebarTop.data('isTop');

        if (!$sidebarTop.length) {
            return;
        }

        if (windowWidth < 900) {
            if (isTop !== true) {
                $sidebarTop.insertAfter($('.entry-header'));
                isTop = true;
            }
        } else {
            if (isTop !== false) {
                $sidebarTop.insertBefore($sidebarBottom);
                isTop = false;
            }
        }

        $sidebarTop.data('isTop', isTop);
    }

    // When there's a long menu, prevent it from breaking on two lines
    function detectLongMenu() {
        if (windowWidth > 900) {
            var $menuWrapper = $('.menu-wrapper');

            if ($menuWrapper.find('ul:first-of-type').height() > $menuWrapper.height()) {
                $menuWrapper.addClass('has--long-menu');
            }

            if ($menuWrapper.find('ul:first-of-type').width() < $menuWrapper.width()) {
                $menuWrapper.removeClass('has--long-menu');
            }
        }
    }

    function tooltipTrigger() {
        $('.js-tooltip-trigger').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            $(this).parent().toggleClass('active');

        });
    }

    function searchSuggestionsTrigger() {
        $('.js-search-suggestions-field').on('focus', function(e) {
            $('.js-search-form').addClass('is--active');
        });

        $('.js-search-suggestions-field').on('blur', function(e) {
            setTimeout(function closeSearchSuggestions() {
                $('.js-search-form').removeClass('is--active');
            }, 150);
        });

        $('.js-search-form').on('click', function(e) {
            if (e.target.id != 'search_keywords') {
                $('.js-search-suggestions-field').blur();
                $('.js-search-form').removeClass('is--active');
            }
        });
    }

    function handleMobileHeaderSearch() {
        // When clicking on search icon, show the search input
        $('.js-search-trigger-mobile').on('click', toggleHeaderSearch);

        // When the search input loses focus, hide the input
        $('.js-search-mobile-field').on('blur', function(e) {
            setTimeout(function closeMobileSearch() {
                $('.js-search-form').removeClass('is--active');
            }, 150);
        });

        function toggleHeaderSearch(e) {
            e.preventDefault();
            e.stopPropagation();

            if (!$('.js-search-form').hasClass('is--active')) {
                $('.js-search-form').addClass('is--active');
                $('.js-search-mobile-field').focus();
            }
        }
    }

    // Detect the submenus that exceed the viewport
    // and add a class to make them open vertically
    function keepSubmenusInViewport() {
        if ($('.primary-menu').length) {
            var headerRightmost = $('.site-header').outerWidth();

            $('.sub-menu').each(function() {
                var submenuRightmost = $(this).offset().left + $(this).width();

                // if the sub menu exceeds primary menu's rightmost edge
                if (submenuRightmost > headerRightmost) {
                    $(this).addClass('is--forced-placed');

                    $(this).find('.sub-menu').addClass('is--forced-placed');
                }
            });
        }
    }

    function moveSingleListingReviews() {
        // On mobile, when focusing on a review field, the keyboard appears thus
        // triggering a resize. When a resize is triggered, trying to move the
        // reviews causes fields to lose focus, hiding the keyboard. Prevent that.
        if (Modernizr.touchevents && $('input, textarea').is(':focus')) {
            return;
        }

        if ($('.widget_listing_comments').length) {
            if (windowWidth < 900) {
                if ($('.widget_listing_comments').parent().hasClass('column-sidebar')) {
                    return;
                }

                $('.widget_listing_comments').appendTo($('.column-sidebar'));
            } else {
                $('.widget_listing_comments').appendTo($reviewsParent);
            }
        }
    }

    function moveSingleListingClaimWidget() {
        var $claimWidget = $('.listing-sidebar--bottom .widget_listing_sidebar_claim_listing');

        if ($claimWidget.length) {
            var $parentSidebar = $claimWidget.parent();

            $claimWidget.each(function() {
                if ($(this).is(':first-of-type')) {
                    $(this).insertBefore($parentSidebar).addClass('is--independent');
                } else if ($(this).is(':last-of-type')) {
                    $(this).insertAfter($parentSidebar).addClass('is--independent');
                }
            });
        }
    }

    var bitcoinDocumentCookies = {
        getItem: function(sKey) {
            if (!sKey) {
                return null;
            }
            return decodeURIComponent(document.cookie.replace(new RegExp("(?:(?:^|.*;)\\s*" + encodeURIComponent(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=\\s*([^;]*).*$)|^.*$"), "$1")) || null;
        },
        setItem: function(sKey, sValue, vEnd, sPath, sDomain, bSecure) {
            if (!sKey || /^(?:expires|max\-age|path|domain|secure)$/i.test(sKey)) {
                return false;
            }
            var sExpires = "";
            if (vEnd) {
                switch (vEnd.constructor) {
                    case Number:
                        sExpires = vEnd === Infinity ? "; expires=Fri, 31 Dec 9999 23:59:59 GMT" : "; max-age=" + vEnd;
                        break;
                    case String:
                        sExpires = "; expires=" + vEnd;
                        break;
                    case Date:
                        sExpires = "; expires=" + vEnd.toUTCString();
                        break;
                }
            }
            document.cookie = encodeURIComponent(sKey) + "=" + encodeURIComponent(sValue) + sExpires + (
                sDomain ? "; domain=" + sDomain : ""
            ) + (
                sPath ? "; path=" + sPath : ""
            ) + (
                bSecure ? "; secure" : ""
            );
            return true;
        },
        removeItem: function(sKey, sPath, sDomain) {
            if (!this.hasItem(sKey)) {
                return false;
            }
            document.cookie = encodeURIComponent(sKey) + "=; expires=Thu, 01 Jan 1970 00:00:00 GMT" + (
                sDomain ? "; domain=" + sDomain : ""
            ) + (
                sPath ? "; path=" + sPath : ""
            );
            return true;
        },
        hasItem: function(sKey) {
            if (!sKey) {
                return false;
            }
            return (
                new RegExp("(?:^|;\\s*)" + encodeURIComponent(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=")
            ).test(document.cookie);
        },
        keys: function() {
            var aKeys = document.cookie.replace(/((?:^|\s*;)[^\=]+)(?=;|$)|^\s*|\s*(?:\=[^;]*)?(?:\1|$)/g, "").split(/\s*(?:\=[^;]*)?;\s*/);
            for (var nLen = aKeys.length, nIdx = 0; nIdx < nLen; nIdx++) {
                aKeys[nIdx] = decodeURIComponent(aKeys[nIdx]);
            }
            return aKeys;
        }
    };

    function frontpageVideoInit() {
        // video resizing
        var $wrapper = $('.page-template-front_page .entry-header .wp-video'),
            $video = $('.page-template-front_page .entry-header .mejs-video'),
            $header,
            $featured,
            videoWidth,
            videoHeight,
            headerWidth,
            headerHeight,
            newWidth,
            newHeight;

        function stretch() {

            if ((
                    videoWidth / videoHeight
                ) > (
                    headerWidth / headerHeight
                )) {
                newHeight = headerHeight;
                newWidth = newHeight * videoWidth / videoHeight;
            } else {
                newWidth = headerWidth;
                newHeight = newWidth * videoHeight / videoWidth;
            }

            $wrapper.css({
                width: newWidth,
                height: newHeight
            });
        }

        if ($wrapper.length) {
            $header = $('.page-template-front_page .entry-header');
            $featured = $('.page-template-front_page .entry-featured');
            videoWidth = $video.outerWidth();
            videoHeight = $video.outerHeight();
            headerWidth = $header.outerWidth();
            headerHeight = $header.outerHeight();

            $wrapper.find('video').prop('muted', true)

            stretch();
            $wrapper.addClass('is--stretched').data('ar', newWidth / newHeight);

            $window.on('debouncedresize', function() {
                headerWidth = $header.outerWidth();
                headerHeight = $header.outerHeight();
                stretch();
            });

        }
    }

    // iOS Multiple Select Bug Fix
    if (navigator.userAgent.match(/iPhone/i)) {
        $('select[multiple]').each(function() {
            var select = $(this).on({
                "focusout": function() {
                    var values = select.val() || [];
                    setTimeout(function() {
                        select.val(values.length ? values : ['']).change();
                    }, 1000);
                }
            });
            var firstOption = '<option value="" disabled="disabled"';
            firstOption += (
                select.val() || []
            ).length > 0 ? '' : ' selected="selected"';
            firstOption += '>' + select.attr('data-placeholder');
            firstOption += '</option>';
            select.prepend(firstOption);
        });
    }

    function loginWithAjaxHandlers() {
        if ($('.lwa-modal').length) {

            $('.js-lwa-open-remember-form').on('click', function(e) {
                e.stopPropagation();
                e.preventDefault();

                $('.js-lwa-login, .js-lwa-remember').toggleClass('form-visible');
            });

            $('.js-lwa-close-remember-form').on('click', function() {
                $('.js-lwa-login, .js-lwa-remember').toggleClass('form-visible');
            });

            $('.js-lwa-open-register-form').on('click', function(e) {
                e.stopPropagation();
                e.preventDefault();

                $('.js-lwa-login, .js-lwa-register').toggleClass('form-visible');
            });

            $('.js-lwa-close-register-form').on('click', function() {
                $('.js-lwa-login, .js-lwa-register').toggleClass('form-visible');
            });

            $('.lwa-login-link').on('touchstart', function() {
                closeMenu();
            });
        }
    }

    var HandleSubmenusOnTouch = (
        function() {
         
            var $theUsualSuspects,
                $theUsualAnchors,
                initialInit = false,
                isHorizontalInitiated = false,
                isSidebarInitiated = false;

            function init() {
                if (initialInit) {
                    return;
                }

                $theUsualSuspects = $('li[class*=children]');
                $theUsualAnchors = $theUsualSuspects.find('> a');

                bindOuterNavClick();

                initialInit = true;
            }

            // Sub menus will be opened with a click on the parent
            // The second click on the parent will follow parent's link
            function initHorizontalMenu() {
                if (isHorizontalInitiated) {
                    return;
                }

                init();
                unbind();

                // Make sure there are no open menu items
                $theUsualSuspects.removeClass('hover');

                $theUsualAnchors.on('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    if ($(this).hasClass('active')) {
                        window.location.href = $(this).attr('href');
                    }

                    $theUsualAnchors.removeClass('active');
                    $(this).addClass('active');

                    // When a parent menu item is activated,
                    // close other menu items on the same level
                    $(this).parent().siblings().removeClass('hover');

                    // Open the sub menu of this parent item
                    $(this).parent().addClass('hover');
                });

                isHorizontalInitiated = true;
            }

            // Sub menus will be opened on arrow click
            function initSidebarMenu() {
                if (isSidebarInitiated) {
                    return;
                }

                init();
                unbind();

                $theUsualAnchors.on('touchstart click', function(e) {
                    var posX = e.originalEvent.touches && e.originalEvent.touches[0].pageX ? e.originalEvent.touches[0].pageX : e.pageX;
                    var width = $(this).outerWidth();

                    if ((width - posX) < 60) {

                        e.preventDefault();
                        e.stopPropagation();

                        if ($(this).parent().hasClass('hover')) {
                            $(this).parent().removeClass('hover');
                        } else {
                            $(this).parent().addClass('hover');
                            $(this).parent().siblings().removeClass('hover');
                        }
                    }
                });

                isSidebarInitiated = true;
            }

            function unbind() {
                $theUsualAnchors.unbind();
                isHorizontalInitiated = false;
            }

            // When a sub menu is open, close it by a touch on
            // any other part of the viewport than navigation.
            // use case: normal, horizontal menu, touch events,
            // sub menus are not visible.
            function bindOuterNavClick() {
                $('body').on('touchstart', function(e) {
                    var container = $('.menu-wrapper');

                    if (!container.is(e.target) // if the target of the click isn't the container...
                        &&
                        container.has(e.target).length === 0) // ... nor a descendant of the container
                    {
                        $theUsualSuspects.removeClass('hover').removeClass('active');
                    }
                });
            }

            return {
                initHorizontalMenu: initHorizontalMenu,
                initSidebarMenu: initSidebarMenu
            }
        }()
    );

    function handleHiddenFacets() {
        if (!$body.hasClass('is--using-facetwp')) {
            return;
        }

        $('.js-toggle-hidden-facets').on('click', function() {
            $body.toggleClass('is--showing-hidden-facets');
            $('.hidden_facets').slideToggle(300);
        })
    }

    // Check if a sub menu's height is bigger that windows's width
    function handleLongSubMenus() {
        if (Modernizr.touchevents) {
            return;
        }

        $('li[class*="children"] > a').on('hover', function() {
            var $subMenu = $(this).siblings('.sub-menu');

            var remainingHeight = windowHeight - this.getBoundingClientRect().top;
            if (remainingHeight < $subMenu.height()) {
                $subMenu.addClass('big-one');
            }
        });
    }

    // Hide the category description after a FacetWP filtering
    function hideCategoryDescription() {
        if ($body.hasClass('is--using-facetwp')) {

            checkAndHideForFacet();

            $(document).on('facetwp-refresh', function() {
                setTimeout(function() {
                    checkAndHideForFacet();
                }, 1);
            });

        } else {
            $('.job_listings').on('update_results', function() {
                $('.listing_category_description.do-hide').hide();

                // An 'update_results' event is triggered on page load;
                // hide it only after it gets the class do-hide;
                // (only after the initial 'update_results' event is triggered)
                $('.listing_category_description').addClass('do-hide');
            });
        }
    }

    function checkAndHideForFacet() {
        var windowPath = window.location.href;

        if (windowPath.indexOf("fwp") > -1) {
            $('.listing_category_description').hide();
        } else {
            $('.listing_category_description').show();
        }
    }
})(jQuery);