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
define ( 'ENTRATA_FLOORPLANS_API', '0.1' );


// require_once( 'lib/pretty-print.php' );
require_once( 'lib/shortcode.php' );

//* Layout
require_once( 'layout/default.php' );