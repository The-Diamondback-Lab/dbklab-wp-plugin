<?php
/**
 * Plugin Name: DBK Lab Plugin
 * Description: Custom routes and functions for the DBK Lab
 * Version: 0.1
 * Author: The Diamondback Lab
 * Author URI: https://github.com/The-Diamondback-Lab
 */

if (!defined('ABSPATH')) {
    exit();
}

require plugin_dir_path(__FILE__) . 'class-custom-routes.php';

$GLOBALS['dbklab_plugin_custom_routes'] = DBK_Custom_Routes::get_instance();
