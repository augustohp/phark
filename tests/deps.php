<?php

require_once __DIR__.'/../vendor/simpletest/autorun.php';
require_once __DIR__.'/base.php';

class DependanciesTest extends \Phark\Tests\TestCase
{
	public function testParsingExactDependancyStrings()
	{
		$d1 = new \Phark\Dependancy('package');

		$this->assertFalse($d1->meets('blargh','2.0.0'));
		$this->assertTrue($d1->meets('package','1.0.0'));
	}

	public function testRangeDependancyStrings()
	{
		$d1 = new \Phark\Dependancy('package', array('>=1.0.0', '<2.0.0'));

		$this->assertTrue($d1->meets('package','1.0.0'));
		$this->assertTrue($d1->meets('package','1.5.3'));
		$this->assertFalse($d1->meets('package','2.0.0'));
		$this->assertFalse($d1->meets('package','5.0.0'));
	}

	public function testFuzzyMinorVersionDependancyStrings()
	{
		$d1 = new \Phark\Dependancy('package', array('~>1.0.0'));

		$this->assertTrue($d1->meets('package','1.0.0'));
		$this->assertTrue($d1->meets('package','1.0.5'));
		$this->assertFalse($d1->meets('package','1.2.0'));
		$this->assertFalse($d1->meets('package','5.0.0'));
	}
}

