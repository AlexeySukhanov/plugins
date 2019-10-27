<?php
/**
 *
 * Plugin Name: Schema Faq Generator
 * Description: Creates a widget containing a generator that allows you to create structured data for FAQ pages in Schema format.
 * Version: 1.0
 * Author: Alexey Sukhanov
 * Author URI: https://www.upwork.com/fl/sukhanov
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

class Schema_FAQ_Gen_Widget extends WP_Widget {
    // Установка идентификатора, заголовка, имени класса и описания для виджета.
    public function __construct(){
        $widget_options = array(
            'classname' => 'schema_faq_gen',
            'description' => 'Generator allows you to create structured data for FAQ pages in Schema format.'
        );
        parent::__construct( 'schema_faq_gen', 'Schema FAQ Generator', $widget_options );
    }

    // Параметры виджета, отображаемые в области администрирования WordPress.
    public function form( $instance ) {
        $title = ! empty( $instance['title']) ? $instance['title'] : '' ;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
            <input type="text" id="<?php echo $this->get_field_id( 'title' ) ?>"  name="<?php echo $this->get_field_name( 'title' ); ?>"  value="<?php echo esc_attr( $title ); ?>" class="widefat title">
        </p>
        <?php
    }

    // Обновление настроек виджета в админ-панели.
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        return $instance;
    }

    // Вывод виджета в области виджетов на сайте.
    public function widget( $args, $instance ) {
        $title = apply_filters( 'sfg_widget_title', $instance['title'] );

        echo PHP_EOL . $args['before_widget'] . PHP_EOL;
        echo $args['before_title'] . $title . $args['after_title'] . PHP_EOL;
        require_once('tmpl.html');
        echo PHP_EOL . $args['after_widget'] . PHP_EOL;
    }
}

// Регистрация и активация виджета.
function register_sfg_widget() {
    register_widget( 'Schema_FAQ_Gen_Widget' );
}
add_action( 'widgets_init', 'register_sfg_widget' );

// Регистрация и подключение скриптов и стилей
function register_sfg_scripts() {
    wp_enqueue_style( 'sfg-style-css', plugin_dir_url( __FILE__ ) . 'css/sfg-style.css' );
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'jquery-ui-min-js', 'https://code.jquery.com/ui/1.12.0/jquery-ui.min.js', array( 'jquery' )  );
    wp_enqueue_script( 'sfg-script-js', plugin_dir_url( __FILE__ ) . 'js/sfg-script.js', array( 'jquery' ), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'register_sfg_scripts' );