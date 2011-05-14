<?php

namespace
{
	define('BASEDIR', __DIR__.'/../');
	define('LIBDIR', BASEDIR.'lib/');

	// show all errors
	error_reporting(E_ALL);

	// set up autoload
	function __autoload($className)
	{
		if(!class_exists($className))
		{
			$path = LIBDIR . str_replace('\\','/',$className).'.php';

			if(file_exists($path))
				require_once($path);

			if(!class_exists($className) && !interface_exists($className))
				throw new Exception("Unable to load $className");
		}
	}
}

namespace Phark\Tests
{
	class TestCase extends \UnitTestCase
	{
		public function before($method)
		{
			parent::before($method);
		}
  }
}

