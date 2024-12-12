<?php
/*
DataSymbol Barcode Scanner plugin for WordPress.
Provides a very easy way to embed Barcode Scanner in your posts and pages.
https://www.datasymbol.com
Copyright © 1999-2024, RKD Software, All rights reserved. 
*/

include_once( plugin_dir_path(__FILE__) . '/helpers.php' );

//adds "Settings" link
function ds_add_settings_link( $links, $file )
{
	static $this_plugin;
	if ( ! $this_plugin )
		$this_plugin = plugin_basename( __FILE__ );

	if( strpos($file, 'barcode-scanner.php') !== false )
	{
		$settings_link = '<a href="admin.php?page=ds-options">' . __( 'Settings', 'datasymbol-barcode-scanner' ) . '</a>';
		array_unshift( $links, $settings_link );
	}

	return $links;
}

add_filter( 'plugin_action_links', 'ds_add_settings_link', 10, 2 );

function ds_menu()
{
	// Add options sub-menu
	global $ds_options_hook;
	$ds_options_hook = add_submenu_page( 'options-general.php', __( 'DataSymbol Barcode Scanner Settings', 'datasymbol-barcode-scanner' ), __( 'Barcode Scanner', 'datasymbol-barcode-scanner' ), 'manage_options', 'ds-options', 'ds_options' );
	add_action( 'load-' . $ds_options_hook, 'ds_add_options_help' );
}

//adds help tab to the options page
function ds_add_options_help()
{
	global $ds_options_hook;
	$screen = get_current_screen();

	if ( $screen->id !== $ds_options_hook )
		return;

	$screen->add_help_tab(
		array(
			'id'      => 'ds-options-help-tab',
			'title'   => __( 'Help', 'datasymbol-barcode-scanner' ),
			'content' => ds_options_help(),
		)
	);

	$screen->set_help_sidebar( ds_help_sidebar() );
}

add_action( 'admin_menu', 'ds_menu' );

//Define an option screen
function ds_options()
{
	include_once( WP_PLUGIN_DIR . '/' . str_replace(basename(__FILE__), '', plugin_basename(__FILE__)) . 'options-screen.php' );
}

//Return help text for options screen
function ds_options_help()
{
	$help_text  = '<p>For more Information:<br /><a href="http://www.datasymbol.com/barcode-web-sdk/barcode-scanner-plugin-for-wordpress.html" target=_blank>DataSymbol Barcode Decoding WordPress Plugin</a></p>';
	return $help_text;
}

//Return help text for options screen (rigth bar)
function ds_help_sidebar() {
	$help_text  = 'DataSymbol Barcode Scanner help';

	$help_text  = '<p><strong>' . __( 'For more information:', DATASYMBOL_BARCODE_SCANNER ) . '</strong></p>';
	$help_text .= '<p><a href="http://www.datasymbol.com/barcode-web-sdk/barcode-scanner-plugin-for-wordpress.html" target=_blank>' . __( 'DataSymbol for WP', DATASYMBOL_BARCODE_SCANNER ) . '</a></p>';

	return $help_text;
}

