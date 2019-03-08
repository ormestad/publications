<?php
// Load configuration
require 'config.php';

session_start();

// Include libraries
require 'class.clarity.php';
require 'class.couch.php';
require 'class.html.php';
require 'class.uservalidation.php';
require 'class.alerthandler.php';
require 'class.NGIprojects.php';
require 'class.NGIresearchers.php';
require 'class.NGIpublications.php';
require 'class.PHPMed.php';
require 'class.NGIportal.php';

//Connect to database
$DB=new mysqli("p:".$CONFIG['mysql']['server'],$CONFIG['mysql']['user'],$CONFIG['mysql']['pass'],$CONFIG['mysql']['db']);
$DB->set_charset("utf8");
if($DB->connect_errno>0){
    die('Unable to connect to database [' . $DB->connect_error . ']');
}

// Initialize user validation
$USER=new Uservalidation();

// Initialize alert handler
$ALERTS=new alertHandler();

//--------------------------------------------------------------------------------------------------
// Global functions
//--------------------------------------------------------------------------------------------------

function sql_query($sql) {
	global $DB;
	if(!$result = $DB->query($sql)){
	    throw new Exception('There was an error running the query [' . $DB->error . '] : '.$sql);
	} else {
		if(is_object($result)) {
			if($result->num_rows>0) {
				return $result;
			} else {
				return FALSE;
			}
		} else {
			return $result;
		}
	}
}

// Fetch one row
function sql_fetch($sql) {
	if($query=sql_query($sql)) {
		$row=$query->fetch_assoc();
		return $row;
	} else {
		return FALSE;
	}
}
