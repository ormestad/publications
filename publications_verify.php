<?php
require 'lib/global.php';

if($USER->auth>0) {
	$publications=new NGIpublications();
	if(!$page=filter_input(INPUT_GET,'page',FILTER_VALIDATE_INT)) {
		$page=1;
	}

	$query=$publications->reservePublications($USER->data['user_email'],date('Y'),8);
	$publication_list=$publications->showPublicationList($query,$page);
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
	<link rel="stylesheet" href="css/foundation.min.css">
	<link rel="stylesheet" href="css/app.css">
	<link rel="stylesheet" href="css/icons/foundation-icons.css" />
</head>

<body>
<?php require '_menu.php'; ?>

<div class="row">
	<br>
	<div class="large-12 columns">
		<ul class="accordion" data-accordion data-allow-all-closed="true">
			<li class="accordion-item" data-accordion-item="">
				<a href="#" class="accordion-title"><h4><i class="fi-info" id="how_to_info"></i>Classification Guidelines</h4></a>
				<div class="accordion-content" data-tab-content>
					<dl>
						<dt>Verify:</dt>
						<dd>You are sure that this publication was made possible by sequencing at NGI Stockholm.</dd>

						<dt>Maybe:</dt>
						<dd>You are unsure how to classify this publication, for example:
							<ul>
								<li>The authors credits NGI in the acknowledgements but don’t specify which facility (Maybe this was sequenced in Uppsala?)</li>
								<li>You know the author is a PI is from a Swedish university and that they have used our facility before (check the “researchers and labs” page). And the full-text matches hints to sequencing data being used, e.g. “...was sequenced on Illumina HiSeq X platform…”. But it isn’t clear WHERE it was sequenced.</li>
								<li>Many other reasons. Maybe you have worked on a user project (in the lab or doing bioinformatics work) where you know the author have used our platform and you suspect the publication might be related to that project. E.g, “Didn’t I prep a library for R.Olsen two years ago? And wasn’t that also RNAseq of [some organism] from [some exotic part of the world]?”</li>
							</ul>
						</dd>
						<dt>Discard:</dt>
						<dd>You are sure that this publication is not related to sequencing at NGI Stockholm.</dd>
					</dl>
				</div>
			</li>
		</ul>
	</div>
	<div class="large-12 columns">
		<?php echo $publication_list['list']; ?>
	</div>
	<div class="large-12 columns">
		<?php echo $publication_list['pagination']; ?>
	</div>
</div>

<script src="js/vendor/jquery.js"></script>
<script src="js/vendor/what-input.js"></script>
<script src="js/vendor/foundation.min.js"></script>
<script src="js/app.js"></script>
</body>

</html>
