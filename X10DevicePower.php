<?php
/**
 * A generic Power device module.
 * Such devices that communicate over the powerline.
 * @author Petter Kjelkenes <kjelkenes@gmail.com>
 *
 */
class X10DevicePower extends X10Device{
	
	
	/**
	 * Runs a raw command
	 * @param string $query example on, off, etc..
	 */
	public function rawCommand($method, $args, $query){
		$com = $this->x10ApiExcecutable . ' sendplc ' . $this->code . ' ' . $query;
		$this->log($method, $args, $com);
		return shell_exec($com);
	}
	

	
	/**
	 * Turn device ON.
	 */
	public function on(){
		$this->chkFlag(X10Device::F_ON);
		return $this->rawCommand(__METHOD__, func_get_args(), 'on');
	}
	
	/**
	 * Turn device OFF.
	 */
	public function off(){
		$this->chkFlag(X10Device::F_OFF);
		return $this->rawCommand(__METHOD__, func_get_args(), 'off');
	}
	
	/**
	 * Dim lights to a percent value from 0 - 100.
	 * @param int $percent Percent ( 0 - 100 )
	 */
	public function dim($percent){
		$this->chkFlag(X10Device::F_DIM);
		$percent = (int)$percent;
		if ($percent > 100 || $percent < 0)throw new \Exception("Dim percent from 0 - 100 is allowed.");
		return $this->rawCommand(__METHOD__, func_get_args(), "dim $percent");
	}
	
	/**
	 * Bright lights to a percent value from 0 - 100.
	 * @param int $percent Percent ( 0 - 100 )
	 */
	public function bright($percent){
		$this->chkFlag(X10Device::F_BRIGHT);
		$percent = (int)$percent;
		if ($percent > 100 || $percent < 0)throw new \Exception("Bright percent from 0 - 100 is allowed.");
		return $this->rawCommand(__METHOD__, func_get_args(), "bright $percent");
	}
	
	
	
	public function extendedCode($command, $value){
		if (!ctype_xdigit($command) || !ctype_xdigit($value))throw new \Exception("Command and value in custom extended code must both be hex codes.");
		
		return $this->rawCommand(__METHOD__, func_get_args(), "$command $value");
	}
	
	
	/**
	 * Runs a raw query.
	 * Returns integer as a result.
	 */
	public function rawQuery($query){
		$com = $this->x10ApiExcecutable . ' queryplc ' . $this->code . ' ' . $query;
		$result = trim(shell_exec($com));
		if ($result === '')throw new \Exception("Unable to run raw query against device ($this->code). Are you sure executable path is correct? Command executed: $com");
		$result = (int)$result;
		return $result;
	}
	
	
	/**
	 * Returns true if a device is on, false otherwise ( even if not known )
	 */
	public function isOn(){
		return $this->rawQuery('on') === 1;
	}
	
	/**
	 * Returns the current dim level. 
	 * -1 if not known.
	 */
	public function dimLevel(){
		return $this->rawQuery('dim');
	}
	
		
}