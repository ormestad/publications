<?php
/*
class.html.php
=======================================================================================
Based on https://davidwalsh.name/create-html-elements-php-htmlelement-class


FORM EXAMPLE
=======================================================================================

$theform=new htmlForm("test.php");
$theform->addInput("Label 1",array('name' => 'number1', 'value' => ''));
$theform->addInput("Label 2",array('name' => 'number2', 'value' => ''));
$theform->addTextarea("Label 3",array('name' => 'number3'),"The text in the textarea");
$theform->addCheckboxes("Label 4","favorite_color",array('r' => 'Red', 'g' => 'Green', 'b' => 'Blue'), array('g'));
$theform->addSelect("Label 5","favorite_color",array('r' => 'Red', 'g' => 'Green', 'b' => 'Blue'), array('g'));
echo $theform->render();

LIST EXAMPLE
=======================================================================================

$standardlist=new htmlList();
$standardlist->listItem("Item 1");
$standardlist->listItem("Item 2");
$standardlist->listItem("Item 3");
$standardlist->listItem("Item 4");
echo $standardlist->render();

$definitionlist=new htmlList('dl');
$definitionlist->listItem(array("Title 1","Definition 1"));
$definitionlist->listItem(array("Title 2","Definition 2"));
$definitionlist->listItem(array("Title 3","Definition 3"));
echo $definitionlist->render();

Zurb Foundation Card
=======================================================================================

$card=new zurbCard();
$card->section('Header','card-divider',array('class' => 'a_class'));
$card->section('Content');
echo $card->render();

=======================================================================================

// Standard usage
$tabledata=array(
	array('color' => 'Red', 'animal' => 'Fox', 'car' => 'Ferrari'),
	array('color' => 'Green', 'animal' => 'Chameleon', 'car' => 'Skoda'),
	array('color' => 'Blue', 'animal' => 'Seal', 'car' => 'Volvo')
);

// An array with numerical indexes will not print table header
$tabledata_nocols=array(
	array('a','b','c'),
	array('alpher','bethe','gamow'),
	array('coke','pepsi','jolt')
);

// Advanced usage: individual cells can be targeted by sending the value as an array containing cell content in 'text' and additional attributes as an array under 'attrib'
$tabledata_advanced=array(
	array('color' => array('text' => 'Red', 'attrib' => array('style' => 'background-color: red;')), 'animal' => 'Fox', 'car' => 'Ferrari'),
	array('color' => array('text' => 'Green', 'attrib' => array('style' => 'background-color: green;')), 'animal' => 'Chameleon', 'car' => 'Skoda'),
	array('color' => array('text' => 'Blue', 'attrib' => array('style' => 'background-color: blue;')), 'animal' => 'Seal', 'car' => 'Volvo')
);

$table=new htmlTable('Favorite colors');
$table->addData($tabledata);
echo $table->render();

=======================================================================================
*/

class htmlElement {
	var $type;
	var $attributes;
	var $self_closers;

	function __construct($type,$self_closers = array('input','img','hr','br','meta','link')) {
		$this->type = strtolower($type);
		$this->self_closers = $self_closers;
	}

	/* get */
	function get($attribute) {
		return $this->attributes[$attribute];
	}

	/* set -- array or key,value */
	function set($attribute,$value = '') {
		if(!is_array($attribute)) {
			$this->attributes[$attribute] = $value;
		} else {
			if(!is_array($this->attributes)) {
				$this->attributes=array();
			}
			$this->attributes=array_merge($this->attributes,$attribute);
		}
	}

	// Merge attributes (e.g. add another class name to the already existing ones)
	function merge($attribute) {
		if(!is_array($this->attributes)) {
			$this->attributes=array();
		}

		foreach($attribute as $key => $value) {
			if(array_key_exists($key,$this->attributes)) {
				$classnames=explode(' ',$this->attributes[$key]);
				$classnames[]=$value;
				$this->attributes[$key]=implode(' ',$classnames);
			} else {
				$this->attributes[$key]=$value;
			}
		}
	}

	/* remove an attribute */
	function remove($att) {
		if(isset($this->attributes[$att])) {
			unset($this->attributes[$att]);
		}
	}

	/* clear */
	function clear() {
		$this->attributes = array();
	}

	/* inject */
	function inject($object) {
		if(@get_class($object) == __class__) {
			// Will otherwise throw a notice: undefined index
			if(!is_array($this->attributes)) {
				$this->attributes=array();
			}

			if(!array_key_exists("text",$this->attributes)) {
				$this->attributes['text']='';
			}

			$this->attributes['text'].=$object->build();
		}
	}

