<?php

namespace Phark;

/**
 * Builds a .phar bundle from a package directory
 */
class Bundler
{
	private $_package;

	/**
	 * Constructor
	 */
	public function __construct($package)
	{
		$this->_package = $package;
	}

	/**
	 * Return the default filename for the spec
	 * @return string
	 */
	public function pharfile()
	{
		return $this->_package->spec()->hash().'.phar';
	}

	/**
	 * Builds a {@link Phar} archive in the specified directory
	 * @return Phar
	 */
	public function bundle($dirname)
	{
		if(ini_get('phar.readonly'))
			throw new Exception("unable to bundle packages when phar.readonly=1 (php.ini)");

		$filename = new Path($dirname, $this->pharfile());

		//TODO: write as TAR and convert to PHAR on the serverside?
		$p = new \Phar($filename, 0, $this->pharfile());

		foreach($this->_package->spec()->files() as $file)
			$p->addFile(new Path($this->_package->directory(), $file), $file);

		return $p;
	}
}
