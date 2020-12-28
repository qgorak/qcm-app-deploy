<?php

namespace services\UI;

use Ajax\php\ubiquity\JsUtils;
use Ajax\service\JArray;
use models\User;
use Ubiquity\controllers\Router;
use Ubiquity\orm\DAO;
use Ubiquity\translation\TranslatorManager;
use models\Exam;
use models\Answer;
use Ajax\semantic\html\elements\HtmlButton;

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

    public function OverseeUsersDataTable($exam){
        $usersg=DAO::getOneToMany($exam->getGroup(),'usergroups');
        $users=[];
        foreach($usersg as $userg){
            \array_push($users,$userg->getUser());
        }
        $dt=$this->jquery->semantic()->dataTable('OverseeUserDt',User::class,$users);
        $dt->setFields([
            'firstname',
            'lastname',
            'msg',
            'warning'
        ]);
        $dt->setCaptions([
            'firstname',
            'lastname',
            'Envoyer un message'
        ]);
        $dt->fieldAsIcon('warning');
        $dt->setValueFunction('msg',function($v,$e){return new HtmlButton('msg-'.$e->getId(),'send',null,'
        sendMessage('.$e->getId().');
        $(".user").html("'.$e->getFirstname().' '.$e->getLastname().'");
        $(".cheat").modal("show");
        ');});
        $dt->setIdentifierFunction( 'getId' );
        $dt->setActiveRowSelector("active");
        return $dt;
    }

    public function displayUserAnswers($answers){
        $this->jquery->semantic()->dataTable('userAnswers',Answer::class,$answers);
    }
    
    public function displayMyExamsInProgress($exams){
	    foreach($exams as $exam){
	        $exam->timer['time']=\strtotime($exam->getDatef())-\strtotime(\date("Y-m-d H:i:s"));
            $exam->timer['id']=$exam->getId();
        }
        $dt=$this->jquery->semantic()->dataTable('myExam',Exam::class,$exams);
        $dt->setFields([
            'qcm',
            'group',
            'datef',
            'timer'
        ]);
        $dt->setCaptions([
            TranslatorManager::trans('qcm',[],'main'),
            TranslatorManager::trans('group',[],'main'),
            TranslatorManager::trans('endDate',[],'main'),
            'remaining time'
        ]);
        $dt->insertDisplayButtonIn(4,false);
        $dt->setIdentifierFunction ( 'getId' );
        $dt->setValueFunction('qcm',function($v){return $v->getName();});
        $dt->setValueFunction('group',function($v){return $v->getName();});
        $dt->setValueFunction('timer',function($v){ return '<div id="timer-'.$v['id'].'"></div><script>createTimer('.$v['time'].',"#timer-'.$v['id'].'","'.Router::path('exam.get',[$v['id']]).'")</script>';});
        $this->jquery->ajaxOnClick ( '._display', Router::path('exam.oversee',['']) , '#response', [
            'hasLoader' => 'internal',
            'historize' => 'true',
            'method' => 'get',
            'attr' => 'data-ajax',
        ] );
        $this->jquery->exec('$("#icon-headerExamInProgress").transition("set looping").transition("fade", "2000ms");',true);
        return $dt;
    }

    public function displayMyComingExams($exams){
        foreach($exams as $exam){
            $exam->timer['time']=\strtotime($exam->getDatef())-\strtotime(\date("Y-m-d H:i:s"));
            $exam->timer['id']=$exam->getId();
        }
        $dt=$this->jquery->semantic()->dataTable('myComingExams',Exam::class,$exams);
        $dt->setFields([
            'qcm',
            'group',
            'dated',
            'datef',
            'timer'
        ]);
        $dt->setCaptions([
            TranslatorManager::trans('qcm',[],'main'),
            TranslatorManager::trans('group',[],'main'),
            TranslatorManager::trans('startDate',[],'main'),
            TranslatorManager::trans('endDate',[],'main'),
            'time before up'
        ]);
        $dt->setValueFunction('qcm',function($v){return $v->getName();});
        $dt->setValueFunction('group',function($v){return $v->getName();});
        $dt->setValueFunction('timer',function($v){ return '<div id="timer-'.$v['id'].'"></div><script>createTimer('.$v['time'].',"#timer-'.$v['id'].'","'.Router::path('exam.get',[$v['id']]).'")</script>';});
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