<?php

namespace Phark\Command;

class InstallCommand implements \Phark\Command
{
	public function summary()
	{
		return 'Install a package globally';
	}

	public function execute($args, $env)
	{
		$opts = new \Phark\Options($args);
		$result = $opts->parse(array('f'), array('command','package'));

		// if a directory is specified
		if($realpath = realpath($result->params['package']))
		{
			$env->shell()->printf(" * installing from %s\n", $realpath);

			$package = new \Phark\Package($realpath);
			$env->packages()->install($package);

			$env->shell()->printf(" * package %s installed √\n", $package->spec()->hash());
		}
	}
}
