// Module to enhance wp blog with likes and shares. 

jQuery(window).load(function() {
    Likes.init();
    Share.init();
});

var Likes = (function($) {
    function init(){
        var stop = false; 
        $(".likes-count").on('click', function(event) {
            event.preventDefault();
            if(stop) return
            var $this = $(this);
            $this.addClass('bitstarter__icon--anim');
            stop = true;
            $.post(IondigitalThemeParams.ajax.url, {
                action: IondigitalThemeParams.ajax.likes_action,
                ID: $this.data("post-id")
            }).done(function(response) {
                stop = false

                if( typeof  response === "string"){
                    response = JSON.parse(response)
                }
                
                if (response.data.liked) {
                    $this.addClass("likes-count--active");

                    var n = $this.find(".likes-count__number").text() || 0;

                    if( !isNaN(n) ) $this.find(".likes-count__number").text(parseInt(n) + 1);
                } else {
                    $this.removeClass("likes-count--active");

                    var n = $this.find(".likes-count__number").text() || 0
                    if(!isNaN(n)) $this
                        .find(".likes-count__number")
                        .text(parseInt(n) <= 0 ? 0 : parseInt(n) - 1);
                }
                $this.removeClass('bitstarter__icon--anim');
            }).fail( _ => {
                stop = false;
                $this.removeClass('bitstarter__icon--anim');
            });
        });
    }

    return {
        init: init
    }
})(jQuery);


var Share = (function($, w) {
    function init(){
        var shareServices = {
            twitter: function() {
                window.open('http://twitter.com/intent/tweet?text=' + jQuery(".entry-title").text() + ' ' + window.location,
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
                window.open('http://pinterest.com/pin/create/button/?description=' + jQuery(".entry-title").text() + '&url=' + encodeURIComponent(location.href),
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
        w.shareServices = shareServices;


    }
    
    return {
        init: init
    }

})(jQuery, window);
