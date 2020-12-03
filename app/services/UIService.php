<?php

namespace services;

use Ajax\php\ubiquity\JsUtils;
use Ajax\service\JArray;
use Ubiquity\orm\DAO;
use models\Question;
use models\User;

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
		    '',
		    'id',
		    'caption'
		] );
		$dt->fieldAsCheckbox('',[
		    'checked'=>$checked
		]);
		$dt->setIdentifierFunction ( 'getId');


		
		return $dt;
	}

}