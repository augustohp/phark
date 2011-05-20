<?php

namespace Phark;

/**
 * Simple classloader for PSR-0 compliant packages
 * @see http://groups.google.com/group/php-standards/web/psr-0-final-proposal?pli=1
 */
class ClassLoader
{
	private $_paths=array();

	public function __construct($paths)
	{
		$this->_paths = array_map(function($p) { return rtrim($p,'/').'/'; }, $paths);
	}

	public function load($className)
	{
		if(!class_exists($className))
		{
			foreach($this->_paths as $path)
			{
				$file = $path . str_replace('\\','/',$className).'.php';

				if(file_exists($file))
				{
					require $file;
					break;
				}
			}

			if(!class_exists($className) && !interface_exists($className))
				throw new \Exception("Unable to load $className");
		}
	}

	public function register()
	{
		spl_autoload_register(array($this, 'load'), true);
		return $this;
	}
}
