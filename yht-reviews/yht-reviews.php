<?php
/**
 * YHT Reviews plugin
 *
 * @link              https://yourhighesttruth.com
 * @since             1.2.1
 * @package           Yht_Reviews
 *
 * @wordpress-plugin
 * Plugin Name:       YHT Reviews
 * Plugin URI:        https://yourhighesttruth.com
 * Description:       Allows to create a list of reviews and display it in two ways using shortcodes.
 * Version:           1.0.0
 * Author:            yourhighesttruth.com
 * Author URI:        https://yourhighesttruth.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       yht-reviews
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'YHT_REVIEWS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-yht-reviews-activator.php
 */
function activate_yht_reviews() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-yht-reviews-activator.php';
	Yht_Reviews_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-yht-reviews-deactivator.php
 */
function deactivate_yht_reviews() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-yht-reviews-deactivator.php';
	Yht_Reviews_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_yht_reviews' );
register_deactivation_hook( __FILE__, 'deactivate_yht_reviews' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-yht-reviews.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_yht_reviews() {

	$plugin = new Yht_Reviews();
	$plugin->run();

}
run_yht_reviews();


