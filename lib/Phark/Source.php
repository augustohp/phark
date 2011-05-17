<?php

namespace Phark;

interface Source
{
	/**
	 * Finds matching packages 
	 */
	public function find($name, Requirement $requirement=null);
}
