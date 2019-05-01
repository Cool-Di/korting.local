<?
/**
 * AutoLoader
 *
 * After registering this autoload function with SPL, the following line
 * would cause the function to attempt to load the \Adv\Bar\Baz\Qux class
 * from /path/to/php_interface/lib/Vendor/Bar/Baz/Qux.php:
 * 
 *	  new \Vendor\Bar\Baz\Qux;
 *	  
 * @param string $class The fully-qualified class name.
 * @return void
 */
spl_autoload_register(
	function($sClassName) {

		if(strpos($sClassName, '\\') === false) {
			return;
		}

		$sBaseDir = __DIR__.'/';
		$sFilePathAbs = $sBaseDir.str_replace('\\', '/', $sClassName).'.php';

		if(file_exists($sFilePathAbs)) {
			require($sFilePathAbs);
		} else {
			// 
		}
	}
);
