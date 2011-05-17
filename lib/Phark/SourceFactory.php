<?php

namespace Phark;

/**
 * Pluggable factory for creating Source objects
 */
class SourceFactory
{
	private $_handlers=array();

	/**
	 * Register a callback for a type of source
	 */
	public function register($type, $handler)
	{
		$this->_handlers[$type] = $handler;
		return $this;
	}

	public function create($type, $url, $params=array())
	{
		if(!isset($this->_handlers[$type]))
			throw new Exception("Unknown source type $type");

		return call_user_func($this->_handlers[$type], $url, $params);
	}
}
