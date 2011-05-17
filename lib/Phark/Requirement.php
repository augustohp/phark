<?php

namespace Phark;

/**
 * Encapsulates a requirement for a particular version e.g >=mypackage-1.0.0 
 */ 
class Requirement
{
	private $_requirements=array();

	/**
	 * Pass in either a string or an array of strings
	 */
	public function __construct($requirements)
	{
		foreach((array)$requirements as $r)
			$this->_requirements []= $this->_parse($r);
	}

  private function _parse($requirement)
	{
		if(!preg_match('/^(~>|<=?|>=?|=|)(\d+\.\d+.\w+)$/',$requirement,$m))
			throw new Exception("Invalid requirement $requirement");

		$r = new \stdClass();
		$r->operator = $m[1] ?: '=';
		$r->version = new Version($m[2]); 

		return $r;
	}

	/**
	 * Returns true if a particular version meets the all the requirements
	 */
	public function isSatisfiedBy($version)
	{
		if(is_string($version)) $version = new Version($version);

		foreach($this->_requirements as $r)
		{
			switch($r->operator)
			{
				case '<=': if(!$version->lessOrEqual($r->version)) return false; break;
				case '<': if(!$version->less($r->version)) return false; break;
				case '>=': if(!$version->greaterOrEqual($r->version)) return false; break;
				case '>': if(!$version->greater($r->version)) return false; break;
				case '=': if(!$version->equal($r->version)) return false; break;
				case '~>': if(!$version->fuzzyEqual($r->version)) return false; break;
				default: throw new Exception("Unknown operator '{$r->operator}'");
			}
		}

		return true;		
	}

	/**
	 * Takes either a string or a Requirement object, returns a Requirement 
	 * object
	 */
	public static function parse($requirement)
	{
		return is_object($requirement) ? $requirement : new Requirement($requirement);
	}

	/**
	 * Convert to a string
	 */
	public function __toString()
	{
		$array = array_map(function($a) { return "{$a->operator}{$a->version}"; }, $this->_requirements);
		return implode(' ', $array);
	}
}
