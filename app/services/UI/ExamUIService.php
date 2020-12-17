<?php

namespace services\UI;

use Ajax\php\ubiquity\JsUtils;
use Ajax\service\JArray;
use Ubiquity\controllers\Router;
use Ubiquity\translation\TranslatorManager;
use models\Exam;
use models\Question;

class ExamUIService {
    
	protected $jquery;
	protected $semantic;
	
	public function __construct(JsUtils $jq) {
		$this->jquery = $jq;
		$this->semantic = $jq->semantic ();
	}
	
	public function displayMyExams($exam){
	    $exams=$this->jquery->semantic()->dataTable('myExam',Exam::class,$exam);
	    $exams->setFields([
	        'dated',
	        'datef',
	        'qcm',
	        'group'
	    ]);
	    $exams->setCaptions([
	        TranslatorManager::trans('startDate',[],'main'),
	        TranslatorManager::trans('endDate',[],'main'),
	        TranslatorManager::trans('qcm',[],'main'),
	        TranslatorManager::trans('group',[],'main')
	    ]);
	    $exams->setValueFunction('qcm',function($v){return $v->getName();});
	    $exams->setValueFunction('group',function($v){return $v->getName();});
	}
	
	public function examForm($qcm,$groups){
	    $exam=$this->jquery->semantic()->dataForm('exam',new Exam());
	    $exam->setFields([
	        'idQcm',
	        'idGroup'
	    ]);
	    $exam->setCaptions([
	        TranslatorManager::trans('qcm',[],'main'),
	        TranslatorManager::trans('group',[],'main')
	    ]);
	    $exam->fieldAsDropDown('idQcm',JArray::modelArray($qcm,'getId','getName'));
	    $exam->fieldAsDropDown('idGroup',JArray::modelArray($groups,'getId','getName'));
	    $this->jquery->postFormOnClick('#examSubmit',Router::path ('examAddSubmit'),'examAdd','#response',[
	        'hasloader'=>'internal'
	    ]);
	}
}