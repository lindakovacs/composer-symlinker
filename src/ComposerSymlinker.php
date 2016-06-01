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
			} elseif (isset($this->files[$class]) && $this->files[$class] !== $file) {
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
		$file = "$projectDir/vendor/composer/autoload_classmap.php";
		if (!is_file($file)) {
			throw new Exception("Missing $file");
		}

		$map = include $file;
		$map = $this->files + $map;
		$export = var_export($map, TRUE);
		file_put_contents($file, "<?php return $export;");

		$file = "$projectDir/vendor/composer/autoload_static.php";
		if (is_file($file)) {
			$content = file_get_contents($file);
			$content = preg_replace('#\$classMap = array \(.*?\);#s', addcslashes("\$classMap = $export;", '$\\'), $content);
			file_put_contents($file, $content);
		}
	}

}
