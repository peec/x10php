# X10PHP

This API is useful if you want to program your own home, lights alarm systems and more. Using this API you can create your own custom website and control your home from anywhere.

This is a wrapper around the scripting SDK for developers.


### Example of use:

	<?php
	// Include the library.
	include 'x10/bootstrap.php';
	// Path to the ahcmd binary.
	$x10 = new X10('C:\ahsdk\bin\ahcmd.exe');
	// Add all devices, this should be drawn from some kind of db if you don't want to do this manually.
	$x10->addPowerDevice('a1');
	$x10->addPowerDevice('c4');

	// Dim 'a1' to 50%
	$x10->dev('a1')->dim(50);

	// Turn off 'c4'
	$x10->dev('c4')->off();

### What do I need?

Instructions to get going quickly!

    Apache or any webserver. ( Windows users can download Xampp or just apache + php )
    X10 Official SDK: http://www.activehomepro.com/sdk/sdk-info.html ( download and install ).
    X10 USB interface ( such as CM15A, CM15PRO, CM11A etc. )
    1 lamp module if you want to test it ( such as LM12 ). 

	
### License

This API wrapper is free and is licensed under LGPL. Use it for whatever you want. Credits to Petter Kjelkenes must be visible to end-users and developers.
