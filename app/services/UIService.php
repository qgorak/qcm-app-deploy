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
		    $dt->setCaption(0,'');
		    $msg->setContent('Select your questions in the bank');
		    $msg->setIcon('arrow down');
		    $dt->insertDefaultButtonIn(4, 'x','_remove circular red',false,null,'remove');
		    $dt->setEmptyMessage($msg);
		    
		}else{
		    $dt->setCaption(0,'');
		    $msg->setContent('Empty');
		    $msg->setVariation('negative');
		    $msg->setIcon('exclamation triangle');
		    $dt->insertDefaultButtonIn(4, 'plus','_add circular green ',false,null,'add');
		    $dt->setEmptyMessage($msg);
		    $dt->setStyle('margin-top:0;padding-inline: 10px 20px;');
		    $toolbar = $this->jquery->semantic()->htmlMenu('Question Bank');
		    $toolbar->addPopupAsItem('Sort', 'sort','<div id="response-tag"></div>');
		    $toolbar->addHeader('Question Bank');
		    $toolbar->setClass('ui top attached menu');
		    
		    $dt->setToolbar($toolbar);
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
	
	public function getQuestionDataTable($questions){
	    $dt = $this->jquery->semantic ()->dataTable ( 'dtItems', Question::class, $questions );
	    $msg = new HtmlMessage ( '', "Aucun élément à afficher !" );
	    $msg->addIcon ( "x" );
	    $dt->setEmptyMessage ( $msg );
	    $dt->setFields ( [
	        'id',
	        'caption'
	    ] );
	    $dt->onRowClick('alert(\'ok\')');
	    $dt->setIdentifierFunction ( 'getId' );
	    $dt->addEditDeleteButtons ( false );
	    $dt->setEdition ();
	}

}