phark-paths(1) -- paths used by phark
=====================================

## SYNOPSIS

Phark uses are variety of paths to install it's stuff. Packages are
either installed system-wide or on a per-project basis. 

### Phark System

Phark installs by default to `/usr/local/phark`. Underneath this is:

* `packages`
  Site wide packages are stored under here, decompressed from .phar form. These are in the form
	`package@1.0.0`. For more details on version numbers see `versions`.
* `activated`
	The latest activated packages are symbolically linked here. This is the path that is included
	in the php `include_path`. See `runtime` for more details.
* `cache` 
  Packages are downloaded to this directory before they are installed. See `fetch` for more details.

Underneath a particular project, phark maintains a `vendor` directory. This is also included in
the php `include_path` and contains a mixture of actual package directories and symbolic links to
the system package directories.

In addtion, Phark uses the system temp directory, which defaults to `/tmp`. 

## MORE INFORMATION

Running under a local project, Phark attempts to find the nearest vendor directory to include. This
is accomplished by starting at the $PWD and iterating up the directory tree. Once found, the top level
is treated as the base directory.



