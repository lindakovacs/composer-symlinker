<?php

/**
 * Copyright (c) 2015 David Grudl (https://davidgrudl.com)
 */


class ComposerSymlinker
{
	/** @var string[] */
	private $files = [];


	/**
	 * @return void
	 */
	public function addPackage($dir)
	{
		$autoload = "$dir/vendor/composer/autoload_classmap.php";
		if (!is_file($autoload)) {
			throw new Exception("Missing $autoload");
		}

		$vendorDir = realpath($dir) . DIRECTORY_SEPARATOR . 'vendor/';
		$map = include $autoload;
		foreach ($map as $class => $file) {
			if (strpos($file, $vendorDir) === 0) {
				// ignore package vendor dir
			} elseif (isset($this->files[$class])) {
				throw new Exception("Class $class found in {$this->files[$class]} and in $file.");
			} else {
				$this->files[$class] = $file;
			}
		}
	}


	/**
	 * @return void
	 */
	public function apply($projectDir)
	{
		$autoload = "$projectDir/vendor/composer/autoload_classmap.php";
		if (!is_file($autoload)) {
			throw new Exception("Missing $autoload");
		}

		$map = include $autoload;
		$map = $this->files + $map;
		file_put_contents($autoload, '<?php return ' . var_export($map, TRUE) . ';');
	}

}
