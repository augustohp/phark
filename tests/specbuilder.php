<?php

require_once __DIR__.'/../vendor/simpletest/autorun.php';
require_once __DIR__.'/base.php';

\Mock::generate('\Phark\Shell','MockShell'); 

class SpecificationBuilderTest extends \Phark\Tests\TestCase
{
	public function testBuilding()
	{
		$shell = new MockShell();	
		$shell->setReturnValue('glob', array(
			'lib/Test/A.php',
			'lib/Test/B.php',
			'lib/Test/Package/C.php',
			));

		$builder = new \Phark\SpecificationBuilder('/blargh',$shell);
		$spec = $builder
			->name('pheasant')
			->authors('Lachlan Donald <lachlan@ljd.cc>')
			->homepage('http://github.com/lox/pheasant')
			->version('1.0.0')
			->phpVersion('>=5.3.1')
			->summary('A object mapper written for MySQL5.1 and PHP5.3')
			->description('Pheasant is a simple object mapper for PHP 5.3+ and MySQL 5+. It offers basic relationships and query hydration.')
			->devDependency('simpletest','>=2.0.1beta')
			->files('lib/**')
			->includePath('lib')
			->build()
			;

		$this->assertEqual($spec->name(), 'pheasant');
		$this->assertEqual($spec->authors(), array('Lachlan Donald <lachlan@ljd.cc>'));
		$this->assertEqual($spec->homepage(), 'http://github.com/lox/pheasant');
		$this->assertEqual((string) $spec->version(), '1.0.0');
		$this->assertIsA($spec->version(), '\Phark\Version');
		$this->assertEqual((string)$spec->phpVersion(), '>=5.3.1');
		$this->assertIsA($spec->phpVersion(), '\Phark\Requirement');
		$this->assertEqual($spec->includePath(), array('lib'));

		$this->assertEqual($spec->files(), array(
			'lib/Test/A.php',
			'lib/Test/B.php',
			'lib/Test/Package/C.php',
		));			
	}
}


