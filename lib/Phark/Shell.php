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

	/**
	 * Returns true if the file is a file (false for non-existant or a directory)
	 * @see is_file
	 */
	public function isfile($file)
	{
		return is_file($file);
	}
}

