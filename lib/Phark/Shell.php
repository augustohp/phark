<?php

namespace Phark;

class Shell
{
	public function getcwd()
	{
		return getcwd();
	}

	public function glob()
	{
		throw new \BadMethodCallException(__METHOD__ . ' not implemented');
	}
}

