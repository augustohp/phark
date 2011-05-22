<?php

namespace Phark;

class Environment
{
	public function installDir()
	{
		return '/usr/local/phark';
	}

	public function remoteSources()
	{
		return array('http://pharkphp.org/');
	}

	public function executableDir()
	{
		return '/usr/local/bin';
	}

	public function packageDirs()
	{
		return array('/usr/local/phark/packages');
	}

	public function activePackagesDir()
	{
		return '/usr/local/phark/activated';
	}

	public function cacheDir()
	{
		return '/usr/local/phark/cache';
	}

	public function shell()
	{
		return new Shell();
	}

	public function packages()
	{
		return new PackageDir(new Path($this->installDir()), $this);
	}
}
