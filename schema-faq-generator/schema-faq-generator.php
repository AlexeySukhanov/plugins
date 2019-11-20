<?php
/**
 *
 * Plugin Name: Schema Faq Generator
 * Description: Creates a widget containing a generator that allows you to create structured data for FAQ pages in Schema format.
 * Version: 2.3
 * Author: Alexey Sukhanov
 * Author URI: https://www.upwork.com/fl/sukhanov
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

class Schema_FAQ_Gen_Widget extends WP_Widget {

    // Set identifier, title, class name and description for the widget
    public function __construct(){
        $widget_options = array(
            'classname' => 'schema_faq_gen',
            'description' => 'Generator allows you to create structured data for FAQ pages in Schema format.'
        );
        parent::__construct( 'schema_faq_gen', 'Schema FAQ Generator', $widget_options );
    }

    // Widget options displayed in the WordPress admin area
    public function form( $instance ) {
        $title = ! empty( $instance['title']) ? $instance['title'] : '' ;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
            <input type="text" id="<?php echo $this->get_field_id( 'title' ) ?>"  name="<?php echo $this->get_field_name( 'title' ); ?>"  value="<?php echo esc_attr( $title ); ?>" class="widefat title">
        </p>
        <?php
    }

    // Update widget settings in the admin panel
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        return $instance;
    }

    // Display widget in frontend widget area
    public function widget( $args, $instance ) {
        $title = apply_filters( 'sfg_widget_title', $instance['title'] );
        echo PHP_EOL . $args['before_widget'] . PHP_EOL;
        echo $args['before_title'] . $title . $args['after_title'] . PHP_EOL;
        require_once('tmpl.html');
        echo PHP_EOL . $args['after_widget'] . PHP_EOL;
    }
}

// Register and activate the widget
function register_sfg_widget() {
    register_widget( 'Schema_FAQ_Gen_Widget' );
}
add_action( 'widgets_init', 'register_sfg_widget' );

//add shortcode
function add_sfg_shortcode( $atts ){
    $tmpl = file_get_contents(plugin_dir_url( __FILE__ ) . 'tmpl.html');
    return $tmpl;
}
add_shortcode( 'sfg', 'add_sfg_shortcode' );

// Register and connect scripts and styles
function register_sfg_scripts() {
    wp_enqueue_style( 'sfg-style-css', plugin_dir_url( __FILE__ ) . 'css/sfg-style.css' );
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'jquery-ui-min-js', plugin_dir_url( __FILE__ ) . 'vendors/jquery-ui.min.js', array( 'jquery' )  );
    wp_enqueue_script( 'autosize-min-js', plugin_dir_url( __FILE__ ) . 'vendors/autosize.min.js', array( 'jquery' )  );
    wp_enqueue_script( 'sfg-core-js', plugin_dir_url( __FILE__ ) . 'js/sfg-core.js', array( 'jquery' ), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'register_sfg_scripts' );