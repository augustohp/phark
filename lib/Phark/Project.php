<?php

namespace Phark;

class Project
{
	private $_dir, $_env;

	public function __construct($dir, $env=null)
	{
		$this->_dir = $dir;
		$this->_env = $env ?: new Environment();
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

	public function packages()
	{
		return new PackageDir(new Path($this->_dir, 'vendor'), $this->_env);	
	}

	/**
	 * Finds the nearest path with a Pharkspec or Pharkdep file
	 * @return Project or null
	 */
	public static function locate($env=null)
	{
		$env = $env ?: new Environment();
		$shell = $env->shell();
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
