<?php
/*
	Plugin Name: Floorplans (Entrata API)
	Plugin URI: https://elod.in
    Description: Just another floorplans plugin
	Version: 0.1
    Author: Jon Schroeder
    Author URI: https://elod.in

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU General Public License for more details.
*/


/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
    die( "Sorry, you are not allowed to access this page directly." );
}

// Plugin directory
define( 'ENTRATA_FLOORPLANS', dirname( __FILE__ ) );

// Define the version of the plugin
define ( 'ENTRATA_FLOORPLANS_VERSION', '0.1' );

// require_once( 'lib/pretty-print.php' );
require_once( 'lib/shortcode.php' );
require_once( 'lib/fallback-if-no-id.php' );

//* Layout
require_once( 'layout/default.php' );

add_action( 'wp_enqueue_scripts', 'entrata_enqueue_scripts_styles' );
function entrata_enqueue_scripts_styles() {

	// Plugin styles
    wp_register_style( 'entrata-floorplans', plugin_dir_url( __FILE__ ) . 'css/entrata-floorplans.css', array(), ENTRATA_FLOORPLANS_VERSION, 'screen' );
    
    // Script
    // wp_register_script( 'slick-init', plugin_dir_url( __FILE__ ) . 'js/slick-init.js', array( 'slick-main' ), ENTRATA_FLOORPLANS_VERSION, true );
	
	
}

//* Add the updater
require 'vendor/plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/jonschr/entrata-floorplans-api',
	__FILE__,
	'entrata-floorplans-api'
);

// Optional: Set the branch that contains the stable release.
$myUpdateChecker->setBranch('master');