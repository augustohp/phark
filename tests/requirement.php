<?php

require_once __DIR__.'/base.php';

class RequirementTest extends \Phark\Tests\TestCase
{
	public function testRangeRequirementStrings()
	{
		$d1 = new \Phark\Requirement(array('>=1.0.0', '<2.0.0'));

		$this->assertTrue($d1->isSatisfiedBy('1.0.0'));
		$this->assertTrue($d1->isSatisfiedBy('1.5.3'));
		$this->assertFalse($d1->isSatisfiedBy('2.0.0'));
		$this->assertFalse($d1->isSatisfiedBy('5.0.0'));
	}

	public function testFuzzyMinorVersionRequirementStrings()
	{
		$d1 = new \Phark\Requirement(array('~>1.0.0'));

		$this->assertTrue($d1->isSatisfiedBy('1.0.0'));
		$this->assertTrue($d1->isSatisfiedBy('1.0.5'));
		$this->assertFalse($d1->isSatisfiedBy('1.2.0'));
		$this->assertFalse($d1->isSatisfiedBy('5.0.0'));
	}

	public function testMultipleRequirements()
	{
		$d1 = new \Phark\Requirement(array('1.0.0', '>=1.0.0'));

		$this->assertTrue($d1->isSatisfiedBy('1.0.0'));
		$this->assertFalse($d1->isSatisfiedBy('2.0.0'));
	}
}

