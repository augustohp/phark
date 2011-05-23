<?php

namespace Phark\Command;

class ListCommand implements \Phark\Command
{
	public function summary()
	{
		return 'Lists packages available in the current scope';
	}

	public function execute($args, $env)
	{
		$opts = new \Phark\Options($args);
		$result = $opts->parse(array('-g'), array('command'));
		$shell = $env->shell();
		
		if($project = $env->project() && !isset($result->opts['-g']))
		{
			$shell->printf("PROJECT PACKAGES\n\n");
			$packages = $project->packages();
		}
		else
		{
			$shell->printf("GLOBAL PACKAGES\n\n");
			$packages = $env->packages();
		}

		foreach($packages as $package)
		{
			$shell->printf("%s (%s)\n", $package->spec()->name(), $package->spec()->version()); 
		}
	}
}
