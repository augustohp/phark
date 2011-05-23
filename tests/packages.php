<?php

require_once __DIR__.'/base.php';

\Mock::generate('\Phark\Shell','MockShell'); 
\Mock::generate('\Phark\Environment','MockEnvironment'); 

class PackageTest extends \Phark\Tests\TestCase
{
	public function testInstalling()
	{
		$shell = new MockShell();
		$shell->setReturnValue('glob', array('bin/myexec', 'bin/another'), array('/some/path','bin/**'));
		$shell->setReturnValue('glob', array('myfile.php'), array('/some/path','myfile.php'));
		$shell->setReturnValueAt(0, 'isdir', false, array('/packages/mypackage@1.0.0'));
		$shell->setReturnValueAt(1, 'isdir', true, array('/packages/mypackage@1.0.0'));
		$shell->setReturnValueAt(2, 'isdir', true, array('/active/mypackage@1.0.0'));
		$shell->setReturnReference('chmod', $shell);

		$shell->expectAt(0,'copy',array('/some/path/Pharkspec', '/packages/mypackage@1.0.0/Pharkspec'));
		$shell->expectAt(1,'copy',array('/some/path/myfile.php', '/packages/mypackage@1.0.0/myfile.php'));
		$shell->expectAt(2,'copy',array('/some/path/bin/myexec', '/packages/mypackage@1.0.0/bin/myexec'));
		$shell->expectAt(3,'copy',array('/some/path/bin/another', '/packages/mypackage@1.0.0/bin/another'));
		$shell->expectCallCount('copy',4);

		$shell->expectAt(0,'symlink',array('/packages/mypackage@1.0.0', '/active/mypackage'));
		$shell->expectAt(1,'symlink',array('/active/mypackage/bin/myexec', '/usr/local/bin/myexec'));
		$shell->expectAt(2,'symlink',array('/active/mypackage/bin/another', '/usr/local/bin/another'));
		$shell->expectCallCount('symlink',3);

		$shell->expectAt(0,'chmod',array('/active/mypackage/bin/myexec', 0755));
		$shell->expectAt(1,'chmod',array('/active/mypackage/bin/another', 0755));
		$shell->expectCallCount('chmod',2);		

		$env = new MockEnvironment();
		$env->setReturnValue('executableDir', '/usr/local/bin');
		$env->setReturnReference('shell', $shell);

		$builder = new \Phark\SpecificationBuilder('/some/path', $shell);
		$spec = $builder
				->name('mypackage')
				->version('1.0.0')
				->files('myfile.php', 'bin/**')
				->executables('bin/**')
				->build()
				;

		$package = new \Phark\Package('/some/path', $spec);
		$packages = new \Phark\PackageDir('/', $env);
		$packages->install($package);
	}
}


