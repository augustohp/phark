<?php

require_once __DIR__.'/../vendor/simpletest/autorun.php';
require_once __DIR__.'/base.php';

class DependancyBuilderTest extends \Phark\Tests\TestCase
{
	public function testBuilding()
	{
		$builder = new \Phark\DependencyBuilder();
		$builder
			->source('http://example.org')
			->package('mypackage')
			->package('blargh','>=1.0.0')
			;
	}
}


