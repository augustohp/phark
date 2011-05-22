<?php

namespace Phark;

class Project
{
	private $_dir;

	public function __construct($dir)
	{
		$this->_dir = $dir;
	}

	public function name()
	{
		return basename($this->_dir);
	}

	public function directory()
	{
		return $this->_dir;
	}

	public function includePaths()
	{
		return array(
			(string) new Path($this->_dir, 'vendor')
		);
	}

	/**
	 * Finds the nearest path with a Pharkspec or Pharkdep file
	 * @return Project or null
	 */
	public static function locate($shell=null)
	{
		$shell = $shell ?: new Shell();
		$dir = $shell->getcwd();
		$projectRoot = false;

		do
		{
			if($shell->isfile("$dir/Pharkspec") || $shell->isfile("$dir/Pharkdeps"))
				$projectRoot = $dir;
			else
				$dir = dirname($dir);
		} 
		while(!$projectRoot && $dir);

		if($projectRoot)
			return new self($projectRoot);
	}
}
