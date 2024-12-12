<?php
/*
DataSymbol Barcode Scanner plugin for WordPress.
Provides a very easy way to embed Barcode Scanner in your posts and pages.
https://www.datasymbol.com
Copyright © 1999-2024, RKD Software, All rights reserved. 
*/


include_once( plugin_dir_path(__FILE__) . '/helpers.php' );
?>

<div class="wrap">
<a href='http://www.DataSymbol.com' target='_blank'><img src="<?php echo DS_INCLUDES_PATH . "/logo-main.png" ?>" alt="www.DataSymbol.com"></a>
<h1><?php echo esc_html( ucwords('DataSymbol Barcode Scanner Options') ); ?></h1>

<?php

// If options have been updated on screen, update the database
if( !empty($_POST) && check_admin_referer('datasymbol-barcode-scanner-profile', 'dataSymbol_barcode_scanner_profile_nonce') )
{
	if ( !empty( $_POST['d_b_s_scannertag'] ) )
		$options['scannertag'] = trim(sanitize_text_field(wp_unslash($_POST['d_b_s_scannertag'])));

	if ( !empty( $_POST['d_b_s_scannercode'] ) )
		$options['scannercode'] = trim(wp_unslash($_POST['d_b_s_scannercode']));

	if ( !empty( $_POST['d_b_s_onBarcodeJS'] ) )
		$options['onBarcodeJS'] = trim(wp_unslash($_POST['d_b_s_onBarcodeJS']));

	if ( !empty( $_POST['d_b_s_barcodeTypes'] ) )
		$options['barcodeTypes'] = trim(wp_unslash($_POST['d_b_s_barcodeTypes']));

	if ( !empty( $_POST['d_b_s_licenseKey'] ) )
		$options['licenseKey'] = preg_replace("/[^A-Za-z0-9+\\/=\\-]/", "", wp_unslash($_POST['d_b_s_licenseKey']));

	if ( !empty( $_POST['d_b_s_resx'] ) )
		$options['resx'] = preg_replace("/[^0-9]/", "", $_POST['d_b_s_resx']);

	if ( !empty( $_POST['d_b_s_resy'] ) )
		$options['resy'] = preg_replace("/[^0-9]/", "", $_POST['d_b_s_resy']);

	if ( !empty( $_POST['d_b_s_facingMode'] ) )
		$options['facingMode'] = trim(sanitize_text_field(wp_unslash($_POST['d_b_s_facingMode'])));

	if ( !empty( $_POST['d_b_s_frameTimeout'] ) )
		$options['frameTimeout'] = preg_replace("/[^0-9]/", "", $_POST['d_b_s_frameTimeout']);

	if ( !empty( $_POST['d_b_s_barcodeTimeout'] ) )
		$options['barcodeTimeout'] = preg_replace("/[^0-9]/", "", $_POST['d_b_s_barcodeTimeout']);

	if ( !empty( $_POST['d_b_s_frameTime'] ) )
		$options['frameTime'] = preg_replace("/[^0-9]/", "", $_POST['d_b_s_frameTime']);

	if ( ! empty( $_POST['d_b_s_beep'] ) )
		$options['beep'] = sanitize_text_field( wp_unslash( $_POST['d_b_s_beep'] ) );
	else
		$options['beep'] = '0';

	if ( ! empty( $_POST['d_b_s_barcodeAtPoint'] ) )
		$options['barcodeAtPoint'] = sanitize_text_field( wp_unslash( $_POST['d_b_s_barcodeAtPoint'] ) );
	else
		$options['barcodeAtPoint'] = '0';

	//save options in DB
	update_option( DS_PLUGIN_OPTIONS, $options );

	echo '<div class="updated fade"><p><strong>' . esc_html( __( 'Settings saved.', DATASYMBOL_BARCODE_SCANNER ) ) . "</strong></p></div>\n";
}

//read options
$options = get_option( DS_PLUGIN_OPTIONS );

?>

<form method="POST" action="<?php echo esc_html(get_bloginfo('wpurl')) . '/wp-admin/options-general.php?page=ds-options'; ?>">

<table class="form-table">

<tr>
<th scope="row"><label for="d_b_s_scannertag"><?php echo esc_html(ucwords(__( 'Scanner tag', DATASYMBOL_BARCODE_SCANNER))); ?></label></th>
<td><input type="text" size="25" maxlength="25" name="d_b_s_scannertag" value="<?php echo esc_html( $options['scannertag'] ); ?>" /></td>
</tr>

<tr>
<th scope="row"><label for="d_b_s_onBarcodeJS"><?php echo esc_html('onBarcode JavaScript'); ?></label></th>
<td><textarea cols="100" rows="5" name="d_b_s_onBarcodeJS" /><?php echo esc_html($options['onBarcodeJS']); ?></textarea></td>
</tr>

<tr>
<th scope="row"><label for="d_b_s_barcodeTypes"><?php echo esc_html(ucwords(__( 'Barcode Types', DATASYMBOL_BARCODE_SCANNER))); ?></label></th>
<td><input type="text" size="70" name="d_b_s_barcodeTypes" value="<?php echo esc_html( $options['barcodeTypes'] ); ?>" /><p class="description"><?php esc_html_e('barcode types are case sensitive, full list: ', DATASYMBOL_BARCODE_SCANNER); print "<a href='http://www.datasymbol.com/barcode-web-sdk/barcode-types.html' target=_blank>Barcode Types</a>"; ?></p></td>
</tr>

