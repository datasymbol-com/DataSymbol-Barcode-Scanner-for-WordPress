<?php
/*
DataSymbol Barcode Scanner plugin for WordPress.
Provides a very easy way to embed Barcode Scanner in your posts and pages.
https://www.datasymbol.com
Copyright © 1999-2024, RKD Software, All rights reserved. 
*/

include_once( plugin_dir_path(__FILE__) . 'includes/helpers.php' );

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

// Delete any options
delete_site_option( DS_PLUGIN_OPTIONS );

?>

