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

$includePath = explode(PATH_SEPARATOR, get_include_path());

// either work in project mode or system-wide mode
if($project = Project::locate())
{
	$includePath = array_merge($project->includePaths(), $includePath);
}
else
{
	$env = new Environment();
	array_unshift($includePath, $env->activePackages());
}

// override the include path
set_include_path(implode(PATH_SEPARATOR, $includePath));
	
