<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://yourhighesttruth.com
 * @since      1.0.0
 *
 * @package    Yht_Reviews
 * @subpackage Yht_Reviews/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Yht_Reviews
 * @subpackage Yht_Reviews/public
 * @author     yourhighesttruth.com <yourhighesttruth.com>
 */
class Yht_Reviews_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

        // Receive the Request post that came from AJAX
        add_action( 'wp_ajax_render_yht_reviews_view', array( $this, 'render_yht_reviews_view' ) );
        // We allow non-logged in users to access our pagination
        add_action( 'wp_ajax_nopriv_render_yht_reviews_view', array( $this, 'render_yht_reviews_view' ) );

        // Creates shortcode for default YHT Reviews widget output
        add_shortcode( 'yht_reviews', array( $this, 'render_yht_default_shortcode' ) );
	}

    /**
     * Render YHT Reviews default short-code
     *
     * @since    1.0.0
     */
	public function render_yht_default_shortcode() {
	    $site_url = get_site_url();

        $html = "
            <script>
            jQuery(document).ready(function($) {
            
                // This is required for AJAX to work on our page
                var ajaxurl = '". $site_url ."/wp-admin/admin-ajax.php';
                
                console.log(ajaxurl);
            
                function cvf_load_all_posts(page){
                    // Start the transition
                    $('.cvf_pag_loading').fadeIn().css('background','#fff');
            
                    // Data to receive from our server
                    // the value in 'action' is the key that will be identified by the 'wp_ajax_' hook
                    var data = {
                        page: page,
                        action: 'render_yht_reviews_view'
                    };
            
                    // Send the data
                    $.post(ajaxurl, data, function(response) {
                        // If successful Append the data into our html container
                        $('.yht_reviews_container').html(response);
                        // End the transition
                        $('.cvf_pag_loading').css({'background':'white', 'transition':'all 1s ease-out'});
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
            
            </script>
            ";

        $html .= '<div class = "yht_reviews_container"></div>';
        return $html;
    }

    /**
     * Render YHT Reviews default view
     *
     * @since    1.0.0
     */
    public function render_yht_reviews_view() {

        global $wpdb;

        if(isset($_POST['page'])) :

            // Sanitize the received page
            $page = sanitize_text_field($_POST['page']);
            $cur_page = $page;
            $page -= 1;

            // Set the number of results to display
            $per_page = 1;
            $previous_btn = true;
            $next_btn = true;
            $first_btn = true;
            $last_btn = true;
            $start = $page * $per_page;

            $reviews_query = new WP_Query(
                array(
                    'post_type'         => 'yht-reviews',
                    'post_status '      => 'publish',
                    'orderby'           => 'post_date',
                    'order'             => 'DESC',
                    'posts_per_page'    => $per_page,
                    'offset'            => $start
                )
            );
		
            $count_query = new WP_Query(
                array(
                    'post_type'         => 'yht-reviews',
                    'post_status '      => 'publish',
                    'posts_per_page'    => -1
                )
            );

            // Draft variables
            $ratings_number = 0;
            $overall_rating = 0;
            $five_four_rating = 0;
            $five_stars = $four_stars = $three_stars = $two_stars = $one_star = 0;

            // Variables calculations
            if ( $count_query->have_posts() ) {
                while ( $count_query->have_posts() ) {
                    $count_query->the_post();
                    $yht_rating = get_post_meta( get_the_ID(), 'yht_rating', true );
                    if ( $yht_rating ) {
                        $ratings_number++;
                        $overall_rating += $yht_rating;
                        switch ( $yht_rating ):
                            case 5: $five_stars++;
                                break;
                            case 4: $four_stars++;
                                break;
                            case 3: $three_stars++;
                                break;
                            case 2: $two_stars++;
                                break;
                            case 1: $one_star++;
                        endswitch;
                    }
                }
            }

            // Variables:

            $overall_rating = preg_replace('/\.\d{1}\K.+/', '', $overall_rating / $ratings_number);
            $overall_rating_ceil = ceil( $overall_rating );
            $five_four_rating =  floor ( 100 / $ratings_number * ($five_stars + $four_stars ) ) ;
            $five_stars_perc = 100 / $ratings_number * $five_stars;
            $four_stars_perc = 100 / $ratings_number * $four_stars;
            $three_stars_perc = 100 / $ratings_number * $three_stars;
            $two_stars_perc = 100 / $ratings_number * $two_stars;
            $one_star_perc = 100 / $ratings_number * $one_star;
		
            // Settings:
            $yht_settings = get_option( 'yht_reviews_options' );
            extract( $yht_settings );
            
            if ( !stristr( $yht_leave_review_url, 'http' ) && $yht_leave_review_url ) $yht_leave_review_url = 'http://' . $yht_leave_review_url;
            $yht_leave_review_url = $yht_leave_review_url ? $yht_leave_review_url : '#';

            // Include .yht-reviews-container template
            require_once plugin_dir_path(__DIR__ ) .  'public/partials/yht-reviews-container.php';

        endif;

        exit();

    }

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/yht-reviews-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/yht-reviews-public.js', array( 'jquery' ), $this->version, false );

	}

}