(function($){

	$(document).ready(function(){
		

		(function postPage(){
			let format = document.querySelector('input[name=\'post_format\']:checked');
			if( !format ) return
		
			let postFormat = format.getAttribute('id').slice(12);
			adjustView(postFormat);
	
			function adjustView(postFormat){
				let contentPostBlocks = Array.from(document.querySelectorAll('div[id*=bitstarter_post]'));
				contentPostBlocks.map(item => $(item).slideUp());				
				$(`#bitstarter_post_${postFormat}`).slideDown();
				
			}
	
		
			$('#formatdiv').on('change', function (e) {
				if( ! e.target instanceof Element ) return
				let postFormat = e.target.getAttribute('id').slice(12);
				adjustView( postFormat );
			});
		})()

		if($('#post_ID').length > 0){
			if( $('#post_ID').val() === BitstarterParams.blog_id ){
				$('#postimagediv').hide();
			}
		}
		// determine if we should see the Featured Image box depending on the current page template
		if ( $('select#page_template').val() == 'default' || $('select#page_template').val() == 'page-templates/front_page.php' ) {
			// hide the Featured Image box
			$('#postimagediv').hide();

			if($('#post_ID').length > 0){
				if( $('#post_ID').val() !== BitstarterParams.blog_id ){
					$('#bitstarter_page_background').hide();
				}
			}
	
		}

		/**
		 * On page template change hide the Featured Image box since we show the Hero one
		 */

		if( $('#page_template').length > 0 ){
			$('#postimagediv').hide();
		}
	});


	// Redefines jQuery.fn.html() to add custom events that are triggered before and after a DOM element's innerHtml is changed
	// html-change-pre is triggered before the innerHtml is changed
	// html-change-post is triggered after the innerHtml is changed
	var eventName = 'html-change';
	// Save a reference to the original html function
	jQuery.fn.originalHtml = jQuery.fn.html;
	// Let's redefine the html function to include a custom event
	jQuery.fn.html = function() {
		var currentHtml = this.originalHtml();
		if(arguments.length) {
			this.trigger(eventName + '-pre', jQuery.merge([currentHtml], arguments));
			jQuery.fn.originalHtml.apply(this, arguments);
			this.trigger(eventName + '-post', jQuery.merge([currentHtml], arguments));
			return this;
		} else {
			return currentHtml;
		}
	};


})(jQuery);

