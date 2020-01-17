<?php


/**
 * Plugin Name:       EGPL
 * Plugin URI:        https://github.com/QasimRiaz/EGPL
 * Description:       EGPL
 * Version:           3.65
 * Author:            EG
 * License:           GNU General Public License v2
 * Text Domain:       EGPL
 * Network:           true
 * GitHub Plugin URI: https://github.com/QasimRiaz/EGPL
 * Requires WP:       5.0.3
 * Requires PHP:      7.2
 * Date 11/02/2019
 */

//get all the plugin settings
//get all the plugin settings


require plugin_dir_path( __DIR__ ) . 'EGPL/includes/egpl_requests.php';
require plugin_dir_path( __DIR__ ) . 'EGPL/includes/egpl_plugin_activations.php';
require plugin_dir_path( __DIR__ ) . 'EGPL/includes/egpl_custome_request_handler_function.php';
require plugin_dir_path( __DIR__ ) . 'EGPL/includes/zapier_integration.php';
require plugin_dir_path( __DIR__ ) . 'EGPL/includes/levelbased_addons.php';