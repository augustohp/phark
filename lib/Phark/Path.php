<?php

namespace Phark;

class Path
{
	private $_path;

	public function __construct($components)
	{
		$this->_path = array_reduce(func_get_args(), function($v, $w) {
			return $v.($w == '/' ? '/' : rtrim($w, '/')).($w == '/' ? '' : '/');
		});

		if(strlen($this->_path) > 1)
			$this->_path = rtrim($this->_path, '/');
	}

	public function __toString()
	{
		return $this->_path;
	}
}
