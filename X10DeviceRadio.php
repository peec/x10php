<?php

/**
 * X10 Radio Device.
 * Such devices that has radio recievers instead of power line.
 * @author Petter Kjelkenes <kjelkenes@gmail.com>
 *
 */
class X10DeviceRadio extends X10Device{
	
	/**
	 * Send ON signal.
	 */
	public function on(){
		$this->chkFlag(X10Device::F_ON);
		return $this->rawCommand(__METHOD__, func_get_args(), 'On');
	}
	/**
	 * Send OFF signal.
	 */
	public function off(){
		$this->chkFlag(X10Device::F_OFF);
		return $this->rawCommand(__METHOD__, func_get_args(), 'Off');
	}
	
	/**
	 * Send DIM signal.
	 */
	public function dim(){
		$this->chkFlag(X10Device::F_DIM);
		return $this->rawCommand(__METHOD__, func_get_args(), "Dim");
	}
	
	/**
	 * Send Bright signal.
	 */
	public function bright(){
		$this->chkFlag(X10Device::F_BRIGHT);
		return $this->rawCommand(__METHOD__, func_get_args(), "Bright");
	}
	
	
	
	
	
	/**
	 * Runs a raw command
	 * See: Radio Frequency Command Reference in Official SDK for X10.
	 * Example of camera constants: AutoFocus, Zoom
	 * @param string $query example on, off, etc..
	 */
	public function rawCommand($method, $arguments, $query){
		$com = $this->x10ApiExcecutable . ' sendrf ' . $this->code . ' ' . $query;
		$this->log($method, $arguments, $com);
		return shell_exec($com);
	}
}