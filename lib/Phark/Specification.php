<?php

namespace Phark;

class Specification
{
	public
		$name,
		$authors=array(),
		$homepage,
		$version,
		$phpVersion,
		$summary,
		$description,
		$dependencies=array(),
		$devDependencies=array(),
		$files=array()
		;

	public function __construct($properties=array())
	{
		foreach($properties as $prop=>$value)
			$this->$prop = $value;
	}

	public function __call($method, $params)
	{
		return $this->$method;
	}

	public function hash()
	{
		return $this->name() . '@' . $this->version();
	}
}
