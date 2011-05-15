<?php

require_once __DIR__.'/../vendor/simpletest/autorun.php';

class AllTests extends TestSuite
{
	function __construct()
	{
		$exclude = array('all.php','base.php');

		// add all tests
		foreach(glob(__DIR__.'/*.php') as $file)
		{
			if(!in_array(basename($file), $exclude))
				$this->addFile($file);
		}
	}
}

