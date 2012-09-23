<?php

/**
 * Main communication class for X10 communication.
 * See constructor.
 * @author Petter Kjelkenes <kjelkenes@gmail.com>
 *
 */
class X10{
	
	private $x10ApiExcecutable;
	
	private $callbackLogger;
	private $devices = array();
	
	
	/**
	 * Constructs the communication class.
	 * @param string $executable The full path to where the X10 Scripting binary interface is.
	 * Example: C:\x10sdk\ahcmd.exe  or /home/bin/x10sdk/ahcmd
	 * 
	 */
	public function __construct($executable, $loggerCallback = null){
		if (empty($executable) || !is_executable($executable))throw new \Exception("Executable not found or is not accessible to web server.");
		$this->x10ApiExcecutable = strstr($executable, ' ') ? '"'.$executable.'"' : $executable;
		if ($loggerCallback !== null)$this->setCallback($loggerCallback);
	}
	
	/**
	 * Sets a logger callback. Argument fo X10Action is given to the arguments of the callable.
	 * @param closure $loggerCallback
	 */
	public function setCallback($loggerCallback){
		if (!is_callable($loggerCallback))throw new \Exception("Callback must be callable.");
		$this->callbackLogger = $loggerCallback;
	}
	
	
	
	/**
	 * Adds a new power device wich goes over the powerline.
	 * @param string $deviceCode 2 char device ID.
	 */
	public function addPowerDevice($deviceCode){
		return $this->assertAddValidateDevice($deviceCode, X10Device::TYPE_POWER);
	}
	
	/**
	 * Adds a new radio device. Goes over RF sender.
	 * @param string $deviceCode 2 char device ID.
	 */
	public function addRadioDevice($deviceCode){
		return $this->assertAddValidateDevice($deviceCode, X10Device::TYPE_RADIO);
	}
	
	
	/**
	 * Turns all the applicable lights on.
	 * @throws \Exception If no units exception is thrown.
	 */
	public function allLightsOn(){
		if (count($this->devices) == 0)throw new \Exception("No lights to turn on.");
		$code = array_keys($this->devices);
		$code = $code[0];
		$com = $this->x10ApiExcecutable . " sendplc $code alllightson";
		$this->dev($code)->log(__METHOD__, func_get_args(), $com);
		$result = trim(shell_exec($com));
		return $result;
	}
	
	/**
	 * Turns all units off.
	 * @throws \Exception If no units exception is thrown.
	 */
	public function allUnitsOff(){
		if (count($this->devices) == 0)throw new \Exception("No units to turn off.");
		$code = array_keys($this->devices);
		$code = $code[0];
		$com = $this->x10ApiExcecutable . " sendplc $code allunitsoff";
		
		$this->dev($code)->log(__METHOD__, func_get_args(), $com);
		$result = trim(shell_exec($com));
		return $result;
	}
	
	
	

	
	
	/**
	 * Helper method to validate and add device.
	 * @param unknown_type $deviceCode
	 * @param int Device type. See X10Device constants. TYPE_*
	 * @throws \Exception
	 */
	private function assertAddValidateDevice($deviceCode, $type){
		if (!$this->isX10CodeValid($deviceCode))throw new \Exception("Device code: ($deviceCode) is incorrect. Must be a-p[1-16], example: a2, b4, e16 etc.");
		if (isset($this->devices[$deviceCode]))throw new \Exception("Device with code: ($deviceCode) already exists. Multiple devices with same device code is not allowed.");
		switch($type){
			case X10Device::TYPE_POWER:
				$this->devices[$deviceCode] = new X10DevicePower($this->x10ApiExcecutable, $deviceCode, $this->callbackLogger);
				break;
			case X10Device::TYPE_RADIO:
				$this->devices[$deviceCode] = new X10DeviceRadio($this->x10ApiExcecutable, $deviceCode, $this->callbackLogger);
				break;
			default: throw new \Exception("Incorrect devices type. Must be one of constants in X10Device.");
		}
		return $this->devices[$deviceCode];
	}
	
	public function isX10CodeValid($code){
		return preg_match('/^[a-p]{1}([1-9]|10|11|12|13|14|15|16)$/', $code);
	}
	
	
	
	/**
	 * Gets a given device by its device code.
	 * @param unknown_type $deviceCode
	 * @throws \Exception
	 * @return X10DevicePower
	 */
	public function dev($deviceCode){
		if (!isset($this->devices[$deviceCode]))throw new \Exception("Device $deviceCode does not exist in list of devices. Use addPowerDevice / addRadioDevice methods to add devices.");
		
		return $this->devices[$deviceCode];
	}
	
	
	
	public function getDevices(){
		return $this->devices;
	}
	
}


