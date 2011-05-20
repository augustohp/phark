<?php

require_once __DIR__.'/../vendor/simpletest/autorun.php';
require_once __DIR__.'/base.php';

\Mock::generate('\Phark\Shell','MockShell'); 

class FileListTest extends \Phark\Tests\TestCase
{
	public function testFiles()
	{
		$shell = new MockShell();
		$shell->setReturnValue('glob', array(
			'lib/Package/A.php',
			'lib/Package/Blargh/B.php',
			'bin/llamas.php',
			'bin/ignore.php',
			'.git/config',
			'README.md',
			'LICENSE',
			'CONTRIBUTORS'
			));

		$list = new \Phark\FileList('/fake/path', $shell);
		$list
			->glob('lib/**')
			->glob('README.md')
			->glob('bin/**')
			->exclude('bin/ignore.php')
			;

		$this->assertEqual($list->files(), array(
			'lib/Package/A.php',
			'lib/Package/Blargh/B.php',
			'README.md',
			'bin/llamas.php',
			));
	}
}
