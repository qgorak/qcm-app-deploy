<?php

namespace services;

use Ajax\php\ubiquity\JsUtils;
use Ajax\service\JArray;
use Ubiquity\orm\DAO;
use models\Qcm;
use models\Question;
use Ajax\semantic\html\collections\HtmlMessage;

class UIService {
	protected $jquery;
	protected $semantic;
	public function __construct(JsUtils $jq) {
		$this->jquery = $jq;
		$this->semantic = $jq->semantic ();
	}
	public function questionDataTable($name,$questions,$checked) {
		$q = new Question ();
		$dt = $this->jquery->semantic ()->dataTable($name, $q,$questions);
		$dt->setFields ( [
		    'caption',
		    'point'
		] );
		$dt->setVariation('compact');
		$dt->setIdentifierFunction ( 'getId');
		$dt->setClass(['ui very basic table']);
		$msg = $this->jquery->semantic()->htmlMessage('');
		if($checked == true){
		    $dt->setCaption(0,'My qcm questions:');
		    $msg->setContent('Select your questions in the bank');
		    $dt->insertDefaultButtonIn(4, 'x','_remove circular red',false,null,'remove');
		    $dt->setEmptyMessage($msg);
		    
		}else{
		    $dt->setCaption(0,'Question bank:');
		    $msg->setContent('Empty');
		    $msg->setVariation('negative');
		    $msg->setIcon('exclamation triangle');
		    $dt->insertDefaultButtonIn(4, 'plus','_add circular green ',false,null,'add');
		    $dt->setEmptyMessage($msg);
		}
		$dt->setColWidths([0=>8,1=>1,2=>1]);
		$dt->setIdentifierFunction ( 'getId' );
		return $dt;
		
	}
	public function qcmForm() {
	    $q = new Qcm();
	    $frm = $this->jquery->semantic ()->dataForm ( 'qcmForm', $q );
	    $frm->setFields ( [
	        'name',
	        'description'
	    ] );
	    return $frm;
	}

}