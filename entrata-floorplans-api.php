<?php
/*
	Plugin Name: Floorplans (Entrata API)
	Plugin URI: https://github.com/jonschr/entrata-floorplans-api
    Description: Just another Entrata floorplans plugin
	Version: 1.4.2
    Author: Brindle Digital
    Author URI: https://www.brindledigital.com/

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
define ( 'ENTRATA_FLOORPLANS_VERSION', '1.4.2' );

//* Shortcode
require_once( 'lib/shortcode.php' );
require_once( 'lib/fallback-if-no-id.php' );

//* Layout
require_once( 'layout/default.php' );

//* Filters
require_once( 'lib/filters.php' );

//* Enqueues
add_action( 'wp_enqueue_scripts', 'entrata_enqueue_scripts_styles' );
function entrata_enqueue_scripts_styles() {

    wp_register_style( 'entrata-floorplans', 
        plugin_dir_url( __FILE__ ) . 'css/entrata-floorplans.css',
        array(),
        ENTRATA_FLOORPLANS_VERSION,
        'screen' 
    );
    
    wp_register_style( 'entrata-fancybox-theme',
        plugin_dir_url( __FILE__ ) . '/vendor/fancybox/dist/jquery.fancybox.min.css',
        array(),
        ENTRATA_FLOORPLANS_VERSION,
        'screen' 
    );
    
    wp_register_script( 'entrata-fancybox-main',
        plugin_dir_url( __FILE__ ) . '/vendor/fancybox/dist/jquery.fancybox.min.js',
        array( 'jquery' ),
        ENTRATA_FLOORPLANS_VERSION,
        true 
    );
    
    wp_register_script( 'entrata-filters',
        plugin_dir_url( __FILE__ ) . '/js/filters.js',
        array( 'jquery' ),
        ENTRATA_FLOORPLANS_VERSION,
        true 
    );
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