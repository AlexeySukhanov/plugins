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
        // Creates shortcode for ribbon YHT Reviews widget output
        add_shortcode( 'yht_reviews_ribbon', array( $this, 'render_yht_ribbon_shortcode' ) );
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
     * Render YHT Reviews ribbon short-code
     *
     * @since    1.0.0
     */
    public function render_yht_ribbon_shortcode() {
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
        $five_stars = $four_stars = $three_stars = $two_stars = $one_star = 0;

        // Variables calculations
        if ( $count_query->have_posts() ) {
            while ( $count_query->have_posts() ) {
                $count_query->the_post();
                $yht_rating = get_post_meta( get_the_ID(), 'yht_rating', true );
                if ( $yht_rating ) {
                    $ratings_number++;
                    $overall_rating += $yht_rating;
                }
            }
        }

        // Variables:
        $overall_rating = preg_replace('/\.\d{1}\K.+/', '', $overall_rating / $ratings_number);
        $overall_rating_ceil = ceil( $overall_rating );
        $five_four_rating =  floor ( 100 / $ratings_number * ($five_stars + $four_stars ) ) ;

        // Settings:
        $yht_settings = get_option( 'yht_reviews_options' );
        extract( $yht_settings );

        $ratings_output = $ratings_number > 1 ? '<li id="tp-widget-rating" class="tp-widget-rating"><strong>' . $overall_rating . '</strong> out of 5 based on <strong>' . $ratings_number .  ' reviews</strong></li> ' : '';

        $star_output = '';
        if ( $overall_rating_ceil ) {
            $star_output .= '<svg viewBox="0 0 251 46" xmlns="http://www.w3.org/2000/svg" style="position: absolute; height: 100%; width: 100%; left: 0; top: 0;">';

            $rating_word = '';
            if( $overall_rating > 4 ) {
                $rating_word = 'Excellent';
            } elseif ( $overall_rating > 3 ) {
                $rating_word = 'Great';
            } elseif ( $overall_rating > 2 ) {
                $rating_word = 'Good';
            } elseif ( $overall_rating > 1 ) {
                $rating_word = 'Okay';
            } else {
                $rating_word = 'Bad';
            }

            if ( $overall_rating_ceil == 5 ) {
                $star_output .= '
                        <g class="tp-star">
                          <path class="tp-star__canvas" fill="#000" d="M0 46.330002h46.375586V0H0z"></path>
                          <path class="tp-star__shape" d="M39.533936 19.711433L13.230239 38.80065l3.838216-11.797827L7.02115 19.711433h12.418975l3.837417-11.798624 3.837418 11.798624h12.418975zM23.2785 31.510075l7.183595-1.509576 2.862114 8.800152L23.2785 31.510075z" fill="#FFF"></path>
                        </g>
                        <g class="tp-star">
                          <path class="tp-star__canvas" fill="#000" d="M51.24816 46.330002h46.375587V0H51.248161z"></path>
                          <path class="tp-star__canvas--half" fill="#000" d="M51.24816 46.330002h23.187793V0H51.248161z"></path>
                          <path class="tp-star__shape" d="M74.990978 31.32991L81.150908 30 84 39l-9.660206-7.202786L64.30279 39l3.895636-11.840666L58 19.841466h12.605577L74.499595 8l3.895637 11.841466H91L74.990978 31.329909z" fill="#FFF"></path>
                        </g>
                        <g class="tp-star">
                          <path class="tp-star__canvas" fill="#000" d="M102.532209 46.330002h46.375586V0h-46.375586z"></path>
                          <path class="tp-star__canvas--half" fill="#000" d="M102.532209 46.330002h23.187793V0h-23.187793z"></path>
                          <path class="tp-star__shape" d="M142.066994 19.711433L115.763298 38.80065l3.838215-11.797827-10.047304-7.291391h12.418975l3.837418-11.798624 3.837417 11.798624h12.418975zM125.81156 31.510075l7.183595-1.509576 2.862113 8.800152-10.045708-7.290576z" fill="#FFF"></path>
                        </g>
                        <g class="tp-star">
                          <path class="tp-star__canvas" fill="#000" d="M153.815458 46.330002h46.375586V0h-46.375586z"></path>
                          <path class="tp-star__canvas--half" fill="#000" d="M153.815458 46.330002h23.187793V0h-23.187793z"></path>
                          <path class="tp-star__shape" d="M193.348355 19.711433L167.045457 38.80065l3.837417-11.797827-10.047303-7.291391h12.418974l3.837418-11.798624 3.837418 11.798624h12.418974zM177.09292 31.510075l7.183595-1.509576 2.862114 8.800152-10.045709-7.290576z" fill="#FFF"></path>
                        </g>
                        <g class="tp-star">
                          <path class="tp-star__canvas" fill="#000" d="M205.064416 46.330002h46.375587V0h-46.375587z"></path>
                          <path class="tp-star__canvas--half" fill="#000" d="M205.064416 46.330002h23.187793V0h-23.187793z"></path>
                          <path class="tp-star__shape" d="M244.597022 19.711433l-26.3029 19.089218 3.837419-11.797827-10.047304-7.291391h12.418974l3.837418-11.798624 3.837418 11.798624h12.418975zm-16.255436 11.798642l7.183595-1.509576 2.862114 8.800152-10.045709-7.290576z" fill="#FFF"></path>
                        </g>
                ';
            } elseif ( $overall_rating_ceil == 4 ) {
                $star_output .= '
                        <g class="tp-star">
                          <path class="tp-star__canvas" fill="#000" d="M0 46.330002h46.375586V0H0z"></path>
                          <path class="tp-star__shape" d="M39.533936 19.711433L13.230239 38.80065l3.838216-11.797827L7.02115 19.711433h12.418975l3.837417-11.798624 3.837418 11.798624h12.418975zM23.2785 31.510075l7.183595-1.509576 2.862114 8.800152L23.2785 31.510075z" fill="#FFF"></path>
                        </g>
                        <g class="tp-star">
                          <path class="tp-star__canvas" fill="#000" d="M51.24816 46.330002h46.375587V0H51.248161z"></path>
                          <path class="tp-star__canvas--half" fill="#000" d="M51.24816 46.330002h23.187793V0H51.248161z"></path>
                          <path class="tp-star__shape" d="M74.990978 31.32991L81.150908 30 84 39l-9.660206-7.202786L64.30279 39l3.895636-11.840666L58 19.841466h12.605577L74.499595 8l3.895637 11.841466H91L74.990978 31.329909z" fill="#FFF"></path>
                        </g>
                        <g class="tp-star">
                          <path class="tp-star__canvas" fill="#000" d="M102.532209 46.330002h46.375586V0h-46.375586z"></path>
                          <path class="tp-star__canvas--half" fill="#000" d="M102.532209 46.330002h23.187793V0h-23.187793z"></path>
                          <path class="tp-star__shape" d="M142.066994 19.711433L115.763298 38.80065l3.838215-11.797827-10.047304-7.291391h12.418975l3.837418-11.798624 3.837417 11.798624h12.418975zM125.81156 31.510075l7.183595-1.509576 2.862113 8.800152-10.045708-7.290576z" fill="#FFF"></path>
                        </g>
                        <g class="tp-star">
                          <path class="tp-star__canvas" fill="#000" d="M153.815458 46.330002h46.375586V0h-46.375586z"></path>
                          <path class="tp-star__canvas--half" fill="#000" d="M153.815458 46.330002h23.187793V0h-23.187793z"></path>
                          <path class="tp-star__shape" d="M193.348355 19.711433L167.045457 38.80065l3.837417-11.797827-10.047303-7.291391h12.418974l3.837418-11.798624 3.837418 11.798624h12.418974zM177.09292 31.510075l7.183595-1.509576 2.862114 8.800152-10.045709-7.290576z" fill="#FFF"></path>
                        </g>
                ';
            } elseif ( $overall_rating_ceil == 3 ) {
                $star_output .= '
                        <g class="tp-star">
                          <path class="tp-star__canvas" fill="#000" d="M0 46.330002h46.375586V0H0z"></path>
                          <path class="tp-star__shape" d="M39.533936 19.711433L13.230239 38.80065l3.838216-11.797827L7.02115 19.711433h12.418975l3.837417-11.798624 3.837418 11.798624h12.418975zM23.2785 31.510075l7.183595-1.509576 2.862114 8.800152L23.2785 31.510075z" fill="#FFF"></path>
                        </g>
                        <g class="tp-star">
                          <path class="tp-star__canvas" fill="#000" d="M51.24816 46.330002h46.375587V0H51.248161z"></path>
                          <path class="tp-star__canvas--half" fill="#000" d="M51.24816 46.330002h23.187793V0H51.248161z"></path>
                          <path class="tp-star__shape" d="M74.990978 31.32991L81.150908 30 84 39l-9.660206-7.202786L64.30279 39l3.895636-11.840666L58 19.841466h12.605577L74.499595 8l3.895637 11.841466H91L74.990978 31.329909z" fill="#FFF"></path>
                        </g>
                        <g class="tp-star">
                          <path class="tp-star__canvas" fill="#000" d="M102.532209 46.330002h46.375586V0h-46.375586z"></path>
                          <path class="tp-star__canvas--half" fill="#000" d="M102.532209 46.330002h23.187793V0h-23.187793z"></path>
                          <path class="tp-star__shape" d="M142.066994 19.711433L115.763298 38.80065l3.838215-11.797827-10.047304-7.291391h12.418975l3.837418-11.798624 3.837417 11.798624h12.418975zM125.81156 31.510075l7.183595-1.509576 2.862113 8.800152-10.045708-7.290576z" fill="#FFF"></path>
                        </g>
                ';
            } elseif ( $overall_rating_ceil == 2 ) {
                $star_output .= '
                        <g class="tp-star">
                          <path class="tp-star__canvas" fill="#000" d="M0 46.330002h46.375586V0H0z"></path>
                          <path class="tp-star__shape" d="M39.533936 19.711433L13.230239 38.80065l3.838216-11.797827L7.02115 19.711433h12.418975l3.837417-11.798624 3.837418 11.798624h12.418975zM23.2785 31.510075l7.183595-1.509576 2.862114 8.800152L23.2785 31.510075z" fill="#FFF"></path>
                        </g>
                        <g class="tp-star">
                          <path class="tp-star__canvas" fill="#000" d="M51.24816 46.330002h46.375587V0H51.248161z"></path>
                          <path class="tp-star__canvas--half" fill="#000" d="M51.24816 46.330002h23.187793V0H51.248161z"></path>
                          <path class="tp-star__shape" d="M74.990978 31.32991L81.150908 30 84 39l-9.660206-7.202786L64.30279 39l3.895636-11.840666L58 19.841466h12.605577L74.499595 8l3.895637 11.841466H91L74.990978 31.329909z" fill="#FFF"></path>
                        </g>
                ';
            } elseif ( $overall_rating_ceil == 1 ) {
                $star_output .= '
                        <g class="tp-star">
                          <path class="tp-star__canvas" fill="#000" d="M0 46.330002h46.375586V0H0z"></path>
                          <path class="tp-star__shape" d="M39.533936 19.711433L13.230239 38.80065l3.838216-11.797827L7.02115 19.711433h12.418975l3.837417-11.798624 3.837418 11.798624h12.418975zM23.2785 31.510075l7.183595-1.509576 2.862114 8.800152L23.2785 31.510075z" fill="#FFF"></path>
                        </g>
                ';
            }
            $star_output .= '</svg>';
        }

        // Fix admin bar indent
        if( is_admin_bar_showing() && $yht_ribbon_indent ){
            $yht_ribbon_indent += 32;
        } elseif ( is_admin_bar_showing() ) {
            $yht_ribbon_indent = 32;
        }

        $html = '

        <style>' . $yht_custom_styles . '</style>
        <div id="action_bar_cont">
        <div id="action_bar" style="top:' . $yht_ribbon_indent  .'px!important;">
            <div class="container">
                <div class="column">
                    <ul class="contact_details">
                        <li class="slogan">' . $yht_ribbon_text . '</li>
                    </ul>
                    <ul class="social_widetrust" >
                        <li id="trust-score" class="tp-widget-trustscore">' . $rating_word . '</li>
                        
                        <div class="tp-widget-stars">
                            <a id="tp-widget-stars" class="profile-url" target="_blank" href="https://www.trustpilot.com">
                                <div style="position: relative; height: 0; width: 130px; padding: 0; padding-bottom: 18.326693227091635%;">
                                    ' . $star_output . '
                                </div>
                            </a>
                        </div>
                        
                        '.$ratings_output.'
                        
                        <li id="ribbon-logo">
							<a target="_blank" href="https://www.trustpilot.com" >
                            <img src="' . plugin_dir_url( __FILE__ ) .  'img/ts_whitebg.png">  
							</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div> 
        </div> 
        ';

        // Include yht_only_sticky mode CSS&JS
        if ( $yht_only_sticky ) {

            // Include CSS if yht_only_sticky mode ON
            $html .= '<style>' . file_get_contents( plugin_dir_url( __FILE__ ) .  'css/yht-reviews-only-sticky-bar.css' ) . '</style>';
            // Include JS if yht_only_sticky mode ON
            $html .= '<script>' . file_get_contents( plugin_dir_url( __FILE__ ) .  'js/yht-reviews-only-sticky-bar.js' ) . '</script>';

        } else {

            // Include CSS if yht_only_sticky mode OFF
            $html .= '<style>' . file_get_contents( plugin_dir_url( __FILE__ ) .  'css/yht-reviews-sticky-and-static-bar.css' ) . '</style>';
            // Include JS if yht_only_sticky mode ON
            $html .= '<script>' . file_get_contents( plugin_dir_url( __FILE__ ) .  'js/yht-reviews-sticky-and-static-bar.js' ) . '</script>';

        }

        return $html;
    }

    /**
     * Render YHT Reviews ribbon view
     *
     * @since    1.0.0
     */
    public function render_yht_reviews_ribbon_view() {

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

        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/yht-reviews-sticky.js', array( 'jquery' ), $this->version, true );

    }

}