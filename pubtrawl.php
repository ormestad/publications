<?php
require 'lib/global.php';

if($USER->auth>1) {

} else {
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
				Pubtrawl status
			</div>

			<div class="card-section">
				<div class="button-group">
					<span class="start_trawl button">Begin trawl</span>
					<span class="pause_trawl warning button">Pause trawl</span>
				</div>
			</div>

			<div class="card-section">
				<div class="large-12 columns" id="trawlmeter">
					<div class="progress" role="progressbar" tabindex="0" aria-valuenow="0" aria-valuemin="0" aria-valuemax="0">
						<span id="trawlbar" class="progress-meter" style="width: 0%">
					    <p id="trawltext" class="progress-meter-text"></p>
					  </span>
					</div>
				</div>
				<div id="trawl_labs" class="large-12 columns"></div>
			</div>
		</div>
	</div>
</div>

<script src="js/vendor/jquery.js"></script>
<script src="js/vendor/what-input.js"></script>
<script src="js/vendor/foundation.min.js"></script>
<script src="js/app.js"></script>
</body>

</html>
