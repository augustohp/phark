<?php

namespace Phark;

class Package
{
	private $_dir, $_spec;

	public function __construct($dir, $spec=null)
	{
		$this->_dir = (string)$dir;
		$this->_spec = $spec ?: SpecificationBuilder::fromDir($this->_dir)->build();
	}

	public function directory()
	{
		return $this->_dir;
	}

	public function spec()
	{
		return $this->_spec;
	}
}
