<?php

require_once __DIR__.'/../vendor/simpletest/autorun.php';
require_once __DIR__.'/base.php';

\Mock::generate('\Phark\Specification','MockSpecification'); 

class BundlerTest extends \Phark\Tests\TestCase
{
	public function testSelfBundling()
	{
		$bundler = new \Phark\Bundler(BASEDIR);
		$phar = $bundler->bundle();

		// check some files are in place
		$this->assertTrue(isset($phar['bin/phark']));
		$this->assertFalse(isset($phar['tests/all.php']));
	}
}


