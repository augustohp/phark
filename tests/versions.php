<?php

require_once __DIR__.'/../vendor/simpletest/autorun.php';
require_once __DIR__.'/base.php';

class VersionsTest extends \Phark\Tests\TestCase
{
	public function testParsingVersions()
	{
		$v = new \Phark\Version("1.2.3");

		$this->assertEqual($v->major, 1);
		$this->assertEqual($v->minor, 2);
		$this->assertEqual($v->patch, 3);
		$this->assertEqual((string)$v, "1.2.3");

		$v = new \Phark\Version("1.0.2beta1");

		$this->assertEqual($v->major, 1);
		$this->assertEqual($v->minor, 0);
		$this->assertEqual($v->patch, 2);
		$this->assertEqual($v->special, 'beta1');
		$this->assertEqual((string)$v, "1.0.2beta1"); 
	}

	public function testComparingVersions()
	{
		$v1 = new \Phark\Version("1.3.3");
		$v2 = new \Phark\Version("1.2.5");

		$this->assertTrue($v1->greater($v2));
		$this->assertTrue($v1->greaterOrEqual($v2));
		$this->assertTrue($v2->less($v1));
		$this->assertTrue($v2->lessOrEqual($v1));
		$this->assertFalse($v2->equal($v1));

		$v1 = new \Phark\Version("1.2.0");
		$v2 = new \Phark\Version("1.2.0beta1");
		$v3 = new \Phark\Version("1.2.0beta2");
		$v4 = new \Phark\Version("1.2.0alpha1");

		$this->assertTrue($v2->less($v1));
		$this->assertTrue($v3->less($v1));
		$this->assertTrue($v4->less($v1));
	}	

	public function testSortingVersions()
	{
		$versions = array("1.3.1","0.1.0","1.3.1beta1","1.3.1alpha","1.3.1rc1");
		$sorted = array_map(function($v){ return (string)$v; }, \Phark\Version::sort($versions));

		$this->assertEqual($sorted, array(
			'1.3.1','1.3.1rc1','1.3.1beta1','1.3.1alpha','0.1.0'
		));
	}	
}

