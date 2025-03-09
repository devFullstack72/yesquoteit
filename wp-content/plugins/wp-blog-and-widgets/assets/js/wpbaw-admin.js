(function( $ ) {

	"use strict";

		/* Vertical Tab */
		$( document ).on( "click", ".wpbaw-vtab-nav a", function() {

			$(".wpbaw-vtab-nav").removeClass('wpbaw-active-vtab');
			$(this).parent('.wpbaw-vtab-nav').addClass("wpbaw-active-vtab");

			var selected_tab = $(this).attr("href");
			$('.wpbaw-vtab-cnt').hide();

			/* Show the selected tab content */
			$(selected_tab).show();

			/* Pass selected tab */
			$('.wpbaw-selected-tab').val(selected_tab);
			return false;
		});

		/* Remain selected tab for user */
		if( $('.wpbaw-selected-tab').length > 0 ) {
			
			var sel_tab = $('.wpbaw-selected-tab').val();

			if( typeof(sel_tab) !== 'undefined' && sel_tab != '' && $(sel_tab).length > 0 ) {
				$('.wpbaw-vtab-nav [href="'+sel_tab+'"]').click();
			} else {
				$('.wpbaw-vtab-nav:first-child a').click();
			}
		}

		/* Click to Copy the Text */
		$(document).on('click', '.wpos-copy-clipboard', function() {
			var copyText = $(this);
			copyText.select();
			document.execCommand("copy");
		});
})(jQuery);