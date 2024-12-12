/*
DataSymbol Barcode Scanner plugin for WordPress.
Provides a very easy way to embed Barcode Scanner in your posts and pages.
https://www.datasymbol.com
Copyright © 1999-2024, RKD Software, All rights reserved. 
*/

var camDevices=null;
var bIsIOS = ['iPad', 'iPhone', 'iPod'].includes(navigator.platform) ||
				// iPad on iOS detection
				(navigator.userAgent.includes("Mac") && "ontouchend" in document);
var camName = ['0, facing back', 'facing back:0', 'back camera'];

function onError(err)
{
	var statusElement = document.getElementById('status');
	var msg = err.message;
	if( err.name == 'CannotEnumDevices')
		msg += ' check HTTPS';
	statusElement.innerHTML = 'Error (' + err.name + '): ' + msg;
}

function SelectBestCamera( camArr, cams )
{
	var _result = -1;
	camArr.forEach(function (c) { c.label = c.label.toLowerCase(); });
	cams.forEach(function (c) { c = c.toLowerCase(); });
	for( var i=0; i < camArr.length; ++i )
	{
		for( var j=0; j < cams.length; ++j )
		{
			if( camArr[i].label.includes(cams[j]) )
				return i;
		}
	}
	return _result;
}

function CreateScanner()
{
	var barDataEl = document.getElementById( 'datasymbol-barcode-viewport' );
	if( barDataEl == null )
		return;

	DSScanner.addEventListener('onBarcode', onBarcodeReady);

	DSScanner.addEventListener('onScannerReady', function () {
		console.log('HTML onScannerReady');
		var statusElement = document.getElementById('status');
		if(statusElement != null) statusElement.innerHTML = ' ';
		DSScanner.StartScanner();
	});

	if( camDevices && camDevices.length > 0 )
	{
		//mobile
		if( bDSMobile || bIsIOS )
		{
			if( scannerSettings.camera.facingMode == 'environment')
			{
				var camIdx = SelectBestCamera( camDevices, camName );
				if( camIdx >= 0 )
				{
					scannerSettings.camera.id = camDevices[camIdx].id;
					scannerSettings.camera.facingMode = null;
				}
			}
		}
		//non mobile, select first camera
		else
		{
			scannerSettings.camera.id = camDevices[0].id
			scannerSettings.camera.label = camDevices[0].label;
		}
	}

	DSScanner.Create(scannerSettings);
}

function OnResize()
{
	document.getElementById('datasymbol-barcode-viewport').setAttribute('style', 'margin:0;padding:0');	//wp for unknown reasons changes style of video element (sets height:0)
}

function OnWindowLoad()
{
	window.addEventListener('resize', OnResize, false);

	DSScanner.addEventListener('onError', onError);

	DSScanner.getVideoDevices(function (devices) {
		/*devices.forEach(function (device) {
			console.log("device:" + device.label + '|' + device.id);
		});*/
		if(devices.length > 0)
		{
			camDevices = devices.slice();
			CreateScanner();
		}
		else
		{
			onError( {name: 'NotFoundError', message: ''} );
		}
	}, true);
}

window.addEventListener("load", OnWindowLoad);
