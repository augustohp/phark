<?php

require_once __DIR__.'/base.php';

class OptionsTest extends \Phark\Tests\TestCase
{
	public function testBasicOptionParsing()
	{
		$opts = new \Phark\Options(array(
			'install', '--test', '-V', 'llamas'
			));

		$result = $opts->parse(array('--test','-V:'));
		$this->assertEqual($result->opts['--test'], true);
		$this->assertEqual($result->opts['-V'], array('llamas'));
		$this->assertEqual($result->unmatched, array('install'));
	}

	public function testUnknownParams()
	{
		$opts = new \Phark\Options(array('install', '--test'));

		$result = $opts->parse(array('-b'));
		$this->assertEqual($result->unmatched, array('install','--test'));
	}	
}
