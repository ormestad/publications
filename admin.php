<?php
require 'lib/global.php';

if($USER->auth<2) {
	// Not authorized
	header('Location:not_authorized.php');
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
		<div class="card">
			<div class="card-divider">
				PubTrawl
			</div>
			<div class="card-section">
				Launch the huge and slow process of fetching publications from pubmed from <a href="/pubtrawl.php">here</a>.
			</div>
		</div>
	</div>
</div>

<div class="row">
	<br>
	<div class="large-12 columns">
		<div class="card">
			<div class="card-divider">
				Clarity LIMS sync status
			</div>
			<div class="card-section large-12 columns">
				<p>Update database with LIMS information of labs and researchers that will be used when trawling for publications. <br>Please check for and correct <a href="researchers.php?lab_status=error">errors</a> before starting trawl!</p>
				<hr>
				<div class="large-6 columns" id="clarity_status"></div>
				<div class="large-6 columns" id="clarity_status_message"></div>
			</div>
			<div class="card-section large-12 columns">
				<div class="button-group">
					<button class="small button right" id="load_clarity">Update</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<br>
	<div class="large-12 columns">
		<div class="card">
			<div class="card-divider">
				SciLifeLab Publication database sync status
			</div>
			<div class="card-section">
				<p>This will fetch all NGI Stockholm publications stored at <a href="https://publications.scilifelab.se/">publications.scilifelab.se</a> and mark each of them accordingly in this database.</p>
				<span class="start_sync button">Begin syncing from publications.scilifelab.se</span>
				<div id="sync_status_message"></div>
			</div>
		</div>
	</div>
</div>

<script src="js/vendor/jquery.js"></script>
<script src="js/vendor/what-input.js"></script>
<script src="js/vendor/foundation.js"></script>
<script src="js/app.js"></script>
</body>

</html>
