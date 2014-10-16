<?php
//let's not hardcode strings
defined('EVENT_RECURRING_NAMESPACE') 	or define('EVENT_RECURRING_NAMESPACE', 'EventRecurring');
defined('EVENT_RECURRING_LIBRARY_PATH') or define('EVENT_RECURRING_LIBRARY_PATH', 'library');

function example_autoloader($className, $fileExtension = '.php') 
{
	if(0 === strpos($className, EVENT_RECURRING_NAMESPACE)) {
		$resolved = str_replace(EVENT_RECURRING_NAMESPACE, EVENT_RECURRING_LIBRARY_PATH, $className);
		$classPath = strtr($resolved, '\\', DIRECTORY_SEPARATOR);
		$filePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . $classPath;
		if(file_exists($filePath . $fileExtension) && is_readable($filePath . $fileExtension)) {
			require_once $filePath . $fileExtension;
		}
	}
}

spl_autoload_register('example_autoloader'/*,true, false*/);