<tr>
<th scope="row"><label for="d_b_s_resx"><?php echo esc_html(ucwords(__( 'Frame size', DATASYMBOL_BARCODE_SCANNER))); ?></label></th>
<td>
	<input type="text" size="4" maxlength="4" name="d_b_s_resx" value="<?php echo esc_html( $options['resx'] ); ?>" /> x 
	<input type="text" size="4" maxlength="4" name="d_b_s_resy" value="<?php echo esc_html( $options['resy'] ); ?>" /> 
</td>
</tr>

<tr>
<th scope="row"><label for="d_b_s_facingMode"><?php echo esc_html(__( 'Camera (for mobile)', DATASYMBOL_BARCODE_SCANNER)); ?></label></th>
<td>
	<select name="d_b_s_facingMode">
	  <option value="environment" <?php echo $options['facingMode']=='environment'?'selected':''; ?>>Facing Back</option>
	  <option value="user" <?php echo $options['facingMode']=='user'?'selected':''; ?> >Facing Front</option>
	</select>
</td>
</tr>

<tr>
<th scope="row"><label for="d_b_s_frameTimeout"><?php echo esc_html(ucwords(__( 'Frame Timeout (ms)', DATASYMBOL_BARCODE_SCANNER))); ?></label></th>
<td><input type="text" size="5" maxlength="5" name="d_b_s_frameTimeout" value="<?php echo esc_html( $options['frameTimeout'] ); ?>" /></td>
</tr>

<tr>
<th scope="row"><label for="d_b_s_barcodeTimeout"><?php echo esc_html(ucwords(__( 'Barcode Timeout (ms)', DATASYMBOL_BARCODE_SCANNER))); ?></label></th>
<td><input type="text" size="5" maxlength="5" name="d_b_s_barcodeTimeout" value="<?php echo esc_html( $options['barcodeTimeout'] ); ?>" /></td>
</tr>

<tr>
<th scope="row"><label for="d_b_s_frameTime"><?php echo esc_html(ucwords(__( 'Frame Decoding Time (ms)', DATASYMBOL_BARCODE_SCANNER))); ?></label></th>
<td><input type="text" size="5" maxlength="5" name="d_b_s_frameTime" value="<?php echo esc_html( $options['frameTime'] ); ?>" /></td>
</tr>

<tr>
<th scope="row"><label for="d_b_s_beep">Scanner</label></th>
<td>
	<input type="checkbox" name="d_b_s_beep" value="1"<?php checked( '1', $options['beep'] ); ?> /> Beep
	<br>
	<input type="checkbox" name="d_b_s_barcodeAtPoint" value="1"<?php checked( '1', $options['barcodeAtPoint'] ); ?> /> Barcode at Point
</td>
</tr>

<tr>
<th scope="row"><label for="d_b_s_scannercode"><?php echo esc_html(ucwords(__('Scanner Code', DATASYMBOL_BARCODE_SCANNER))); ?></label></th>
<td><textarea cols="100" rows="5" name="d_b_s_scannercode" /><?php echo esc_html($options['scannercode']); ?></textarea></td>
</tr>

<tr>
<th scope="row"><label for="d_b_s_licenseKey"><?php echo esc_html(ucwords(__( 'License Key', DATASYMBOL_BARCODE_SCANNER))); ?></label></th>
<td><input type="text" size="70" name="d_b_s_licenseKey" value="<?php echo esc_html( $options['licenseKey'] ); ?>" /></td>
</tr>

</table>

<?php wp_nonce_field( 'datasymbol-barcode-scanner-profile', 'dataSymbol_barcode_scanner_profile_nonce', true, true ); ?>

<br/><input type="submit" name="Submit" class="button-primary" value="<?php echo esc_html( ucwords( __( 'Save changes', DATASYMBOL_BARCODE_SCANNER ) ) ); ?>"/>

</form>

<?php

// How to embed

echo '<br/><h3>' . esc_html( ucwords( __( 'How to embed DataSymbol Barcode Scanner', DATASYMBOL_BARCODE_SCANNER ) ) ) . "</h3>\n";

echo '<p>' . 'Insert "<b>Scanner Tag</b>" <i>{{DataSymbolScanner}}</i> in your WordPress post or page. Change "<b>Barcode Types</b>". Select only the types you need. ' . 
	'Change the "<b>onBarcode JavaScript</b>" (this JavaScript code will be called when the barcode is decoded).<br />' . 
	'Your WordPress site must have a secure HTTPS connection (an SSL Certificate).  This is a requirement for all modern browsers to work with your camera when using this plugin.<br /><br />' . 

	'In "<i>onBarcode</i>" and "<i>Scanner Code</i>" you can use the following macros:<br />' .
	'<i>{{DS_PLUGIN_PATH}}, {{DS_BARCODE_TYPES}}, {{DS_LICENSE_KEY}}, {{DS_BEEP}}, {{DS_BARCODE_AT_POINT}}, {{DS_FACING_MODE}}, {{DS_RESX}}, {{DS_RESY}}, {{DS_FRAME_TIMEOUT}}, {{DS_FRAME_TIME}}</i><br /><br />' .

	'For more Information:<br /><a href="http://www.datasymbol.com/barcode-web-sdk/barcode-scanner-plugin-for-wordpress.html" target=_blank>DataSymbol Barcode Decoding WordPress Plugin</a><br />' . 
	'<a href="http://www.datasymbol.com/barcode-web-sdk/scanner-settings.html" target=_blank>Web SDK ScannerSettings</a>' . "</p>\n";
?>

</div>



