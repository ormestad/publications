<?php
// Please remove "_template" from file name after adding necessary credentials

$CONFIG=array(
	'site' => array(
		'name'		=> '',
		'url'		=> '',
		'subdir'	=> '',
		'admin'		=> ''
	),
	'mysql' => array(
		'user' 		=> '',
		'pass' 		=> '',
		'db' 		=> '',
		'server'	=> ''
	),
	'clarity' => array(
		'user' 		=> '',
		'pass' 		=> '',
		'uri'		=> ''
	),
	'couch' => array(
		'user' 		=> '',
		'pass' 		=> '',
		'host'		=> '',
		'port'		=> ,
		'views'		=> array(
			'users'		=> '',
			'projects'	=> ''
		)
	),
	'portal' => array(
		'user' 		=> '',
		'pass' 		=> '',
		'host'		=> ''
	),
	'uservalidation' => array(
		'allowed'	=> FALSE,
		'salt'		=> '',
		'roles'		=> array('Unregistered','User','Manager','Administrator'),
		'status'	=> array('Blocked','Unconfirmed','Active')
	),
	'publications' => array(
		'current_year' => 2018, 
		'max_days' => 730,
		'parse_cmd' => 'check_publis.py',
		'keywords'	=> array(
		)
	)
);
