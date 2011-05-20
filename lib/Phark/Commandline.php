<?php

namespace Phark;

class Commandline
{
	private $_commands=array();

	public function register($key, $command)
	{
		$this->_commands[$key] = $command;
		return $this;
	}

	public function commands()
	{
		return $this->_commands;
	}

	public function execute($args)
	{
	}
}
