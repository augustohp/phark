<?php

namespace Phark;

/**
 * An implementation of Semantic Version parsing
 * @see http://semver.org/
 */
class Version
{
	public $major, $minor, $patch, $special;

	public function __construct($string)
	{
		foreach($this->_parse($string) as $key=>$value)
			if(!is_numeric($key)) $this->$key = $value;
	}

	private function _parse($string)
	{
		if(!preg_match('/^(?<major>\d+)\.(?<minor>\d+).(?:(?<patch>\d+)(?<special>\w+)?)?$/', $string, $m))
			throw new Exception("Unable to parse version $string");

		return $m;
	}

	public function __toString()
	{
		return sprintf('%d.%d.%d%s', $this->major, $this->minor, $this->patch, $this->special);
	}

	/**
	 * Returns 1 if this version is greater, 0 if the same, -1 if less 
	 * than
	 */
	public function compare($v)
	{
		$v = is_string($v) ? new self($v) : $v;
		$cmp = strcmp(
			sprintf('%d.%d.%d', $this->major, $this->minor, $this->patch),
			sprintf('%d.%d.%d', $v->major, $v->minor, $v->patch)
		);

		if($cmp == 0)
		{
			if(empty($this->special) && !empty($v->special)) 
				$cmp = 1;
			else if(!empty($this->special) && empty($v->special))
				$cmp = -1;
			else
				$cmp = strcmp($this->special, $v->special);
		}

		return $cmp;
	}

	public function greater($v) { return $this->compare($v) > 0; }
	public function greaterOrEqual($v) { return $this->compare($v) >= 0; }
	public function less($v) { return $this->compare($v) < 0; }
	public function lessOrEqual($v) { return $this->compare($v) <= 0; }
	public function equal($v) { return $this->compare($v) == 0; }

	/**
	 * Checks if a version is equal, ignoring the patch number and special code
	 */
	public function fuzzyEqual($v)
	{
		$clone = clone $this;
		$v = clone $v;
		$v->patch = $clone->patch = 0;
		$v->special = $clone->special = NULL;

		return $clone->equal($v);
	}

	/**
	 * Sorts a list of versions, latest first
	 */
	public static function sort($versions)
	{
		$versions = array_map(function($v) {
			return is_string($v) ? new Version($v) : $v;
		},$versions);

		usort($versions, function($a,$b) {
			return $a->compare($b) * -1;
		});

		return $versions;		
	}
}
