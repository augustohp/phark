<?php

namespace Phark;

/**
 * A dependancy on a specific package
 */
class Dependancy
{
	public $package;
	private $_constraints=array();

	public function __construct($package, $constraints=array())
	{
		$this->package = $package;

		foreach($constraints as $c)
			$this->_constraints []= $this->_parseConstraint($c);
	}

	private function _parseConstraint($constraint)
	{
		if(!preg_match('/^(~>|<=?|>=?|=|)(\d+\.\d+.\w+)$/',$constraint,$m))
			throw new Exception("Invalid constraint $constraint");

		$c = new \stdClass();
		$c->version = new Version($m[2]); 
		$c->operator = $m[1];

		return $c;
	}

	public function meets($package, $version)
	{
		if($package != $this->package) 
			return false;

		if(is_string($version)) $version = new Version($version);

		foreach($this->_constraints as $c)
		{
			switch($c->operator)
			{
				case '<=': if(!$version->lessOrEqual($c->version)) return false; break;
				case '<': if(!$version->less($c->version)) return false; break;
				case '>=': if(!$version->greaterOrEqual($c->version)) return false; break;
				case '>': if(!$version->greater($c->version)) return false; break;
				case '=': if(!$version->equal($c->version)) return false; break;
				case '~>': if(!$version->fuzzyEqual($c->version)) return false; break;
			}
		}

		return true;		
	}
}
