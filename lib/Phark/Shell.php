<?php

namespace Phark;

/**
 * A wrapper for access to the shell
 */
class Shell
{
	/**
	 * Get the current working directory
	 */
	public function getcwd()
	{
		return getcwd();
	}

	/**
	 * Return a list of files relative to a path, matching a pattern.
	 * E.g lib/**, bin/*.php
	 */
	public function glob($basedir, $pattern)
	{

		$iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($basedir));
		$files = array();

		foreach ($iterator as $file)
		{
			//FIXME: this is slow and has loads of stat() calls.
			$path = substr($file->getRealPath(), strlen(realpath($basedir))+1);
			
			if(FileList::match($path, $pattern)) 
				$files []= $path;
		}

		return $files;
	}

	public function mkdir($dir, $perms=0755)
	{
		if(!is_dir($dir))
			mkdir($dir, $perms, true);
		return $this;
	}

	/**
	 * Returns true if the file is a file (false for non-existant or a directory)
	 * @see is_file
	 */
	public function isfile($file)
	{
		return is_file($file);
	}

	/**
	 * Returns true if the dir is a dir 
	 * @see is_dir
	 */
	public function isdir($dir)
	{
		return is_dir($dir);
	}

	/**
	 * Copies a file to another path
	 */
	public function copy($source, $dest)
	{
		if(!is_dir(dirname($dest))) 
			$this->mkdir(dirname($dest));
		
		$source = realpath($source);
		copy($source, $dest);

		return $this;	
	}

	public function symlink($target, $link)
	{
		//FIXME: error handling
		symlink($target, $link);
		return $this;
	}

	public function chmod($file, $mode)
	{
		chmod($file, $mode);
		return $this;
	}

	/**
	 * Outputs a line to STDOUT
	 * @chainable
	 */
	public function printf($string)
	{
		call_user_func_array('printf', func_get_args());
		return $this;
	}
}

