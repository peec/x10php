<?php

/**
 * X10 Actions happens when any method is ran on a specific device.
 * @author SOPPEN
 *
 */
class X10Action{
	
	/**
	 * 
	 * @var \X10Device
	 */
	private $device;
	private $action;
	private $args;
	private $command;
	
	public function __construct($device, $action, $args, $command){
		$this->device = $device;
		$this->action = $action;
		$this->args = $args;
		$this->command = $command;
	}
	
	
	/**
	 * @return \X10DevicePower
	 */
	public function getDevice(){
		return $this->device;
	}

	/**
	 * Returns the exact command runned.
	 */
	public function getCommand(){
		return $this->command;
	}

	/**
	 * @return string The action performed such as on, off etc.
	 */
	public function getAction(){
		return $this->action;
	}
	

	/**
	 * Gets the device code.
	 */
	public function getDevCode(){
		return $this->device->getCode();
	}
	
	/**
	 * Gets the device type, returns constants of X10Device values.
	 */
	public function getDevType(){
		$type = 0;
		if ($this->device instanceof X10DevicePower){
			$type = X10Device::TYPE_POWER;
		}else{
			$type = X10Device::TYPE_RADIO;
		}
		return $type;
	}
	
	/**
	 * Gets the arguments as array.
	 */
	public function getArgs(){
		return $this->args;
	}
}