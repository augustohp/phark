<?php

namespace Phark;

class DependencyBuilder
{
	private $_sources=array(), $_dependencies=array(), $_sourceFactory;

	public function __construct($sourceFactory)
	{
		$this->_sourceFactory = $sourceFactory;
	}

	public function source($url, $type='http', $params=array())
	{
		$this->_sources []= $this->_sourceFactory->create($type, $url, $params);
		return $this;
	}

	public function dependancy($name)
	{
		$this->_dependencies []= func_get_args(); 
		return $this;
	}

	public function build()
	{
		$deps = array();

		foreach($this->_dependencies as $d)
		{
			$name = array_shift($d);
			$deps []= new Dependency($name, $d, $this->_sources);
		}		

		return $deps;
	}
}
