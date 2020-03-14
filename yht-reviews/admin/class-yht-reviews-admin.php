<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://yourhighesttruth.com
 * @since      1.0.0
 *
 * @package    Yht_Reviews
 * @subpackage Yht_Reviews/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Yht_Reviews
 * @subpackage Yht_Reviews/admin
 * @author     yourhighesttruth.com <yourhighesttruth.com>
 */
class Yht_Reviews_Admin {

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
     * The settings page slug.
     *
     * @since    1.0.0
     * @access   public
     * @var      string    $yht_settings_page_addr    The settings page slug.
     */
    public $yht_settings_page_addr = 'edit.php?post_type=yht-reviews';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_action( 'init', array( $this, 'init_reviews_cpt' ) );
        add_action( 'add_meta_boxes', array( $this, 'init_yht_review_box' ) );
        add_action( 'save_post', array( $this, 'save_yht_review_box' ) );
        add_filter( 'hidden_meta_boxes', array( $this, 'clear_default_boxes' ) , 10, 3 );
        add_filter( 'post_updated_messages', array( $this, 'update_reviews_messages' ) );
        add_action('admin_menu', array( $this, 'init_reviews_settings_page' ) );
        add_action( 'admin_init', array( $this, 'init_yht_reviews_settings' ) );

	}

    /**
     * Init YHT reviews custom post type.
     *
     * @since    1.0.0
     */
	public function init_reviews_cpt() {

	    $labels = array(
	        'name' => 'Reviews',
            'singular_name' => 'Review',
            'add_new' => 'Add Review',
            'add_new_item' => 'Add new review',
            'edit_item' => 'Edit review',
            'new_item' => 'New review',
            'all_items' => 'All reviews',
            'view_item' => 'View review',
            'search_items' => 'Search reviews',
            'not_found' => 'Reviews not found',
            'not_found_in_trash' => 'Not found in trash',
            'menu_name' => 'YHT Reviews'
        );

	    $args = array(
	        'labels' => $labels,
            'public' => true,
            'show_in_nav_menus' => false,
            'menu_icon' => 'dashicons-star-half',
            'menu_position' => 9,
            'supports' => array( 'title', 'custom-fields' )
        );

	    register_post_type( 'yht-reviews', $args );

    }

    /**
     * Init meta-box for YHT Reviews CPT.
     *
     * @since    1.0.0
     */
    public function init_yht_review_box() {
        add_meta_box(
            'yht-review-box',
            'Review Info',
            array( $this, 'render_yht_review_box' ),
            'yht-reviews',
            'normal',
            'default'
        );
    }

    /**
     * Render meta-box for YHT Reviews CPT.
     *
     * @param object $post
     * @since    1.0.0
     */
    public function render_yht_review_box( $post ) {

        // Get CPT data
        $yht_user_name = get_post_meta($post->ID, 'yht_user_name',true);
        $yht_date = get_post_meta($post->ID, 'yht_date',true);
        $yht_rating = get_post_meta( $post->ID, 'yht_rating', true );
        $yht_testimonial = get_post_meta( $post->ID, 'yht_testimonial', true );

        // Validation field output
        wp_nonce_field( basename( __FILE__ ), 'yht_review_box_nonce' );

        // Include reviews meta-box template
        require_once plugin_dir_path(__DIR__ ) .  'admin/partials/yht-reviews-meta-box.php';

    }

    /**
     * Save meta-box data for YHT Reviews CPT.
     *
     * @param int $post_id
     * @since    1.0.0
     */
    public function save_yht_review_box( $post_id ) {

        // Check wp_nonce correctness
        if ( !isset( $_POST['yht_review_box_nonce'] ) || !wp_verify_nonce( $_POST['yht_review_box_nonce'], basename( __FILE__ ) ) ) {
            return $post_id;
        }

        // Check is it autosave
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        // Check user rights
        if ( !current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
        }

        // Check post type
        $post = get_post( $post_id );
        if ( $post->post_type == 'yht-reviews' ) {

            update_post_meta( $post_id, 'yht_user_name', esc_attr( $_POST['yht_user_name'] ) );
            update_post_meta( $post_id, 'yht_date', esc_attr( $_POST['yht_date'] ) );
            update_post_meta( $post_id, 'yht_rating', $_POST['yht_rating'] );
            update_post_meta( $post_id, 'yht_testimonial', $_POST['yht_testimonial'] );

        }

        return $post_id;

    }

    /**
     * Clear default meta-boxes CPT.
     *
     * @param array $hidden
     * @since    1.0.0
     */
    public function clear_default_boxes( $hidden, $screen, $use_defaults ) {
        global $wp_meta_boxes;
        $cpt = 'yht-reviews';

        if( $cpt === $screen->id && isset( $wp_meta_boxes[$cpt] ) )
        {
            $tmp = array();
            foreach( (array) $wp_meta_boxes[$cpt] as $context_key => $context_item )
            {
                foreach( $context_item as $priority_key => $priority_item )
                {
                    foreach( $priority_item as $metabox_key => $metabox_item )
                        if ( $metabox_key !== 'yht-review-box' && $metabox_key !== 'slugdiv' && $metabox_key !== 'submitdiv'  ) {
                            $tmp[] = $metabox_key;
                        }
                }
            }

            $hidden = $tmp;
        }
        return $hidden;
    }

    /**
     * Update YHT reviews CPT status messages.
     *
     * @param array $messages
     * @since    1.0.0
     */
    public function update_reviews_messages( $messages ) {

        global $post, $post_ID;

        $messages['yht-reviews'] = array(
            0 => '',
            1 => sprintf( 'Review updated. <a href="%s">View</a>', esc_url( get_permalink($post_ID) ) ),
            2 => 'Parameter updated.',
            3 => 'Parameter deleted.',
            4 => 'Review updated',
            5 => isset($_GET['revision']) ? sprintf( 'Review restored from edition: %s', wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6 => sprintf( 'Review published on site. <a href="%s">View</a>', esc_url( get_permalink($post_ID) ) ),
            7 => 'Review saved.',
            8 => sprintf( 'Submitted for review verification. <a target="_blank" href="%s">View</a>', esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
            9 => sprintf( 'Scheduled for publication: <strong>%1$s</strong>. <a target="_blank" href="%2$s">View</a>', date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
            10 => sprintf( 'Draft updated. <a target="_blank" href="%s">View</a>', esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
        );

    }

    /**
     * Init YHT reviews settings page.
     *
     * @since    1.0.0
     */
    public function init_reviews_settings_page() {

        add_submenu_page(
            $this->yht_settings_page_addr,
            'Reviews Settings',
            'Reviews Settings',
            'manage_options',
            'yht-reviews-settings',
            array( $this, 'render_reviews_settings_page' )
        );

    }

    /**
     * Render YHT reviews settings page.
     *
     * @since    1.0.0
     */
    public function render_reviews_settings_page(){

        // Include YHT Reviews settings page template
        require_once plugin_dir_path(__DIR__ ) .  'admin/partials/yht-reviews-settings-page.php';

    }

    /**
     * Init YHT reviews settings.
     *
     * @since    1.0.0
     */
    public function init_yht_reviews_settings() {

        // Присваиваем функцию валидации ( true_validate_settings() ). Вы найдете её ниже
        register_setting( 'yht_reviews_options', 'yht_reviews_options' );

        // Add settings sections
        add_settings_section( 'default_view_section', 'Default View Settings', '', $this->yht_settings_page_addr );
        add_settings_section( 'ribbon_view_section', 'Ribbon View Settings', '', $this->yht_settings_page_addr );

        // Create Leave Review Url text field
        $yht_reviews_field_params = array(
            'type'      => 'text', // тип
            'id'        => 'yht_leave_review_url',
            'desc'      => 'URL in the text "Click here to leave a review →"',
        );
        add_settings_field( 'yht_leave_review_url_field', 'Leave Review URL', array( $this, 'render_yht_reviews_settings' ), $this->yht_settings_page_addr, 'default_view_section', $yht_reviews_field_params );

        // Create display settings
        $yht_reviews_field_params = array(
            'type'      => 'checkbox',
            'id'        => 'yht_display_name',
            'desc'      => 'Mark if you want to display names.'
        );
        add_settings_field( 'yht_display_name_field', 'Display Names', array( $this, 'render_yht_reviews_settings' ), $this->yht_settings_page_addr, 'default_view_section', $yht_reviews_field_params );

        $yht_reviews_field_params = array(
            'type'      => 'checkbox',
            'id'        => 'yht_display_date',
            'desc'      => 'Mark if you want to display dates.'
        );
        add_settings_field( 'yht_display_date_field', 'Display Dates', array( $this, 'render_yht_reviews_settings' ), $this->yht_settings_page_addr, 'default_view_section', $yht_reviews_field_params );

        $yht_reviews_field_params = array(
            'type'      => 'checkbox',
            'id'        => 'yht_display_title',
            'desc'      => 'Mark if you want to display reviews titles.'
        );
        add_settings_field( 'yht_display_title_field', 'Display Reviews Titles', array( $this, 'render_yht_reviews_settings' ), $this->yht_settings_page_addr, 'default_view_section', $yht_reviews_field_params );

        // Create copy shortcode field
        $yht_reviews_field_params = array(
            'type'      => 'copy_shortcode',
            'id'        => 'yht_copy_short_code',
            'desc'      => 'Click on the field to copy the shortcode to paste in pages and posts through the admin panel.',
        );
        add_settings_field( 'yht_copy_short_code_field', 'Copy Shortcode', array( $this, 'render_yht_reviews_settings' ), $this->yht_settings_page_addr, 'default_view_section', $yht_reviews_field_params );

        // Create copy PHP field for templates
        $yht_reviews_field_params = array(
            'type'      => 'copy_php',
            'id'        => 'yht_copy_php',
            'desc'      => 'Click on the field to copy php to paste in theme templates.',
        );
        add_settings_field( 'yht_copy_php_field', 'Copy PHP Code', array( $this, 'render_yht_reviews_settings' ), $this->yht_settings_page_addr, 'default_view_section', $yht_reviews_field_params );

        // Create Leave Review ribbon text field
        $yht_reviews_field_params = array(
            'type'      => 'text', // тип
            'id'        => 'yht_ribbon_text',
            'desc'      => 'Leave ribbon description text here',
        );
        add_settings_field( 'yht_ribbon_text_field', 'Ribbon description', array( $this, 'render_yht_reviews_settings' ), $this->yht_settings_page_addr, 'ribbon_view_section', $yht_reviews_field_params );

        // Create ribbon copy shortcode field
        $yht_reviews_field_params = array(
            'type'      => 'copy_shortcode_ribbon',
            'id'        => 'yht_copy_ribbon_short_code',
            'desc'      => 'Click on the field to copy the ribbon shortcode to paste in pages and posts through the admin panel.',
        );
        add_settings_field( 'yht_copy_ribbon_short_code_field', 'Copy Ribbon Shortcode', array( $this, 'render_yht_reviews_settings' ), $this->yht_settings_page_addr, 'ribbon_view_section', $yht_reviews_field_params );

        // Create copy PHP field for templates
        $yht_reviews_field_params = array(
            'type'      => 'copy_php_ribbon',
            'id'        => 'yht_copy_ribbon_php',
            'desc'      => 'Click on the field to copy php to paste ribbon in theme templates.',
        );
        add_settings_field( 'yht_copy_php_ribbon_field', 'Copy PHP Ribbon Code', array( $this, 'render_yht_reviews_settings' ), $this->yht_settings_page_addr, 'ribbon_view_section', $yht_reviews_field_params );

    }

    /**
     * Render YHT reviews settings.
     *
     * @param array $args
     * @since    1.0.0
     */
    public function render_yht_reviews_settings( $args ) {
        extract( $args );

        $option_name = 'yht_reviews_options';

        $o = get_option( $option_name );

        switch ( $type ) {
            case 'text':
                $o[$id] = esc_attr( stripslashes($o[$id]) );
                echo "<input class='regular-text' type='text' id='$id' name='" . $option_name . "[$id]' value='$o[$id]' />";
                echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";
                break;
            case 'textarea':
                $o[$id] = esc_attr( stripslashes($o[$id]) );
                echo "<textarea class='code large-text' cols='50' rows='10' type='text' id='$id' name='" . $option_name . "[$id]'>$o[$id]</textarea>";
                echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";
                break;
            case 'checkbox':
                $checked = ($o[$id] == 'on') ? " checked='checked'" :  '';
                echo "<label><input type='checkbox' id='$id' name='" . $option_name . "[$id]' $checked /> ";
                echo ($desc != '') ? $desc : "";
                echo "</label>";
                break;
            case 'select':
                echo "<select id='$id' name='" . $option_name . "[$id]'>";
                foreach($vals as $v=>$l){
                    $selected = ($o[$id] == $v) ? "selected='selected'" : '';
                    echo "<option value='$v' $selected>$l</option>";
                }
                echo ($desc != '') ? $desc : "";
                echo "</select>";
                break;
            case 'radio':
                echo "<fieldset>";
                foreach($vals as $v=>$l){
                    $checked = ($o[$id] == $v) ? "checked='checked'" : '';
                    echo "<label><input type='radio' name='" . $option_name . "[$id]' value='$v' $checked />$l</label><br />";
                }
                echo "</fieldset>";
                break;
            case 'copy_shortcode':
                $o[$id] = esc_attr( stripslashes($o[$id]) );
                echo "<input class='regular-text' type='text' id='$id' readonly='readonly' name='" . $option_name . "[$id]' value='[yht_reviews]' onclick='copy_on_click($id)' />";
                echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";
                break;
            case 'copy_php':
                $o[$id] = esc_attr( stripslashes($o[$id]) );
                $php_output = esc_html("echo do_shortcode( '[yht_reviews]' );");
                echo "<input class='regular-text' type='text' id='$id' readonly='readonly' name='" . $option_name . "[$id]' value='$php_output' onclick='copy_on_click($id)' />";
                echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";
                break;
            case 'copy_shortcode_ribbon':
                $o[$id] = esc_attr( stripslashes($o[$id]) );
                echo "<input class='regular-text' type='text' id='$id' readonly='readonly' name='" . $option_name . "[$id]' value='[yht_reviews_ribbon]' onclick='copy_on_click($id)' />";
                echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";
                break;
            case 'copy_php_ribbon':
                $o[$id] = esc_attr( stripslashes($o[$id]) );
                $php_output = esc_html("echo do_shortcode( '[yht_reviews_ribbon]' );");
                echo "<input class='regular-text' type='text' id='$id' readonly='readonly' name='" . $option_name . "[$id]' value='$php_output' onclick='copy_on_click($id)' />";
                echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";
                break;
        }
    }

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/yht-reviews-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/yht-reviews-admin.js', array( 'jquery' ), $this->version, false );

	}


}