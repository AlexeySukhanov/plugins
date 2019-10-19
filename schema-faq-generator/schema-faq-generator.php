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


add_action('admin_head', 'echo_lol');
function echo_lol()
{
    echo <<<STL
<style>
body {
    background-color: red;
}
</style>
STL;
}

