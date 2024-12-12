<?php
/*
Plugin Name: DataSymbol Barcode Scanner for WordPress
Plugin URI: https://www.datasymbol.com/barcode-web-sdk/barcode-scanner-plugin-for-wordpress.html
Description: DataSymbol Barcode Scanner provides a very easy way to embed Barcode Scanner in your posts and pages.
Version: 1.1
Author: RKD Software
Author URI: https://www.datasymbol.com
Text Domain: datasymbol-barcode-scanner
*/

$includesDir = plugin_dir_path( __FILE__ ) . 'includes/';
include_once( $includesDir . 'helpers.php' );

add_action( 'init', 'ds_initialize' );


if( is_admin() )
	include_once( $includesDir . 'admin-config.php' );	//when WP admin page
else
	include_once( $includesDir . 'add-scanner.php' );	//insert scanner in page/post if (scanner tag exists)


