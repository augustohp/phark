Development Status
==================

Phark is still under active development and is a fair way off from being usable. The
basic aim is to get a functional beta released that allows for the basics. Look at the list
below to see what's working and what need's doing. 

What's working?
---------------

* System installation
* Parsing of Pharkspec files
* Basic dependency resolution
* Local package installation. `phark install <directory>`.
* Listing local packages and project packages `phark list`
* Show working environment with `phark environment`

What needs doing?
-----------------

* More documentation, `phark help` should be wired to man files
* Fetching packages via HTTP `phark fetch`
* Fetching tarball of latest spec files via HTTP
* Parsing Pharkdeps files, installing project deps. `phark deps`
* Searching local specifications

What's left for the future
--------------------------
* Web app for pharkphp.org (signup, register cert, accept package)
* Submitting phark files to pharkphp.org. `phark publish`
* Searching remote specifications (proper search)
* Package signing (needed for publishing files)
* Support grouped dependencies (developent deps only, for instance)
* GIT support in Pharkdeps
* SVN support in Pharkdeps
* Dependency locking. `Pharkdeps.lock
