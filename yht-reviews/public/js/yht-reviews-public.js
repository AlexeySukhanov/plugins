jQuery(document).ready(function($) {
	// This is required for AJAX to work on our page
	var ajaxurl = window.location.protocol + '//' + window.location.hostname + '/wp-admin/admin-ajax.php';

	function cvf_load_all_posts(page){
		// Start the transition
		$(".cvf_pag_loading").fadeIn().css('background','#fff');

		// Data to receive from our server
		// the value in 'action' is the key that will be identified by the 'wp_ajax_' hook
		var data = {
			page: page,
			action: "render_yht_reviews_view"
		};

		// Send the data
		$.post(ajaxurl, data, function(response) {
			// If successful Append the data into our html container
			$(".yht_reviews_container").html(response);
			// End the transition
			$(".cvf_pag_loading").css({'background':'white', 'transition':'all 1s ease-out'});
		});
	}

	// Load page 1 as the default
	cvf_load_all_posts(1);

	// Handle the clicks
	$('.yht_reviews_container .cvf-universal-pagination li.active').live('click',function(){
		var page = $(this).attr('p');
		cvf_load_all_posts(page);
	});

});