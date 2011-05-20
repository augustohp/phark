<?php

namespace Phark;

class FileList
{
	private $_basedir, $_shell, $_files=array(), $_exclude=array();

	public function __construct($basedir, $shell)
	{
		$this->_basedir = $basedir;
		$this->_shell = $shell;
	}

	public function files()
	{
		$exclude = $this->_exclude;

		return array_values(array_filter($this->_files, function($f) use($exclude) {
			foreach($exclude as $pattern)
				if(FileList::match($f, $pattern)) return false;
			return true;
		}));
	}

	/**
	 * Add a glob pattern for inclusion to the file list
	 * @ see \Phark\Shell::glob()
	 */
	public function glob($pattern)
	{
		$this->_files += array_filter($this->_shell->glob($this->_basedir, $pattern), function($f) use($pattern) {
			return FileList::match($f, $pattern);
		});

		return $this;
	}

	/**
	 * Exclude a glob pattern from the file list
	 * @see \Phark\Shell::glob()
	 */
	public function exclude($pattern)
	{
		$this->_exclude []= $pattern;
		return $this;
	}	

	/**
	 * Match a glob to a filename
	 * return bool
	 */
	public static function match($filename, $glob)
	{
		$pattern = preg_replace('/\*\*/','(.+?)',$glob);
		$pattern = preg_replace('/\*/','([^/]+)',$pattern);
		$pattern = '#^'.$pattern.'$#';

		return preg_match($pattern, $filename);
	}
}
