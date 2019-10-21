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

/* Виджет WPSchool Widget */
class wpschool_example_widget extends WP_Widget {

    // Установка идентификатора, заголовка, имени класса и описания для виджета.
    public function __construct() {
        $widget_options = array(
            'classname' => 'wpschool_widget',
            'description' => 'Это наш первый виджет',
        );
        parent::__construct( 'wpschool_widget', 'WPSchool Widget', $widget_options );
    }

    // Вывод виджета в области виджетов на сайте.
    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance[ 'title' ] );
        $blog_title = get_bloginfo( 'name' );
        $tagline = get_bloginfo( 'description' );

        echo $args['before_widget'] . $args['before_title'] . $title . $args['after_title']; ?>
        <p><strong>Site Name:</strong> <?php echo $blog_title ?></p>
        <p><strong>Tagline:</strong> <?php echo $tagline ?></p>
        <?php echo $args['after_widget'];
    }

    // Параметры виджета, отображаемые в области администрирования WordPress.
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : ''; ?>
        <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
        <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>" />
        </p><?php
    }

    // Обновление настроек виджета в админ-панели.
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
        return $instance;
    }

}

// Регистрация и активация виджета.
function wpschool_register_widget() {
    register_widget( 'wpschool_example_widget' );
}
add_action( 'widgets_init', 'wpschool_register_widget' );




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
//         $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
         //$title = 'Schema FAQ Generator';

         echo PHP_EOL;
         echo $args['before_widget'];
            echo PHP_EOL;
                echo $args['before_title'] . $title . $args['after_title'];
             echo PHP_EOL;
                echo 'content';
             echo PHP_EOL;
         echo $args['after_widget'];
         echo PHP_EOL;
    }
}


// Регистрация и активация виджета.
function schema_faq_gen_register_widget() {
    register_widget( 'Schema_FAQ_Gen_Widget' );
}
add_action( 'widgets_init', 'schema_faq_gen_register_widget' );