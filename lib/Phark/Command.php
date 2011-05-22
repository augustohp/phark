<?php

namespace Phark;

interface Command
{
	public function summary();
	public function execute($args, $env);
}
