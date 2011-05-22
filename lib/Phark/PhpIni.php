<?php

namespace Phark;

class PhpIni
{
	private $_path, $_values;

	public function __construct($path)
	{
		$this->_path = $path;
		$this->_values = parse_ini_file($this->_path);
	}

	public static function fromCurrent()
	{
		ob_start(); 
		@phpinfo(); 
		$phpinfo = preg_replace ('/<[^>]*>/', '', ob_get_contents()); 
		ob_end_clean(); 

		if(!preg_match('#Loaded Configuration File => (\S+)#', $phpinfo, $m))
			throw new Exception("Unable to find configuration file in phpinfo()");

		return new self($m[1]);
	}

	public function replace($key, $value)
	{
		$contents = file($this->_path);
		$pattern = '/'.preg_quote($key,'/').'/i';
		$lines = preg_grep($pattern, $contents);	

		if(count($lines))
		{
			foreach($lines as $lineno=>$line)
			{
				$contents[$lineno] = sprintf(";%s ; commented by phark\n%s = %s\n",
					trim($line), $key, $value);
			}
		}
		else
			$contents []= "$key = $value\n";

		file_put_contents($this->_path, implode('',$contents));
		return $this;
	}

	public function __isset($value)
	{
		return isset($this->_values[$values]);
	}

	public function __get($value)
	{
		return $this->_values[$value];
	}	
}
