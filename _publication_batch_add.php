<?php
require 'lib/global.php';
$researchers=new NGIresearchers();
if($USER->auth>1) {
	$labs_query=sql_query("SELECT * FROM labs WHERE lab_status <> 'disabled' or lab_status IS NULL ORDER BY lab_name");
	$labs=[];
	while($lab=$labs_query->fetch_assoc()) {
		$id = $researchers->getClarityID($lab['lab_clarity_uri']);
		$labs[$id]=$lab;
		$labs[$id]['session'] = 'pending';
	}
	echo json_encode($labs);
} else {
	// Not logged in
	$errors[]='Not logged in';
}
?>
