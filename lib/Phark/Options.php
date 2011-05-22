<?php

namespace Phark;

/**
 * Brain-dead options parser
 */
class Options
{
	private $_args;

	/**
	 * Takes an array of arguments, defaults to $argv
	 */
	public function __construct($args=null)
	{
		$this->_args = $args ?: array_slice($GLOBALS['argv'],1);
	}

	/**
	 * Parse arguments based on an array of valid flags, with a trailing
	 * colon to indicate a value is required
	 * @return object
	 */
	public function parse($pattern)
	{
		$args = $this->_args;
		$valid = array_map(function($a) { return trim($a,':'); }, $pattern);
		$result = (object) array('unmatched'=>array(), 'opts'=>array());
		$inside = false;

		while($arg = array_shift($args))
		{
			if($inside)
			{
				$result->opts[$inside] []= $arg;
				$inside = false;
			}
			else if(in_array($arg, $valid))
			{
				if(in_array($arg.':', $pattern))
					$inside = $arg;
				else
					$result->opts[$arg] = true;
			}
			else
			{
				$result->unmatched []= $arg;
			}
		}

		return $result;
	}
}
