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

	public function packagePaths()
	{
		return array('/usr/local/phark/packages');
	}
	
}
