<?php

namespace Phark;

/**
 * Builds a .phar package based on a Pharkspec
 */
class Bundler
{
	const SPEC_FILENAME='Pharkspec';

	private $_path, $_shell;

	/**
	 * Constructor
	 */
	public function __construct($path, $shell=null)
	{
		$this->_shell = $shell ?: new Shell();
		$this->_path = $path;
	}

	/**
	 * Returns the path to the spec file
	 * @return string
	 */
	public function specfile()
	{
		return rtrim($this->_path,'/').'/'.self::SPEC_FILENAME;
	}

	/**
	 * Loads the {@link Specification} from the Pharkspec
	 * @return Specification
	 */
	public function spec()
	{
		if(!$this->_shell->isfile($this->specfile()))
			throw new Exception("failed to find Pharkspec in $path");

		$spec = new SpecificationBuilder($this->_path, $this->_shell);
		$specfile =  $this->specfile();

		// call in a closure to clear scope
		$closure = function($spec, $specfile) {
			require $specfile;
			return $spec->build();
		};

		return $closure($spec, $specfile);
	}

	/**
	 * Builds a {@link Phar} archive
	 * @return Phar
	 */
	public function bundle()
	{
		if(ini_get('phar.readonly'))
			throw new Exception("unable to bundle packages when phar.readonly=1 (php.ini)");

		$spec	= $this->spec();

		//TODO: write as TAR and convert to PHAR on the serverside?
		$p = new \Phar($this->_path.'/'.$spec->hash().'.phar', 0, $spec->hash().'.phar');

		foreach($spec->files() as $file)
			$p->addFile($this->_path.'/'.$file, $file);

		return $p;
	}
}
