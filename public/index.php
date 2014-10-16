<?php

//display all errors
ini_set('error_reporting', 1);
ini_set('display_errors', E_ALL);

//defined('SILLY_TESTS') or define('SILLY_TESTS', true);//using this definition may result in memory limit or maximum execution time hit

try {
	
	//include library
	require_once '../vendor/event/recurring/autoloader.php';
	
	//get an instance of a scheduler
	$scheduler = new \EventRecurring\Scheduler();
	$example = array();
	
	//Usage example 1
	$example['count limit'] = $scheduler->schedule(1388962800, '1. March 2014.', '31. March 2014', 'weekly', 11);
	//Usage example 2
	$example['date limit'] = $scheduler->schedule('6. January 2014', '1. March 2014.', '31. March 2014', 'daily', 0, '15. March 2014');
	//Usage example 3
	$example['no limit'] = $scheduler->schedule('6. January 2014', '1. March 2014', '31. March 2014', 'odd_workdays', 0, null);
	
	//Some more examples
	$example['count and date limit'] = $scheduler->schedule('6. January 2014', '1. March 2014', '31. March 2014', 'odd_workdays', 25, null);
	$example['very traditional'] = $scheduler->schedule('6. January 1997', '1. March 2014', '31. March 2014', 'odd_workdays', 2750, null);
	$example['very traditional in the future'] = $scheduler->schedule('6. January 1997', '1. March 2014', '31. March 2015', 'odd_workdays', 2750, null);
	$example['very traditional in the future, limited'] = $scheduler->schedule('6. January 1997', '1. March 2014', '31. March 2015', 'odd_workdays', 2750, '15. April 2014');
	$example['only dates'] = $scheduler->schedule('6. January 1997', '1. March 2014', '31. March 2015');
	
	//display results
	echo "<pre>"; print_r($example);
	
} catch (Exception $e) {
	//something went wrong
	echo $e->getMessage();
}