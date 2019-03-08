<?php
require 'lib/global.php';

global $CONFIG;
$errors = [];
if($USER->auth>1) {
	if(isset($_REQUEST['lab_id'])) {
		$pubmed=new PHPMed();
		$researchers=new NGIresearchers();
		$publications=new NGIpublications();

		if($lab=$researchers->getLab($_REQUEST['lab_id'])) {
			$added=0;
			$found=0;

			// Build pubmed query with a limitation on publication date
			date_default_timezone_set('UTC');
			$timestamp = time() - ($CONFIG['publications']['max_days'] * 86400);
			$dt = date('Y/m/d', $timestamp);
			$pmq = '('.$lab['query']['query_string']['pi'].')' . ' AND ("'.$dt.'"[Date - Publication] : "3000"[Date - Publication])';

			$data=$pubmed->parsedSearch($pmq);
			$found=count($data);
			foreach($data as $pmid => $article) {
				$add=$publications->addPublication($article,$lab);
				if($add['data']['status']=="added") {
					$added++;
				}
			}
		} else {
			// Lab not found
			$errors[]='Invalid Lab ID';
		}
	} else {
		// lab_id not set
		$errors[]='Lab ID not set';
	}
} else {
	// Not logged in
	$errors[]='Not logged in';
}

echo json_encode(array('added' => $added, 'found' => $found, 'errors' => implode(', ', $errors)));
