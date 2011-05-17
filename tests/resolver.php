<?php

require_once __DIR__.'/../vendor/simpletest/autorun.php';
require_once __DIR__.'/base.php';


class ResolverTest extends \Phark\Tests\TestCase
{
	public function setUp()
	{
		$this->source = new \Phark\Source\ArraySource();
	}

	private function _spec($name, $version, $deps=array())
	{
		$builder = new \Phark\SpecificationBuilder();
		$builder
			->name($name)
			->version($version)
			;

		foreach($deps as $pkg=>$req)
			$builder->dependency($pkg, $req);

		return $builder->build();
	}

	public function testResolvingSimpleDependancies()
	{
		$package = $this->_spec('package','1.0.0', array(
			'packageA'=>'1.0.0',
			'packageB'=>'>=2.0.1',	
		));

		$this->source
			->add($package)
			->add($this->_spec('packageA', '1.0.0'))
			->add($this->_spec('packageA', '2.0.0'))
			->add($this->_spec('packageB', '1.0.1'))
			->add($this->_spec('packageB', '2.0.1', array('packageC'=>'>=3.0.0')))
			->add($this->_spec('packageC', '3.0.0', array('packageA'=>'>=1.0.0')))
			->add($this->_spec('packageC', '3.5.0beta1', array('packageA'=>'>=1.0.0')))
			;

		$resolver = new \Phark\DependencyResolver();
		$resolver->source($this->source);
		$solution = $resolver->resolve($package);

		$this->assertEqual($solution, array(
			'packageC@3.5.0beta1',
			'packageB@2.0.1',
			'packageA@1.0.0',
			'package@1.0.0',	
		));
	}

	public function testCircularDependencies()
	{
		$package = $this->_spec('packageA','1.0.0', array(
			'packageB'=>'1.0.0',
		));

		$this->source
			->add($package)
			->add($this->_spec('packageB', '1.0.0', array('packageA'=>'1.0.0')))
			;

		$resolver = new \Phark\DependencyResolver();
		$resolver->source($this->source);
		$solution = $resolver->resolve($package);

		$this->assertEqual($solution, array(
			'packageB@1.0.0',
			'packageA@1.0.0',
		));
	}	

	public function testDependencyClash()
	{
		$package = $this->_spec('packageA','1.0.0', array(
			'packageB'=>'1.0.0',
			'packageC'=>'2.0.1',
		));

		$this->source
			->add($package)
			->add($this->_spec('packageB', '1.0.0', array('packageA'=>'1.0.0')))
			->add($this->_spec('packageB', '2.0.0', array('packageA'=>'1.0.0')))
			->add($this->_spec('packageC', '2.0.1', array('packageB'=>'2.0.0')))
			;

		$resolver = new \Phark\DependencyResolver();
		$resolver->source($this->source);
		
		$this->expectException();
		$solution = $resolver->resolve($package);
	}		
}
