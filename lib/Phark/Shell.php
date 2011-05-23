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
		$files = array();
		$iterator = new \RecursiveIteratorIterator(
			new \RecursiveDirectoryIterator($basedir, \RecursiveDirectoryIterator::FOLLOW_SYMLINKS));

		foreach ($iterator as $file)
		{
			$path = new Path(substr($file->getPath(), strlen($basedir)+1), $file->getFilename());
			
			if(FileList::match($path, $pattern)) 
				$files []= (string) $path;
		}

		return $files;
	}

	public function mkdir($dir, $perms=0755)
	{
		if(!is_dir($dir))
		{
			if(!@mkdir($dir, $perms, true))
				throw new ShellException("Unable to mkdir $dir: ".$this->_lastError());
		}

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

		if(!@copy($source, $dest))
			throw new ShellException("Unable to copy $source to $dir: ".$this->_lastError());

		return $this;	
	}

	public function symlink($target, $link)
	{
		if(!@symlink($target, $link))
			throw new ShellException("Unable to link $link to $target: ".$this->_lastError());

		return $this;
	}

	public function chmod($file, $mode)
	{
		if(!chmod($file, $mode))
			throw new ShellException("Unable to chmod $file to $mode: ".$this->_lastError());

		return $this;
	}

	public function unlink($file)
	{
		if(!unlink($file))
			throw new ShellException("Unable to unlink $file: ".$this->_lastError());

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

	private function _lastError()
	{
		$last = error_get_last();
		return $last['message'];
	}
}

