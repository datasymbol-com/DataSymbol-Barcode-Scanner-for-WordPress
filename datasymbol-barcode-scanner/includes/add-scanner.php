<?php
/*
DataSymbol Barcode Scanner plugin for WordPress.
Provides a very easy way to embed Barcode Scanner in your posts and pages.
https://www.datasymbol.com
Copyright © 1999-2024, RKD Software, All rights reserved. 
*/

function ds_filter_replace_macros( &$sScannerCode, &$sonBarcodeJS,  $options, $sBarcodeTypesJS )
{
	$macrosArr = array(
		"{{DS_PLUGIN_PATH}}"		=> DS_PLUGIN_PATH,
		"{{DS_BARCODE_TYPES}}"		=> $sBarcodeTypesJS,
		"{{DS_LICENSE_KEY}}"		=> $options['licenseKey'],
		"{{DS_BEEP}}"			=> $options['beep'],
		"{{DS_BARCODE_AT_POINT}}"	=> $options['barcodeAtPoint'],
		"{{DS_FACING_MODE}}"		=> $options['facingMode'],
		"{{DS_RESX}}"			=> $options['resx'],
		"{{DS_RESY}}"			=> $options['resy'],
		"{{DS_FRAME_TIMEOUT}}"		=> $options['frameTimeout'],
		"{{DS_FRAME_TIME}}"		=> $options['frameTime'],
	);

	$sScannerCode = $options['scannercode'];
	$sonBarcodeJS = $options['onBarcodeJS'];

	//$sScannerCode = str_ireplace( "{{DS_PLUGIN_PATH}}", DS_PLUGIN_PATH, $options['scannercode'] );
	//$sScannerCode = str_ireplace( "{{DS_PLUGIN_PATH}}", DS_PLUGIN_PATH, $options['onBarcodeJS'] );

	foreach( $macrosArr as $m => $val )
	{
		$sScannerCode = str_ireplace( $m, $val, $sScannerCode );
		$sonBarcodeJS = str_ireplace( $m, $val, $sonBarcodeJS );
	}
}

function ds_filter( $content )
{
	//read plugin options
	$options = get_option( DS_PLUGIN_OPTIONS );

	//exit if not scannertag
	if( false === strpos($content, $options['scannertag']) )
		return $content;

	//create JS array with barcode types ($options['barcodeTypes'] == "EAN13, UPCA, Code128, Code39, QRCode")
	$strBarcodeToDecode = "";
	$matches = preg_match_all("/\\b([A-Za-z0-9]{4,})\\b/m", $options['barcodeTypes'], $m);
	if( $matches > 0 )
	{
		foreach($m[0] as $v)
		    $strBarcodeToDecode .= "'" . $v . "',";
	}
	if( $strBarcodeToDecode != "" )  $strBarcodeToDecode = substr( $strBarcodeToDecode, 0, -1 );	//remove last ','

	//format scanner settings variable
	$strScannerSettings = "var bDSMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
	var scannerSettings = {
			scanner: {key: '%s', frameTimeout: %s, barcodeTimeout: %s, beep: %s, barcodeAtPoint: %s, },
			viewport: {id: 'datasymbol-barcode-viewport', width: null, },
			camera: { resx: %s, resy: %s, },
			barcode: { barcodeTypes: [%s], frameTime: %s, },
	};
	if( bDSMobile ) scannerSettings.camera.facingMode = '" . $options['facingMode'] . "';
	";

	$strScannerSettings = sprintf ($strScannerSettings, $options['licenseKey'], $options['frameTimeout'], $options['barcodeTimeout'], ($options['beep']=="1" ? "true":"false"), 
		($options['barcodeAtPoint']=="1" ? "true":"false"), $options['resx'], $options['resy'], $strBarcodeToDecode, $options['frameTime'] );

	//replace
	ds_filter_replace_macros( $sScannerCode, $sonBarcodeJS, $options, $strBarcodeToDecode );

	//replace {{DS_PLUGIN_PATH}}
/*	$sScannerCode = str_ireplace( "{{DS_PLUGIN_PATH}}", DS_PLUGIN_PATH, $options['scannercode'] );
*/
	$strJS = 
	"<script type='text/javascript'>" . $strScannerSettings .
	"function onBarcodeReady (barcodeResult) {" . $sonBarcodeJS . "};" .
	"var DEF_WASM_PATH = '" . DS_SDK_PATH . "/datasymbol-sdk.wasm';" . 
	"</script>";

	$replace =	"<!-- DataSymbol Barcode Scanner start -->\n";
	$replace .=	$strJS;
	$replace .=	$sScannerCode;//$options['scannercode'];
	$replace .=	"<!-- DataSymbol Barcode Scanner stop -->\n";

	$content = str_ireplace( $options['scannertag'], $replace, $content, $c );

	return $content;
}

add_filter( 'the_content', 'ds_filter' );
add_filter( 'widget_content', 'ds_filter' );



