<?php
/**
 * @author Petter Kjelkenes <kjelkenes@gmail.com>
 * @license LGPL
 */

// Try running this in your browser.. Try adding some sleep(4) functions on some lines.
// Remember to change "C:\ahsdk\bin\ahcmd.exe" to where you have installed the X10 SDK!


// Include the library
include 'bootstrap.php';


// Create the object
$x10 = new X10('C:\Program Files (x86)\AHSDK\bin\ahcmd.exe');


/**
 * Add the first device, this can be our ceiling lights.
 */

// Add all devices you have ( codes )
$x10->addPowerDevice('a1');

// Only allow certain commands.( This is recommended but not required ).
// Forexample, you don't want to dim appliance modules.
// To see what features is available, you can: print_r(X10Device::getFeatures());
$x10->dev('a1')->setAllowedFeatures(X10Device::FG_ON_OFF | X10Device::FG_DIM_BRIGHT);


// Turn 'a1' off.
$x10->dev('a1')->off();
// Turn 'a1' on!
$x10->dev('a1')->on();

// Dim the device.
$x10->dev('a1')->dim(50);

// Extended code.
$x10->dev('a1')->extendedCode('A1', '0F');


/**
 * Add the 2th device, this can be our TV.
 */


// Now add appliance module for the TV.
$x10->addPowerDevice('a2')->setAllowedFeatures(X10Device::FG_ON_OFF);

$a2 = $x10->dev('a2');
// Turn TV on.
$a2->on();
// and TV off.
$a2->off();




// This is NOT allowed.
try{
	$a2->dim(50);
}catch(\Exception $e){
	echo "This is a correct exception: {$e->getMessage()}";
}