	/* build */
	function build() {
		//start
		$build = "\n<".$this->type;

		//add attributes
		if(count($this->attributes)) {
			foreach($this->attributes as $key=>$value) {
				if($key != 'text') {
					if(trim($value)=="") {
						$build.= ' '.$key;
					} else {
						$build.= ' '.$key.'="'.$value.'"';
					}
				}
			}
		}

		//closing
		if(!in_array($this->type,$this->self_closers)) {
			if(array_key_exists("text",$this->attributes)) {
				$content=$this->attributes['text'];
			} else {
				$content="";
			}
			$build.= '>'.$content.'</'.$this->type.">";
		} else {
			$build.= ">";
		}

		//return it
		return $build;
	}

	/* spit it out */
	function output() {
		return $this->build();
	}
}

// =======================================================================================

class htmlTable {
	var $children;

	function __construct($title='',$attrib=array()) {
		$this->parentelement=new htmlElement('table');
		$this->parentelement->set($attrib);
		$caption=new htmlElement('caption');
		$caption->set('text',$title);
		$this->children[]=$caption;
	}

	private function setHeader($data) {
		$first_row=array_shift($data);
		if($this->hasStringKeys($first_row)) {
			$tablehead=new htmlElement('thead');
			$cols=array_keys($first_row); // Get table headers
			if(is_array($cols)) {
				$row=new htmlElement('tr');
				foreach($cols as $key => $value) {
					$cell=new htmlElement('th');
					$cell->set('text',ucfirst(str_replace("_", " ", $value)));
					$row->inject($cell);
				}
				$tablehead->inject($row);
			}
		} else {
			$tablehead=FALSE;
		}

		return $tablehead;
	}

	private function setBody($data) {
		$tablebody=new htmlElement('tbody');
		foreach($data as $rowdata) {
			$row=new htmlElement('tr');
			foreach($rowdata as $key => $value) {
				$cell=new htmlElement('td');
				if(is_array($value)) {
					$cell->set('text',$value['text']);
					$cell->set($value['attrib']);
				} else {
					$cell->set('text',$value);
				}
				$row->inject($cell);
			}
			$tablebody->inject($row);
		}

		return $tablebody;
	}

	//http://stackoverflow.com/questions/173400/how-to-check-if-php-array-is-associative-or-sequential
	private function hasStringKeys(array $array) {
		return count(array_filter(array_keys($array), 'is_string')) > 0;
	}

	public function addData($data) {
		if(count($data)) {
			if($header=$this->setHeader($data)) {
				$this->children[]=$header;
			}
			$this->children[]=$this->setBody($data);
		} else {
			$this->children[]=$this->setBody(array(array('No data to show')));
		}
	}

	public function render() {
		if(is_array($this->children)) {
			foreach($this->children as $child) {
				$this->parentelement->inject($child);
			}
		}

		return $this->parentelement->output();
	}
}

// =======================================================================================
// Generate accordion menu HTML for Zurb Foundation 6
// See documentation for options: https://foundation.zurb.com/sites/docs/accordion.html

/*
$test=new zurbAccordion(TRUE,TRUE);
$test->addAccordion('Title 1','Blablabla');
$test->addAccordion('Title 2','Hehehe',TRUE);
$test->addAccordion('Title 3','Hahahahaha');
$test_string=$test->render();
*/

class zurbAccordion {
	function __construct($multiExpand=FALSE,$allowAllClosed=FALSE) {
		$this->parentelement=new htmlElement('ul');
		$this->parentelement->set('class','accordion');
		$this->parentelement->set('data-accordion');
		if($multiExpand) {
			$this->parentelement->set('data-multi-expand','true');
		}
		if($allowAllClosed) {
			$this->parentelement->set('data-allow-all-closed','true');
		}
	}

	public function addAccordion($title,$content,$active=FALSE) {
		$output=new htmlElement('li');
		$output->set('class','accordion-item');
		if($active) {
			$output->merge(array('class' => 'is-active'));
		}
		$output->set('data-accordion-item');

		$accordion_title=new htmlElement('a');
		$accordion_title->set('href','#');
		$accordion_title->set('class','accordion-title');
		$accordion_title->set('text',$title);
		$output->inject($accordion_title);

		$accordion_content=new htmlElement('div');
		$accordion_content->set('class','accordion-content');
		$accordion_content->set('data-tab-content');
		$accordion_content->set('text',$content);
		$output->inject($accordion_content);

		$this->children[]=$output;
	}

