<?php
require 'lib/global.php';

if($USER->auth>0) {
	$researchers=new NGIresearchers();
	
	$affiliation_select[0]="All";
	$affiliations=sql_query("SELECT id,name FROM affiliations ORDER BY name");
	while($affiliation=$affiliations->fetch_assoc()) {
		$affiliation_select[$affiliation['id']]=$affiliation['name'];
	}

	$filterform=new htmlForm("researchers.php","get",4);
	$filterform->addSelect("Status","lab_status",array('all' => 'All', 'active' => 'Active labs', 'error' => 'Contains errors', 'disabled' => 'Disabled'),$_GET);
	$filterform->addSelect("Affiliation","lab_affiliation",$affiliation_select,$_GET);
	$filterform->addSelect("Order by","order_by",array('lab_name' => 'Lab name', 'lab_affiliation' => 'Affiliation'),$_GET);
	$filterform->addSelect("Sort","sort",array('asc' => 'Ascending', 'desc' => 'Descending'),$_GET);
	$filterform->addInput("",array('type' => 'submit', 'name' => 'submit', 'value' => 'Filter search', 'class' => 'button'));
	
	/*
	$lab_list=$researchers->listLabs();	
	$lab_errors=$researchers->formatLabList($lab_list['errors']);
	$all_labs=$researchers->formatLabList($lab_list['all']);

	$res_list=$researchers->listResearchers();
	$res_errors=$researchers->formatResearcherList($res_list['errors']);
	*/

	switch($_GET['order_by']) {
		default:
		case 'lab_name':
			$order_string=" ORDER BY lab_name";
		break;

		case 'lab_affiliation':
			$order_string=" ORDER BY lab_affiliation";
		break;
	}

	switch($_GET['sort']) {
		default:
		case 'asc':
			$order_string.=" ASC";
		break;

		case 'desc':
			$order_string.=" DESC";
		break;
	}
	
	switch($_GET['lab_status']) {
		default:
		case 'all':
			$filters=array();
		break;

		case 'active':
			$filters[]="lab_status='active'";
		break;

		case 'error':
			$filters[]="lab_status='error'";
		break;

		case 'disabled':
			$filters[]="lab_status='disabled'";
		break;
	}
	
	// Only add if value exists in Affiliation table
	$lab_affiliation=filter_var($_GET['lab_affiliation'],FILTER_SANITIZE_MAGIC_QUOTES);
	if(array_key_exists($lab_affiliation, $affiliation_select) && $lab_affiliation!='0') {
		$filters[]="lab_affiliation='$lab_affiliation'";
	}
	
	$query_string="SELECT * FROM labs";
	
	if(count($filters)>0) {
		$query_string.=' WHERE '.implode(' AND ',$filters);
	}
	
	$query=sql_query($query_string.$order_string);
	
	//$query=sql_query("SELECT * FROM labs WHERE lab_status!='disabled' ORDER BY lab_name");
	$lab_list=$researchers->showLabList($query,$_GET['page']);

} else {
	// Not logged in
	header('Location:login.php');
}

// Render Page
//=================================================================================================
?>

<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $CONFIG['site']['name']; ?></title>
	<link rel="stylesheet" href="css/foundation.css">
	<link rel="stylesheet" href="css/app.css">
	<link rel="stylesheet" href="css/icons/foundation-icons.css" />
</head>

<body>
<?php require '_menu.php'; ?>

<div class="row">
	<br>
	<div class="large-12 columns">
		<?php echo $filterform->render(); ?>
	</div>
	<div class="large-12 columns">
		<?php //echo $filterform->render(); ?>
	</div>
	<div class="large-12 columns">
		<?php echo $lab_list['list']; ?>
	</div>
	<div class="large-12 columns">
		<?php echo $lab_list['pagination']; ?>
	</div>

<!--
	<div class="large-12 columns">
		<h3>Errors</h3>
	</div>
	<div class="large-12 columns">
		<?php echo $lab_errors; ?>
	</div>
	<div class="large-12 columns">
		<?php echo $res_errors; ?>
	</div>
	<div class="large-12 columns">
		<h3>Labs</h3>
	</div>
	<div class="large-12 columns">
		<?php echo $all_labs; ?>
	</div>
-->
</div>

<script src="js/vendor/jquery.js"></script>
<script src="js/vendor/what-input.js"></script>
<script src="js/vendor/foundation.js"></script>
<script src="js/app.js"></script>
</body>

</html>

