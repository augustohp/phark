<?php

require_once __DIR__.'/../vendor/simpletest/autorun.php';
require_once __DIR__.'/base.php';

class BundlerTest extends \Phark\Tests\TestCase
{
	public function testSelfBundling()
	{
		$bundler = new \Phark\Bundler(new \Phark\Package(BASEDIR));
		$phar = $bundler->bundle('/tmp');

		// check some files are in place
		$this->assertTrue(is_file("/tmp/".$bundler->pharfile()));
		$this->assertTrue(isset($phar['bin/phark']));
		$this->assertFalse(isset($phar['tests/all.php']));

		unlink("/tmp/".$bundler->pharfile());
	}
}
