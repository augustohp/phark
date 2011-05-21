<?php

namespace Phark;

/**
 * A directory containing a specific phark package repository structure
 */
class PackageDir
{
	private $_dir, $_shell, $_env;

	public function __construct($dir, $env)
	{
		$this->_dir = $dir;
		$this->_shell = $env->shell();
		$this->_env = $env;
	}

	public function install(Package $package)
	{
		$targetDir = new Path($this->_dir, 'packages', $package->spec()->hash());
		$activeDir = new Path($this->_dir, 'activated', $package->spec()->name());
	
		if($this->_shell->isdir($targetDir))
			throw new \BadMethodCallException("$targetDir already exists");

		// copy the files from source to target
		foreach($package->spec()->files() as $file)
		{
			$this->_shell->copy(
				(string) new Path($package->directory(), $file),
				(string) new Path($targetDir, $file)
			);
		}

		//TODO: check if another version of the package is installed
		$this->_shell->symlink((string)$targetDir, (string)$activeDir);

		foreach($package->spec()->executables() as $bin)
		{
			$this->_shell->symlink(
				(string) new Path($activeDir, $bin),
				(string) new Path($this->_env->executableDir(), basename($bin))
			);
		}
	}
}
