<?php
/**
 * All X10 modules override this abstract.
 * @author Petter Kjelkenes <kjelkenes@gmail.com>
 *
 */
abstract class X10Device{
	protected $x10ApiExcecutable;
	protected $code;
	protected $callbackLogger;
	protected $featureFlags = null;
	protected $featureList = array();
	
	const TYPE_POWER = 1;
	const TYPE_RADIO = 2;
	
	/* Single flags */
	const F_ON = 1;
	const F_OFF = 2;
	const F_DIM = 4;
	const F_BRIGHT = 8;
	
	/* Groups */
	
	// F_ON | F_OFF
	const FG_ON_OFF = 3;
	
	// F_DIM | F_BRIGHT
	const FG_DIM_BRIGHT = 12;

	
	
	
	/**
	 * Sets what features is allowed to do.
	 * See the F_ constants in this class.
	 * Use it bitwise like so:
	 * F_ON | F_OFF | F_DIM etc.
	 * @param int $flags Bitwise flags of F_ constants in this class.
	 */
	public function setAllowedFeatures($flags){
		$this->featureFlags = $flags;
	}
	
	public function isFeatureAllowed($feature){
		return $this->featureFlags === null || $feature & $this->featureFlags;
	}
	
	public function getAllowedFlags(){
		return $this->featureFlags;
	}
	
	/**
	 * Convenicene method to get readable names of each feature.
	 */
	static public function getFeatures(){
		$ref = new \ReflectionClass('X10Device');
		$constants = $ref->getConstants();
		
		$returns = array();
		
		foreach($constants as $key => $val){
			if (substr($key, 0, 2) == 'F_'){
				$r = $key;
				switch($key){
					case 'F_ON': $r = 'On'; break;
					case 'F_OFF': $r = 'Off'; break;
					case 'F_DIM': $r = 'Dim'; break;
					case 'F_BRIGHT': $r = 'Bright'; break;
				}
				
				$returns[$val] = $r;
			}
		}
		return $returns;
	}
	
	
	
	
	
	/**
	 * Constructs X10 Device module.
	 * @param string $executable Path to SDK excecutable.
	 * @param string $deviceCode Device Code ( example a1 )
	 * @param closure annonymous function that 
	 */
	public function __construct($executable, $deviceCode, $callbackLogger = null){
		$this->x10ApiExcecutable = $executable;
		$this->code = $deviceCode;
		$this->callbackLogger = $callbackLogger;
		$this->featureList = self::getFeatures();
	}
	

	protected function chkFlag($flagsRequired){
		if (!$this->isFeatureAllowed($flagsRequired)){
			
			$msg = "Feature error on device (#{$this->code}): Feature(s):  ";
			foreach($this->featureList as $k => $v){
				if ($k & $flagsRequired)$msg .= "$v,";
			}
			$msg .= " is required for this device.";
			
			throw new \Exception($msg);
		}
	}
	
	/**
	 * Types of devices has other commands, this is abstract.
	 * @param string $query The custom query. Eg. "on" or "On".
	 */
	abstract public function rawCommand($method, $arguments, $query);
	
	
	public function getCode(){
		return $this->code;
	}


	
	public function log($name, $arguments, $command){
		$closure = $this->callbackLogger;
		
		if ($closure !==null && is_callable($closure)){
			$action = new \X10Action($this, $name, $arguments, $command);
			// Call it and send action to it.
			$closure($action);
		}
	}
}