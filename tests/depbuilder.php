<?php

namespace Phark\DependencyBuilderTest;

require_once __DIR__.'/../vendor/simpletest/autorun.php';
require_once __DIR__.'/base.php';

class TestSource implements \Phark\Source
{
	public function find($name, \Phark\Requirement $requirement=null)
	{
		return new \Phark\Specification();
	}
}

class DependencyBuilderTest extends \Phark\Tests\TestCase
{
  public function setUp()
	{
		$this->source = $source = new TestSource();
		$this->sourceFactory = new \Phark\SourceFactory();
		$this->sourceFactory->register('http', function($url, $params) use($source) {
			return $source;
		});
	}

	public function testBuilding()
	{
		$builder = new \Phark\DependencyBuilder($this->sourceFactory);
		$deps = $builder
			->source('http://example.org')
			->dependency('mypackage')
			->dependency('blargh','>=1.0.0')
			->build()
			;

		$this->assertEqual(2, count($deps));
		$this->assertEqual($deps[0]->package, 'mypackage');
		$this->assertEqual($deps[1]->package, 'blargh');
		$this->assertTrue($deps[1]->isSatisfiedBy('blargh','1.2.0'));
	}
}


