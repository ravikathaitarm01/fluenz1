<?php
/**
 * Author : Nisheeth Barthwal
 * Date   : 17 Jul 2013
 * Project: maveric
 *
 */

namespace sys\core;

/**
 * @param string $file The filename to load
 */
function mvc_autoloader($file)
{
	// Standardize the directory separators
	$file = str_replace('\\', DIRECTORY_SEPARATOR, $file);

	// Expand the 'sys' & 'app' namespaces
	$f = preg_replace(array('/^sys/', '/^app/'), array('system', 'application'), $file);
	if (file_exists(ROOTPATH.$f.'.php')) // Attempt to load the file given an absolute path
	{
		require_once $f.'.php';
	}
	else
	{
		$found = false;
		// Attempt to load the class from the following directories
		foreach (array(APPPATH, BASEPATH) as $path)
		{
			if (file_exists($path.$file.'.php'))
			{
				require_once $path.$file.'.php';
				$found = true;
				break;
			}
		}

		/* We will be nice and pass on to the next loader
		// If the class wasn't loaded for some darn reason
		if ( ! $found && ! class_exists($file))
		{
			// Disable raising if Zend Guard Loader is enabled
			// cause it obfuscates class names and oh well...
			if ( ! extension_loaded('Zend Guard Loader'))
			{
				Exception::error('Unable to load class : '.$file);
			}
		}
		*/
	}
}

// Register our autoloader
spl_autoload_register(__NAMESPACE__.'\\mvc_autoloader');