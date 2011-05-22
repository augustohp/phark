<?php

namespace Phark\Command;

class HelpCommand implements \Phark\Command
{
	public function summary()
	{
		return 'Show help for a command or help topic';
	}

	public function execute($args, $env)
	{
	}
}
