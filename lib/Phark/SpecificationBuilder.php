<?php

namespace Phark;

class SpecificationBuilder
{
	private $_props, $_shell, $_basedir;

	public function __construct($basedir, $shell=null)
	{
		$this->_basedir = $basedir;
		$this->_shell = $shell ?: new \Phark\Shell();
		$this->_props = array(
			'files' => array(Specification::FILENAME),
			'executables' => array(),
		);
	}

	public static function fromFile($file, $shell=null)
	{
		$spec = new SpecificationBuilder(dirname($file), $shell);
		require $file;
		return $spec;
	}

	public static function fromDir($dir, $shell=null)
	{
		return self::fromFile(new Path($dir, Specification::FILENAME));
	}

	public function name($name) 
	{ 
		$this->_props['name'] = $name; 
		return $this; 
	}

	public function authors($authors) 
	{ 
		$this->_props['authors'] = func_get_args();
		return $this;
	}

	public function homepage($homepage) 
	{ 
		$this->_props['homepage'] = $homepage; 
		return $this;
	}

	public function summary($summary) 
	{ 
		$this->_props['summary'] = $summary;
		return $this;
	}

	public function description($description)
	{ 
		$this->_props['description'] = $description; 
		return $this;
	}

	public function includePath($path)
	{ 
		foreach(func_get_args() as $p)
			$this->_props['includePath'] []= $p; 
		return $this;
	}	

	public function version($version) 
	{ 
		$this->_props['version'] = new \Phark\Version($version); 
		return $this;
	}

	public function phpVersion($phpVersion) 
	{
		$this->_props['phpVersion'] = new \Phark\Requirement($phpVersion); 
		return $this;
	}

	public function executables($path)
	{ 
		foreach(func_get_args() as $filespec)
			$this->_props['executables'] = array_merge($this->_props['executables'],
				$this->_shell->glob($this->_basedir, $filespec));

		return $this;
	}		
	
	public function files($files) 
	{ 
		foreach(func_get_args() as $filespec)
			$this->_props['files'] = array_merge($this->_props['files'],
				$this->_shell->glob($this->_basedir, $filespec));

		return $this;
	}

	public function dependency($name, $requirement=null) 
	{ 
		$this->_props['dependencies'][] = new \Phark\Dependency($name, $requirement); 
		return $this;
	}

	public function devDependency($name, $requirement=null) 
	{ 
		$this->_props['devDependencies'][] = new \Phark\Dependency($name, $requirement); 
		return $this;
	}

	public function build()
	{
		return new Specification($this->_props);
	}
}
