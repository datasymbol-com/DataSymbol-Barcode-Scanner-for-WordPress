<?php
/*
DataSymbol Barcode Scanner plugin for WordPress.
Provides a very easy way to embed Barcode Scanner in your posts and pages.
https://www.datasymbol.com
Copyright © 1999-2024, RKD Software, All rights reserved. 
*/


define( 'DS_SCANNER_VERSION', '1.1' );

//options
define ("DS_PLUGIN_OPTIONS", "ds_barcode_scanner");

//options, version
define ("DS_PLUGIN_OPTIONS_VERSION", "ds_barcode_scanner_version");

//id for translating
define ("DATASYMBOL_BARCODE_SCANNER", "datasymbol-barcode-scanner");

function dirname_r($path, $count=1){
    if ($count > 1){
       return dirname(dirname_r($path, --$count));
    }else{
       return dirname($path);
    }
}

//plugin name
define ("DS_PLUGIN_NAME", basename(dirname_r(__FILE__, 2)));

//relative URL path to the SDK (/wp/wp-content/plugins/datasymbol-barcode-scanner/sdk )
define ( "DS_SDK_PATH", parse_url(WP_PLUGIN_URL . '/' . DS_PLUGIN_NAME . '/sdk', PHP_URL_PATH) );

//relative URL path to the includes (/wp/wp-content/plugins/datasymbol-barcode-scanner/includes )
define ( "DS_INCLUDES_PATH", parse_url(WP_PLUGIN_URL . '/' . DS_PLUGIN_NAME . '/includes', PHP_URL_PATH) );

//relative URL path to the plugin (/wp/wp-content/plugins/datasymbol-barcode-scanner )
define ( "DS_PLUGIN_PATH", parse_url(WP_PLUGIN_URL . '/' . DS_PLUGIN_NAME, PHP_URL_PATH) );

//relative URL path to the SDK
//define ("DS_SDK_PATH", get_site_url(null, '/sdk', 'relative'));


$DS_DEFAULT_SETTINGS = array(
	'scannertag'		=> '{{DataSymbolScanner}}',
	'scannercode'		=>	"<script defer type='text/javascript' src='" . DS_SDK_PATH  . "/datasymbol-sdk-hlp.min.js'></script>\n" .
					"<script src='" . DS_SDK_PATH . "/main.js'></script>\n" .
					"<p id='status'>Downloading ...</p>\n" .
					"<div id='datasymbol-barcode-viewport' style='display:block;width:100%;'></div>",

	'onBarcodeJS'		=>	"var barDataEl = document.getElementById('status');\n" .
					"for (var i = 0; i < barcodeResult.length; ++i) {\n" .
                    "	if(!barcodeResult.at(i).barcodeAtPoint)\n" .
                    "		continue;\n" .
					"	var sBarcode = DSScanner.bin2String(barcodeResult.at(i));\n" .
					"	barDataEl.innerHTML = barcodeResult.at(i).type + ': ' + sBarcode;\n" .
					"}",
	'barcodeTypes'		=> 'EAN13, UPCA, Code128, Code39, QRCode',
	'beep'			=> '1',
	'barcodeAtPoint'	=> '0',
	'facingMode'		=> 'environment',
	'resx'			=> '640',
	'resy'			=> '480',
	'frameTimeout'		=> '100',
	'barcodeTimeout'	=> '1000',
	'frameTime'		=> '200',
	'licenseKey'		=> '',
);



function ds_initialize()
{
	global $DS_DEFAULT_SETTINGS;

	//reed version from WP DB
	$version = get_option( DS_PLUGIN_OPTIONS_VERSION );

	//now working newer version than saved settings (or first start)
	if ( DS_SCANNER_VERSION !== $version )
	{
		// Set up default option values (if not already set)
		$options = get_option( DS_PLUGIN_OPTIONS );

		// If options don't exist, create an empty array
		if( !is_array( $options ) )
			$options = array();

		//merge existing and default options
		$new_options = array_merge( $DS_DEFAULT_SETTINGS, $options );

		//saves options in WP DB
		if ( $options !== $new_options )
			update_option( DS_PLUGIN_OPTIONS, $new_options );

		//saves new version
		update_option( DS_PLUGIN_OPTIONS_VERSION, DS_SCANNER_VERSION );
	}
}