	public function render() {
		if(is_array($this->children)) {
			foreach($this->children as $child) {
				$this->parentelement->inject($child);
			}
		}

		return $this->parentelement->output();
	}
}

// =======================================================================================
// Generate pagination HTML for Zurb Foundation 6
// Inputs: current page, total pages, slots = number of pages to be shown

class zurbPagination {
	function __construct($attrib=array()) {
		$this->parentelement=new htmlElement('nav');
		$this->parentelement->set($attrib);
	}

	public function paginate($current,$total,$queryvars=array(),$slots=8) {
		$prev=$current-1;
		$next=$current+1;

		$listelement=new htmlElement('ul');
		$listelement->set('class','pagination');

		if($current==1) {
			$listelement->inject($this->addNav('Previous',FALSE,array('class' => 'pagination-previous disabled')));
		} else {
			//$listelement->inject($this->addNav('Previous','?page='.$prev,array('class' => 'pagination-previous')));
			$listelement->inject($this->addNav('Previous',$this->updateQueryVars($prev,$queryvars),array('class' => 'pagination-previous')));
		}

		if($total>$slots) {
			if($current<floor($slots/2)+1 || $current>$total-floor($slots/2)) {
				for($n=1;$n<=round($slots/2);$n++) {
					$listelement->inject($this->checkCurrent($n,$current,$queryvars));
				}
				$listelement->inject($this->addNav('',FALSE,array('class' => 'ellipsis')));
				for($n=$total-round($slots/2)+1;$n<=$total;$n++) {
					$listelement->inject($this->checkCurrent($n,$current,$queryvars));
				}
			} else {
				$listelement->inject($this->addNav('',FALSE,array('class' => 'ellipsis')));
				for($n=$current-round($slots/2);$n<=$current+round($slots/2);$n++) {
					$listelement->inject($this->checkCurrent($n,$current,$queryvars));
				}
				$listelement->inject($this->addNav('',FALSE,array('class' => 'ellipsis')));
			}
		} else {
			for($n=1;$n<=$total;$n++) {
				$listelement->inject($this->checkCurrent($n,$current,$queryvars));
			}
		}

		if($current==$total) {
			$listelement->inject($this->addNav('Next',FALSE,array('class' => 'pagination-next disabled')));
		} else {
			//$listelement->inject($this->addNav('Next','?page='.$next,array('class' => 'pagination-next')));
			$listelement->inject($this->addNav('Next',$this->updateQueryVars($next,$queryvars),array('class' => 'pagination-next')));
		}

		$this->parentelement->inject($listelement);
		return $this->parentelement->output();
	}

	private function updateQueryVars($page,$queryvars) {
		$queryvars['page']=$page;
		return '?'.http_build_query($queryvars);
	}

	private function checkCurrent($n,$current,$queryvars) {
		if($n==$current) {
			$nav=$this->addNav($n,FALSE,array('class' => 'current'));
		} else {
			$nav=$this->addNav($n,$this->updateQueryVars($n,$queryvars));
		}
		return $nav;
	}

	private function addNav($text,$link,$attrib=array()) {
		$output=new htmlElement('li');
		$output->set($attrib);
		if($link) {
			$linkElement=new htmlElement('a');
			$linkElement->set('href',$link);
			$linkElement->set('text',$text);
			$output->inject($linkElement);
		} else {
			$output->set('text',$text);
		}
		return $output;
	}
}

// =======================================================================================

class zurbCard {
	var $children;

	function __construct($attrib=array()) {
		$this->parentelement=new htmlElement('div');
		$this->parentelement->set($attrib);
		$this->parentelement->merge(array('class' => 'card'));
	}

	public function divider($content,$attrib=array()) {
		$output=new htmlElement('div');
		$output->set($attrib);
		$output->merge(array('class' => 'card-divider'));
		$output->set('text',$content);

		$this->children[]=$output;
	}

	public function section($content,$attrib=array()) {
		$output=new htmlElement('div');
		$output->set($attrib);
		$output->merge(array('class' => 'card-section'));
		$output->set('text',$content);

		$this->children[]=$output;
	}

	public function image($src,$attrib=array()) {
		$output=new htmlElement('img');
		$output->set('src',$src);
		$output->set($attrib);

		$this->children[]=$output;
	}

	public function render() {
		if(is_array($this->children)) {
			foreach($this->children as $child) {
				$this->parentelement->inject($child);
			}
		}

		return $this->parentelement->output();
	}
}

// =======================================================================================

class htmlList {
	var $children;

