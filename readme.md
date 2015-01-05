Composer Symlinker
==================

[![Downloads this Month](https://img.shields.io/packagist/dm/dg/composer-symlinker.svg)](https://packagist.org/packages/dg/composer-symlinker)

This tool forces the Composer to load some packages from different directories (instead of loading them from `/vendor`).
It is useful for developing and testing of these packages.

Composer Symlinker requires PHP 5.4.0 or newer. The best way how to install it is to use Composer:

```
composer create-project dg/composer-symlinker
```

Usage: Create file `packages.txt` containing list of directories, where are your packages located. For example:

```
w:\nette\application
w:\nette\latte
w:\tracy
```

Let Composer to load these packages from these directories:

```
php composer-symlinker packages.txt [<project_dir>]
```

When `project_dir` is not specified the current directory is used.
