<?php

/**
 * This stub is loaded on every php invocation. It builds an include_path
 * based on the nearest Pharkdeps or Pharkspec file, otherwise it uses the  
 * system-wide activated packages path.
 **/

namespace Phark;

require_once __DIR__.'/lib/Phark/ClassLoader.php';
$classloader = new ClassLoader(array(__DIR__.'/lib'));
$classloader->register();

$dir = getcwd();
$includePath = explode(PATH_SEPARATOR,get_include_path());
$projectRoot = false;

// --------------------------------------------------------
// find the project root, either Pharkspec or Pharkdep file

do
{
	if(is_file("$dir/Pharkspec") || is_file("$dir/Pharkdeps"))
		$projectRoot = $dir;
	else
		$dir = basedir($dir);
} 
while(!$projectRoot && $dir);

// ----------------------------
// add system directories

if($projectRoot)
{
	array_unshift($includePath, "$projectRoot/vendor");
}
else
{
	$env = new Environment();
	array_unshift($includePath, $env->activePackages());
}

set_include_path(implode(PATH_SEPARATOR, $includePath));
	
