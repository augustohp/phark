<?php

namespace Phark;

/**
 * Resolves dependencies using a simple graph
 * @see http://www.electricmonk.nl/log/2008/08/07/dependency-resolving-algorithm/
 * @see http://www.pkgcore.org/~ferringb/misc-pdfs/model.pdf
 */
class DependencyResolver
{
	private $_requirements=array();
	private $_tree;
	private $_sources=array();

	/**
	 * Adds a specification to be installed
	 * @chainable
	 */
	public function specification(Specification $spec)
	{
		$this->dependency($spec->name(), new Requirement((string)$spec->version()));
		return $this;
	}

	private function _sourceFind($package)
	{
		$requirement = new Requirement($this->_requirements[$package]);

		foreach($this->_sources as $source)
		{
			if($spec = $source->find($package, $requirement))
				return $spec;
		}

		throw new Exception("Failed to find a spec that matches $package $requirement");
	}

	/**
	 * Adds a dependency to the resolver, also a leaf to the internal tree
	 * @chainable
	 */
	public function dependency($name, Requirement $requirement)
	{
		$this->_requirements[$name] []= (string) $requirement;
		$spec = $this->_sourceFind($name);

		// protect against circular dependencies
		if(isset($this->_tree[$spec->hash()]))
			return $this;

		$this->_tree[$spec->hash()] = (object) array(
			'name'=>$spec->hash(),
			'depends'=>array(),
		);

		// recurse into dependancies
		foreach($spec->dependencies() as $dep)
		{
			$this->dependency($dep->package, $dep->requirement);	
			$depSpec = $this->_sourceFind($dep->package);

			// add an edge
			$this->_tree[$spec->hash()]->depends []= $this->_tree[$depSpec->hash()];
		}

		return $this;
	}

	/**
	 * Adds a source to use to lookup specs
	 * @chainable
	 */
	public function source(Source $source)
	{
		$this->_sources []= $source;
		return $this;
	}

	/**
	 * Given a particular spec, returns an array of specific versions
	 * to install in order
	 * @return array
	 */
	public function resolve($spec)
	{
		$this->specification($spec);

		$traversed = array();
		$this->_traverse($spec->hash(), $traversed);

		return array_reverse($traversed);
	}

	// TODO: tail recursion would be preferable here
	private function _traverse($name, &$traversed)
	{
		$traversed []= $name;

		foreach($this->_tree[$name]->depends as $leaf)
		{
			if(!in_array($leaf->name, $traversed))
				$this->_traverse($leaf->name, $traversed);
		}
	}

	/**
	 * Dumps the internal tree and dependencies
	 */
	public function debug()
	{
		print("Requirements:\n");

		foreach($this->_requirements as $package=>$req)
		{
			printf(" %s %s\n", $package, implode(', ', $req));
		}

		print("\nDependency Tree:\n");

		foreach($this->_tree as $leaf)
		{
			printf(" %s [%s]\n", $leaf->name,
			 	implode(', ', array_map(function($l) { return $l->name; }, $leaf->depends)));
		}
	}
}