	function __construct($type="ul",$attrib=FALSE) {
		switch($type) {
			default:
				$this->type="ul";
			break;

			case "ul":
			case "ol":
			case "dl":
				$this->type=$type;
			break;
		}

		$this->listelement=new htmlElement($this->type);
		$this->listelement->set($attrib);
	}

	public function listItem($text,$attrib=FALSE) {
		if($this->type=="dl") {
			if(is_array($text)) {
				$output=new htmlElement('dt');
				$output->set('text',$text[0]);
				$this->children[]=$output;

				$output=new htmlElement('dl');
				$output->set('text',$text[1]);
				$this->children[]=$output;
			}
		} else {
			$output=new htmlElement('li');
			$output->set($attrib);
			$output->set('text',$text);
			$this->children[]=$output;
		}
	}

	public function render() {
		if(is_array($this->children)) {
			foreach($this->children as $child) {
				$this->listelement->inject($child);
			}
		}

		return $this->listelement->output();
	}
}

// =======================================================================================
// columns: render form in #n columns (formatting for Zurb Foundation)

class htmlForm {
	var $children;

	function __construct($action="",$method="post",$columns=1,$attrib=FALSE) {
		$this->formelement=new htmlElement('form');
		$this->formelement->set("action",$action);
		$this->formelement->set("method",$method);
		$this->columns=$columns;

		if(is_array($attrib)) {
			$this->formelement->set($attrib);
		}
	}

	private function labelWrap($label,$element) {
		if($label) {
			$output=new htmlElement('label');
			$output->set('text',$label);
			$output->inject($element);
			return $output;
		} else {
			return $element;
		}
	}

	public function addText($text,$attrib=array()) {
		$output=new htmlElement('p');
		$output->set($attrib);
		$output->set('text',$text);

		$this->children[]=$output;
	}

	public function addInput($label,$attrib=array(),$selected=FALSE) {
		$output=new htmlElement('input');
		if($selected) {
			$attrib=array_merge($attrib,array("value" => $selected));
		}
		$output->set($attrib);

		$this->children[]=$this->labelWrap($label,$output);
	}

	public function addTextarea($label,$attrib,$text) {
		$output=new htmlElement('textarea');
		$output->set($attrib);
		$output->set('text',$text);

		$this->children[]=$this->labelWrap($label,$output);
	}

	public function addCheckboxes($label,$name,$data,$selected=FALSE) {
		$output=new htmlElement('fieldset');
		$legend=new htmlElement('legend');
		$legend->set('text',$label);
		$output->inject($legend);
		foreach($data as $key => $value) {
			$checkboxID=$name.'_'.$key;
			$attrib=array("type" => "checkbox", "name" => $name, "value" => $key, "id" => $checkboxID);
			if(is_array($selected)) {
				if(in_array($key,$selected)) {
					$attrib=array_merge($attrib,array("checked" => ""));
				}
			}
			$input=new htmlElement('input');
			$input->set($attrib);
			$output->inject($input);
			$thelabel=new htmlElement('label');
			$thelabel->set('for',$checkboxID);
			$thelabel->set('text',$value);
			$output->inject($thelabel);
		}

		$this->children[]=$output;
	}

	public function addSelect($label,$name,$data,$selected=FALSE,$attrib=array()) {
		$output=new htmlElement('select');
		$output->set('name',$name);
		if(count($attrib)) { $output->set($attrib); }
		foreach($data as $key => $value) {
			$checkboxID=$name.'_'.$key;
			$attrib=array("value" => $key);
			if(is_array($selected)) {
				if(in_array($key,$selected)) {
					$attrib=array_merge($attrib,array("selected" => ""));
				}
			}
			$option=new htmlElement('option');
			$option->set($attrib);
			$option->set('text',$value);
			$output->inject($option);
		}

		$this->children[]=$this->labelWrap($label,$output);
	}

	public function render() {
		// Add all form elements
		if(is_array($this->children)) {
			if($this->columns>1) {
				$column_class=floor(12/$this->columns);
				$rows=ceil(count($this->children)/$this->columns);
				for($row=0;$row<$rows;$row++) {
					$container=new htmlElement('div');
					$container->set('class','row');
					for($col=0;$col<$this->columns;$col++) {
						$cell=new htmlElement('div');
						$cell->set("class","large-".$column_class." columns");
						if (array_key_exists($row*$this->columns+$col, $this->children)) {
							$cell->inject($this->children[$row*$this->columns+$col]);
							$container->inject($cell);
						}
					}
					$this->formelement->inject($container);
				}
			} else {
				foreach($this->children as $child) {
					$this->formelement->inject($child);
				}
			}
		}

		return $this->formelement->output();
	}
}
?>
