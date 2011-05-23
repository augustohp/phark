<?php

require_once __DIR__.'/base.php';

class PathTest extends \Phark\Tests\TestCase
{
	public function testRelativePath()
	{
		$path = new \Phark\Path("mydir","myfile");
		$this->assertEqual((string)$path, "mydir/myfile");
	}

	public function testAbsoluteRelativePath()
	{
		$path = new \Phark\Path("/root/directory/","myfile");
		$this->assertEqual((string)$path, "/root/directory/myfile");
	}

	public function testBareRoot()
	{
		$path = new \Phark\Path("/","myfile");
		$this->assertEqual((string)$path, "/myfile");
	}

	public function testBareRelative()
	{
		$path = new \Phark\Path("","myfile");
		$this->assertEqual((string)$path, "myfile");
	}
}
