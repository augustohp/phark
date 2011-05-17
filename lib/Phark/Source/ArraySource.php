<?php

namespace Phark\Source;

class ArraySource implements \Phark\Source
{
	private $_specs=array();

	public function find($name, \Phark\Requirement $requirement=null)
	{
		if(!isset($this->_specs[$name]))
			return false;

		$requirement = \Phark\Requirement::parse($requirement);
		$candidates = array();

		foreach(\Phark\Version::sort(array_keys($this->_specs[$name])) as $version)
		{
			if(!$requirement || $requirement->isSatisfiedBy($version))
				return $this->_specs[$name][(string)$version];
		}	

		return false;
	}

	public function add($spec)
	{
		$this->_specs[$spec->name()][(string)$spec->version()] = $spec;
		return $this;
	}
}
