<?php
require 'lib/global.php';

if($USER->auth>0) {
	if(isset($_REQUEST['lab_id'])) {
		$pubmed=new PHPMed();
		$researchers=new NGIresearchers();
		$publications=new NGIpublications();
		
		if($lab=$researchers->getLab($_REQUEST['lab_id'])) {
			$added=0;
			$found=0;
			
			$data=$pubmed->parsedSearch($lab['query']['query_string']['pi']);
			$found=count($data['result']['uids']);
			
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