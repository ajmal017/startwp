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


    var Carousel = (function() {

        var offset, $container, $images, $prev, $next, lastScroll, totalWidth, $arrow, $currentImg;

        function init() {

            if (!$('.entry-featured-gallery').length) {
                return;
            }

            offset = $('.entry-header').offset().left;
            $container = $('.entry-featured-gallery');
            $arrow = $('.arrow-icon-svg');
            $prev = $('<div class="gallery-arrow gallery-arrow-prev">' + $arrow.html() + '</div>');
            $next = $('<div class="gallery-arrow gallery-arrow-next">' + $arrow.html() + '</div>');
            lastScroll = 0;
            totalWidth = 0;

            var isRtl = $body.hasClass('rtl');

            if (isRtl) {
                $container.children().each(function(i, obj) {
                    $container.prepend(obj)
                });
            }

            $images = $container.find('.entry-featured-image');

            if ($container.length && $images.length) {

                $prev.add($next).appendTo($container.parent());

                var zeroWidth = $images.last().width();

                $currentImg = $images.first();

                $images.each(function(i, obj) {
                    var $item = $(obj),
                        itemWidth = $item.width(),
                        itemOffset = $item.offset().left,
                        marginRight = parseInt($item.css('marginRight'), 10);

                    totalWidth = totalWidth + itemWidth + marginRight;

                    $item.data('index', i);
                    $item.data('offset', itemOffset);
                    $item.data('width', itemWidth);
                });

                if (totalWidth < windowWidth) {
                    $container.parent().addClass('is--at-start is--at-end').addClass('carousel-center');
                }

                lastScroll = zeroWidth - offset;
                $images = $container.children();

                onScroll();
                $container.on('scroll', onScroll);
                $('.gallery-arrow-prev').on('click', goToPrev);
                $('.gallery-arrow-next').on('click', goToNext);
                $prev.add($next).addClass('is--ready');
            }

            if (isRtl) {
                $container.scrollLeft($container[0].scrollWidth);
            }
        }

        function onScroll() {
            lastScroll = $container.scrollLeft();
            $container.parent()
                .toggleClass('is--at-start', lastScroll <= 10)
                .toggleClass('is--at-end', lastScroll >= totalWidth - windowWidth - 10);
        }

        function goToPrev() {
            var $to;
            $images.each(function(i, obj) {
                var $image = $(obj);
                if ($image.data('offset') < lastScroll) {
                    $to = $image;
                }
            });

            if (typeof $to !== "undefined") {
                setCurrent($to);
            }
        }

        function goToNext() {
            var $to;
            $images.each(function(i, obj) {
                var $image = $(obj);

                if ($image.data('offset') + $image.data('width') > lastScroll + windowWidth) {

                    if ($image.attr('src') == $currentImg.attr('src')) {
                        $image = $image.next();
                    }

                    $to = $image;
                    return false;
                }
            });

            if (typeof $to !== "undefined") {
                setCurrent($to);
            }
        }

        function setCurrent($current) {
            $currentImg = $current;

            TweenLite.to($container, .3, {
                scrollTo: {
                    x: $current.data('offset') - offset
                },
                ease: Power2.easeOut
            });
        }

        return {
            init: init
        }
    })();

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

    if (typeof listable_params.login_url !== "undefined" && listable_params.login_url.indexOf('action=logout') === -1) {
        $('a.iframe-login-link').magnificPopup({
            mainClass: "mfp-bg-transparent  mfp-login-modal",
            type: 'iframe',
            src: listable_params.login_url,
            iframe: {
                markup: '<div class="mfp-iframe-scaler  mfp-wp-login">' +
                    '<div class="mfp-close"></div>' +
                    '<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>' +
                    '</div>' // HTML markup of popup, `mfp-close` will be replaced by the close button
            },
            callbacks: {
                open: function() {
                    if (!listableDocumentCookies.hasItem('listable_login_modal')) {
                        listableDocumentCookies.setItem('listable_login_modal', 'opened', null, '/');
                    }

                    closeMenu();

                    $('body').addClass('overlay-is-open');
                    $('body').width($('body').width());
                    $('body').css('overflow', 'hidden');
                },
                close: function() {
                    listableDocumentCookies.removeItem('listable_login_modal', '/');

                    $('body').removeClass('overlay-is-open');
                    $('body').removeAttr('style');
                }
            }
        });
    }
    if ($('#map').length && typeof L === "object") {
        // set Leaflet's default path for images
        L.Icon.Default.imagePath = 'wp-content/themes/listable/assets/img/';
    }

    // Map module
    var Map = (
        function() {
            // create a custom icon class that can be extended for each listing category

            var map, markers, CustomHtmlIcon;

            // initialization - check wether we are on the archive page or on a single listing
            function init() {

                if ($('.no_job_listings_found').length) {
                    $('<div class="results">' + listable_params.strings['no_job_listings_found'] + '</div>').prependTo('.showing_jobs, .search-query');
                }

                if (!$('#map').length) {
                    $('#main .job_listings').on('updated_results', function(e, result) {
                        updateCards(result.total_found);
                    });
                    return;
                }

                if (typeof L !== "object" || !L.hasOwnProperty('map')) {
                    return;
                }

                map = L.map('map', {
                    scrollWheelZoom: false
                });
                markers = new L.MarkerClusterGroup({
                    showCoverageOnHover: false
                });
                CustomHtmlIcon = L.HtmlIcon.extend({
                    options: {
                        html: "<div class='pin'></div>",
                        iconSize: [48, 59], // size of the icon
                        iconAnchor: [24, 59], // point of the icon which will correspond to marker's location
                        popupAnchor: [0, -59] // point from which the popup should open relative to the iconAnchor
                    }
                });

                $window.on('pxg:refreshmap', function() {  
                    map._onResize();
                });

                var tileLayer,
                    mapboxToken = $('body').data('mapbox-token'),
                    mapboxStyle = $('body').data('mapbox-style');

                if (!empty(mapboxToken)) {
                    tileLayer = L.tileLayer('https://api.tiles.mapbox.com/v4/' + mapboxStyle + '/{z}/{x}/{y}.png?access_token=' + mapboxToken, {
                        maxZoom: 22,
                        attribution: '&copy; <a href="http://mapbox.com">Mapbox</a> | &copy; <a href="http://openstreetmap.org">OpenStreetMap</a>',
                        id: 'mapbox.streets'
                    })
                } else {
                    tileLayer = L.gridLayer.googleMutant({
                        type: 'roadmap'
                    });
                    $('#map').addClass('map--google');
                }

                map.addLayer(tileLayer);

                // if we are on the archive page (#map is not a single listing's map) :D
                // @todo do do doom
                if (!$('#map').is('.listing-map')) {
                    $('#main .job_listings').on('updated_results', function(e, result) {
                        updateCards(result.total_found);
                    });

                    //This one is for FacetWP
                    $(document).on('facetwp-loaded', function(e, result) {
                        updateCards();
                    });
                } else {
                    var $item = $('.single_job_listing');
                    // add only one marker if we're on the single listing page
                    if (typeof $item.data('latitude') !== "undefined" && typeof $item.data('longitude') !== "undefined") {

                        var zoom = (
                            typeof MapWidgetZoom !== "undefined"
                        ) ? MapWidgetZoom : 13;

                        addPinToMap($item);
                        map.addLayer(markers);
                        map.setActiveArea('active-area');
                        map.setView([$item.data('latitude'), $item.data('longitude')], zoom);
                        $(window).on('update:map', function() {
                            map.setView([$item.data('latitude'), $item.data('longitude')], zoom);
                        });
                    } else {
                        $('#map').hide();
                        $('.listing-address').css('marginTop', 0);
                    }
                }

                $('.js-find-me').on('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    map.locate({
                        setView: true,
                        maxZoom: 22
                    });
                });
            }

            function updateCards($total_found) {

                var $cards = $('#main .card');
                var cardsWithLocation = 0;

                if (!$cards.length) {
                    $('body').addClass('has-no-listings');
                    defaultMapView();
                    return;
                }

                //first some cleanup to avoid multiple results being shown - it happens
                $('.showing_jobs .results').remove();

                if (typeof $total_found !== 'undefined') {
                    //someone must have blessed us with higher knowledge
                    //let's not let it go to waste
                    $('<div class="results"><span class="results-no">' + $total_found + '</span> ' + listable_params.strings['results-no'] + '</div>').prependTo('.showing_jobs, .search-query');
                } else {
                    $('<div class="results"><span class="results-no">' + $cards.length + '</span> ' + listable_params.strings['results-no'] + '</div>').prependTo('.showing_jobs, .search-query');
                }

                if ($('.map').length && typeof map !== "undefined") {
                    map.removeLayer(markers);
                    markers = new L.MarkerClusterGroup({
                        showCoverageOnHover: false,
                        spiderfyDistanceMultiplier: 3,
                        spiderLegPolylineOptions: {
                            weight: 0
                        }
                    });
                    $cards.each(function(i, obj) {
                        var cardHasLocation = addPinToMap($(obj), true);
                        if (cardHasLocation) {
                            cardsWithLocation += 1;
                        }
                    });

                    if (cardsWithLocation != 0) {
                        map.fitBounds(markers.getBounds(), {
                            padding: [50, 50]
                        });
                        map.addLayer(markers);

                        var mapZoom = map.getZoom();
                        var bounds = markers.getBounds();
                        var lat = (bounds._northEast.lat + bounds._southWest.lat) / 2;
                        var lng = (bounds._northEast.lng + bounds._southWest.lng) / 2;
                        bounds = [lat, lng];

                        Cookies.set('pxg-listable-bounds', JSON.stringify(bounds));
                        Cookies.set('pxg-listable-mapZoom', mapZoom);
                    } else {
                        defaultMapView();
                    }
                }
            }

            function addPinToMap($item, archive) {
                var categories = $item.data('categories'),
                    iconClass, m;

                if (empty($item.data('latitude')) || empty($item.data('longitude'))) {
                    return false;
                }

                if (typeof categories !== "undefined" && !categories.length) {
                    iconClass = 'pin pin--empty';
                } else {
                    iconClass = 'pin';
                }

                var $icon = $('.selected-icon-svg'),
                    $tags = $item.find('.card__tag'),
                    $categories = $item.find('.category-icon'),
                    $tag, iconHTML = "<div class='" + iconClass + "'>" + $('.empty-icon-svg').html() + "</div>";

                if ($body.is('.single-job_listing')) {
                    // If we are on a single listing
                    if ($('.single-listing-map-category-icon').length) {
                        iconHTML = "<div class='" + iconClass + "'>" + $icon.html() + "<div class='pin__icon'>" + $('.single-listing-map-category-icon').html() + "</div></div>";
                    }
                } else if ($tags.length) {
                    $tag = $tags.first();
                    iconHTML = "<div class='" + iconClass + "'>" + $icon.html() + $tag.html() + "</div>";
                } else if ($categories.length) {
                    iconHTML = "<div class='" + iconClass + "'>" + $icon.html() + "<div class='pin__icon'>" + $categories.html() + "</div></div>";
                }

                m = L.marker([$item.data('latitude'), $item.data('longitude')], {
                    icon: new CustomHtmlIcon({
                        html: iconHTML
                    })
                });

                if (typeof archive !== "undefined") {

                    $item.hover(function() {
                        $(m._icon).find('.pin').addClass('pin--selected');
                    }, function() {
                        $(m._icon).find('.pin').removeClass('pin--selected');
                    });

                    var rating = $item.find('.js-average-rating').text(),
                        ratingHTML = rating.length ? "<div class='popup__rating'><span>" + rating + "</span></div>" : "",
                        address = $item.find('.card__address').text();

                    m.bindPopup(
                        "<a class='popup' href='" + $item.data('permalink') + "'>" +
                        "<div class='popup__image' style='background-image: url(" + $item.data('img') + ");'></div>" +
                        "<div class='popup__content'>" +
                        "<h3 class='popup__title'>" + $item.find('.card__title').html() + "</h3>" +
                        "<div class='popup__footer'>" +
                        ratingHTML +
                        "<div class='popup__address'>" + $item.find('.card__address').html() + "</div>" +
                        "</div>" +
                        "</div>" +
                        "</a>").openPopup();
                }

                markers.addLayer(m);

                return true;
            }

            function defaultMapView() {
                var bounds = Cookies.get('pxg-listable-bounds'),
                    zoom = Cookies.get('pxg-listable-mapZoom');

                if (typeof bounds === 'undefined') {
                    bounds = [51.4825766, 0.0098476];
                    zoom = 9;
                } else {
                    bounds = JSON.parse(bounds);
                }

                map.removeLayer(markers);
                map.setView(bounds, zoom);
            }

            return {
                init: init,
                updateResults: updateCards
            }
        }
    )();

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

        $('.wc-social-login').attr('data-string', listable_params.strings.social_login_string);
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
                $btn = $('<div class="uploader-btn"><div class="spacer"><div class="text">' + listable_params.strings['wp-job-manager-file-upload'] + '</div></div></div>').insertAfter($input);

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

        Map.init();

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
     


    // /* ====== ON WINDOW LOAD ====== */
    $window.load(function() {
        $('html').addClass('is--loaded');
 
        Carousel.init();

        tooltipTrigger();
        keepSubmenusInViewport(); 
        $('.js-menu-trigger').on('touchstart click', toggleMenu);

        if (windowWidth < 900) {
            HandleSubmenusOnTouch.initSidebarMenu();
        } else {
            HandleSubmenusOnTouch.initHorizontalMenu();
        }

        if ($('.site-header .search-form').is(':visible')) {
            handleMobileHeaderSearch();
        }
        
        //for search listings we need to make some magic to make it behave like the categories and tags archives
        if ($body.is('.search') && $body.is('.post-type-archive-job_listing')) {
            if ($('.job_listings #search_keywords').length) {
                $('.job_listings #search_keywords').val($('input.search-field').val());
            } else {
                //steal the search input data and put it in among some make shift filters
                $('.job_listings').append('<form class="job_filters"><input type="hidden" name="search_keywords" id="search_keywords" value="' + $('input.search-field').val() + '"/></form>');
            }
            //now trigger and update so we can receive listings
            //$('.job_listings').trigger( 'update_results', [ 1, true ] );
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
                    HandleSubmenusOnTouch.initHorizontalMenu();
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

    var listableDocumentCookies = {
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