<?php

/**
 *
 * Plugin Name: Test Plugin
 * Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
 * Description: A brief description of the Plugin.
 * Version: 1.0
 * Author: Alexey Sukhanov
 * Author URI: https://www.upwork.com/fl/sukhanov
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

add_action( 'admin_head', 'echo_lol' );
function echo_lol(){
echo <<<STL
<style>
body {
    background-color: red;
}
</style>    
STL;
}

