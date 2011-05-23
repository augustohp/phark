<?php

namespace Phark;

/**
 * A directory containing a specific phark package repository structure
 */
class PackageDir implements \IteratorAggregate
{
	const PACKAGES_PATH='packages';
	const ACTIVE_PATH='active';
	const CACHE_PATH='cache';

	private $_dir, $_shell, $_env;

	public function __construct($dir, $env)
	{
		$this->_dir = $dir;
		$this->_shell = $env->shell();
		$this->_env = $env;
	}

	/**
	 * Initializes the directories, called during system install
	 */
	public function initalize()
	{
		$this->_shell
			->mkdir(new Path($this->_env->installDir(),self::PACKAGES_PATH), 0777)
			->mkdir(new Path($this->_env->installDir(),self::ACTIVE_PATH), 0777)
			->mkdir(new Path($this->_env->installDir(),self::CACHE_PATH), 0777)
			;		

		return $this;
	}

	/**
	 * Returns all installed packages
	 * @return Packages[]
	 */
	public function packages()
	{
		$packagesDir = new Path($this->_dir, "packages");
		$specfiles = $this->_shell->glob((string)$packagesDir, "*/Pharkspec");
		$packages = array();

		foreach($specfiles as $specfile)
			$packages []= new Package((string) new Path($packagesDir, dirname($specfile)));

		return $packages;
	}

	/**
	 * Iterator for packages
	 */
	public function getIterator()
	{
		return new \ArrayIterator($this->packages());
	}

	/**
	 * Returns a specific package
	 * @return Package
	 */
	public function package($name, $version)
	{
		$packageDir = new Path($this->_dir, self::PACKAGES_PATH, "{$name}@{$version}");

		if(!$this->_shell->isdir((string)$packageDir))
			throw new Exception("Package $name $version isn't installed");

		return new Package($packageDir);
	}

	/**
	 * Returns the active version of a package, false if no active
	 * @return Version
	 */
	public function activePackage($name)
	{
		$packageDir = new Path($this->_dir, self::ACTIVE_PATH, $name);

		if(!$this->_shell->isdir($packageDir))
			return false;

		return new Package($packageDir);
	}

	/**
	 * Copies a directory into the packages directory with the correct name
	 * @chainable
	 */
	public function install($package, $activate=true)
	{
		$spec = $package->spec();
		$targetDir = new Path($this->_dir, self::PACKAGES_PATH, $spec->hash());
	
		if($this->_shell->isdir($targetDir))
			throw new Exception("Package {$spec->name()} {$spec->version()} is already installed");

		// copy the files from source to target
		foreach($spec->files() as $file)
		{
			$this->_shell->copy(
				(string) new Path($package->directory(), $file),
				(string) new Path($targetDir, $file)
			);
		}

		if($activate)
			$this->activate(new Package($targetDir, $spec));

		return $this;
	}

	/**
	 * Activates a particular version of a package, deactivating any previously 
	 * activated version
	 * @chainable
	 */
	public function activate($package)
	{
		$activeDir = new Path($this->_dir, self::ACTIVE_PATH, $package->spec()->name());

		// remove any previously active versions
		if($this->_shell->isdir($activeDir))
			$this->deactivate($name);

		// link in the new version
		$this->_shell->symlink($package->directory(), (string)$activeDir);

		foreach($package->spec()->executables() as $bin)
		{
			$this->_shell
				->chmod((string) new Path($activeDir, $bin), 0755)
				->symlink(
					(string) new Path($activeDir, $bin),
					(string) new Path($this->_env->executableDir(), basename($bin))
				);
		}

		return $this;
	}

	/**
	 * Deactvates a particular package
	 * @chainable
	 */
	public function deactivate($name)
	{
		$activeDir = new Path($this->_dir, self::ACTIVE_PATH, $name);

		if(!$this->_shell->isdir($activeDir))
			throw new Exception("Package $name isn't activated");

		$package = new Package($activeDir);

		// unlink executables
		foreach($package->spec()->executables() as $bin)
		{
			$this->_shell
				->unlink(
					(string) new Path($activeDir, $bin),
					(string) new Path($this->_env->executableDir(), basename($bin))
				);
		}

		// remove the active link
		$this->_shell->unlink((string)$activeDir);

		return $this;
	}
}
