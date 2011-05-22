<?php

require_once __DIR__.'/../vendor/simpletest/autorun.php';
require_once __DIR__.'/base.php';

\Mock::generate('\Phark\Shell','MockShell'); 
\Mock::generate('\Phark\Environment','MockEnvironment'); 

class ListCommandTest extends \Phark\Tests\TestCase
{
	public function testListingGlobalPackages()
	{
		$shell = new MockShell();
		$env = new MockEnvironment();

		$command = new \Phark\Command\ListCommand();
		$command->execute(array(), $shell);
	}
}
