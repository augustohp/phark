<?php

namespace Phark;

/**
 * A dependency links a package name, a requirement and a number of sources 
 */
class Dependency
{
	public $package, $requirement, $sources;

	public function __construct($package, $requirement, $sources=null)
	{
		$this->package = $package;
		$this->sources = $sources;
		$this->requirement = Requirement::parse($requirement); 
	}

	public function isSatisfiedBy($package, $version)
	{
		if($package != $this->package)
			return false;
		else
			return $this->requirement->isSatisfiedBy($version);
	}

	public function __toString()
	{
		return sprintf('%s %s', $this->package, $this->requirement);
	}	
}
