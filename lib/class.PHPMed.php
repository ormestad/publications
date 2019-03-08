<?php

/*
------------------------------------------------------------------------------------
PHPMed
------------------------------------------------------------------------------------

PHP class for searching and retrieving publications from PubMed

Based on: https://github.com/asifr/PHP-PubMed-API-Wrapper and http://www.fredtrotter.com/2014/11/14/hacking-on-the-pubmed-api/



------------------------------------------------------------------------------------
*/

class PHPMed {
	public $retmax = 200; // Max number of results to return
	public $retstart = 0; // The search result number to start displaying data, useful for pagination
	public $retmode = 'json';
	public $term = '';
	public $db = 'pubmed';

	private $esearch = 'http://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?';
	private $esummary = 'http://eutils.ncbi.nlm.nih.gov/entrez/eutils/esummary.fcgi?';
	private $efetch = 'http://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?';

	// Format and return an array with all hits (raw data from pubmed API)
	// If publication has an abstract this is added as a new key ['abstract']
	public function parsedSearch($term) {
		if(trim($term)) {
			$query=$this->search($term);
			$pmid_list=implode(",", $query['data']['esearchresult']['idlist']);
			$data=$this->summary($pmid_list);
			return $data;
		} else {
			return FALSE;
		}
	}

	public function search($term) {
		$params = array(
			'db'		=> $this->db,
			'retmode'	=> $this->retmode,
			'retmax'	=> $this->retmax,
			'retstart'	=> $this->retstart,
			'term'		=> $term
		);

		$url=$this->esearch.http_build_query($params);
		return $this->getJSON($url);
	}

	// $pmid - either a single id or a comma separated list (or an array of PMID's)
	public function summary($pmid) {
		$count=0;
		$batch_size=50;

		if(is_array($pmid)) {
			$pmid_array=$pmid;
		} else {
			$pmid_array=explode(',', $pmid);
		}

		$total=count($pmid_array);

		// Fetch papers in batches of 50
		while($count*$batch_size<$total) {
			$start=$count*$batch_size;
			$slice=array_slice($pmid_array, $start, $batch_size);
			$pmid_list=implode(',', $slice);
			$params = array(
				'db'		=> $this->db,
				'retmode'	=> $this->retmode,
				'retmax'	=> $this->retmax,
				'id'		=> $pmid_list
			);
			$url=$this->esummary.http_build_query($params);
			$data=$this->getJSON($url);
			foreach($data['data']['result']['uids'] as $pmid) {
				$articles[$pmid]=$data['data']['result'][$pmid];
				if(in_array('Has Abstract', $data['data']['result'][$pmid]['attributes'])) {
					$articles[$pmid]['abstract']=$this->getAbstract($pmid);
				}
			}
			$count++;
		}

		return $articles;
	}

	private function getAbstract($pmid) {
		$params = array(
			'db'		=> $this->db,
			'retmode'	=> 'text',
			'rettype'	=> 'abstract',
			'retmax'	=> 1,
			'id'		=> $pmid
		);

		$url=$this->efetch.http_build_query($params);
		$data=file_get_contents($url);
		return $data;
	}

	private function getJSON($url) {
		$error=FALSE;

		try {
			$json=file_get_contents($url);
			if($json === FALSE) {
				// Can not retrieve JSON
				$error='Can not fetch results: '.$url;
			}
		} catch(Exception $e) {
			// Site unreachable
			$error='Source unavailable: '.$url;
		}

		return array('data' => json_decode($json,TRUE), 'error' => $error, 'query' => $url);
	}

}
