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
		$params = preg_grep('/^-/',$args,PREG_GREP_INVERT);
		$command = $params[0];

		foreach($this->_commands as $key=>$obj)
		{
			if(strpos($key, $command) === 0)
				return $obj->execute(preg_grep('/^--(help|version)$/',$args,PREG_GREP_INVERT), new Shell());
		}

		throw new Exception("'%command' is not a phark command. See phark --help");
	}
}
