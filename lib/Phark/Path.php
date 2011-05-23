<?php

namespace Phark;

class Path
{
	private $_path;

	public function __construct($components)
	{
		$this->_path = array_reduce(func_get_args(), function($result, $token) {

			$delim = (empty($result) || $result == '/') ? '' : '/';
			$token = ($token == '/') ? $token : rtrim($token,'/');
			return $result . $delim . $token;
		});
	}

	public function __toString()
	{
		return $this->_path;
	}
}
