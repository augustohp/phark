phark(1) -- a package manager for modern php
============================================

## SYNOPSIS

Phark is a package manager for recent versions of PHP (5.3.1+). It 
provides dependencies, versioning and installation for packages either 
system-wide or for an individual project.

In addition to managing packages locally, Phark allows publishing
of packages to <http://pharkphp.org> for easy consumption by other 
developers.

See `phark help` for a list of available commands.

## INTRODUCTION

The simplest use-case is installing a package. To install a package
system-wide, use `phark install mypackage -g`. For installing a package 
to the project directory you are in use `phark install mypackage`. For 
more details see `phark help install`.

To list the packages installed, use `phark list` for the present
project's packages and the globally available ones. See `phark help list`
for how this works.

## PACKAGE CONSUMERS

If you're writing code and you want to use phark packages in your project,
you are a package consumer. To specify which dependencies your project
requires, you need a Pharkdeps file. See `phark help pharkdeps`.

When you checkout a project with a Pharksdep file, use the `phark deps`
command to ensure they are all up-to-date. See `phark help deps`.

## PACKAGE DEVELOPERS

To publish your package, you must have a project with a Pharkfile in it
describing the package. See `phark help pharkfile` for more details about
this.

Relevant commands are available in the following help topics:

* init
  Answer some questions, create a basic Pharkfile. See `phark help init`.
* publish
  Publish your current package to <http://pharkphp.org>. See `phark help publish`
* yank
  Made a mistake? Yank the package quickly. See `phark help yank`

## CONFIGURATION

At present there isn't any configuration for Phark. I'm sure there will be
eventually. To see what Phark has installed and where, checkout the output of
`phark env`.

## CONTRIBUTIONS AND BUGS

I'd love help, whether it's code, documentation, crituques or criticism.

If you find issues, report them:

* github: <http://github.com/lox/phark/issues>
* email: phark-dev@googlegroups.com

Providing the output of `phark env` will help us debug problems.

## HISTORY

See <http://github.com/lox/phark>.

## AUTHOR

Lachlan Donald :: lox :: @lox :: <lachlan@ljd.cc>

