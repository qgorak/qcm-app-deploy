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
	
	public function displayMyExams($exams){
	    $exams=$this->jquery->semantic()->dataTable('myExam',Exam::class,$exams);
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
	    $exams->setIdentifierFunction('getId');
	    $exams->setValueFunction('qcm',function($v){return $v->getName();});
	    $exams->setValueFunction('group',function($v){return $v->getName();});
	    return $exams;
	}

    public function usersDataTable($exam){
        $usersg=DAO::getOneToMany($exam->getGroup(),'usergroups');
        $users=[];
        foreach($usersg as $userg){
            \array_push($users,$userg->getUser());
        }
        $dt=$this->jquery->semantic()->dataTable('OverseeUserDt',User::class,$users);
        $dt->setFields([
            'avatar',
            'firstname',
            'lastname',
            'info',
        ]);
    //    $dt->fieldAsLabel('status',null,['class'=>'ui grey empty circular label']);
        $dt->fieldAsAvatar('avatar');
        $dt->fieldAsIcon('info');
        $dt->setValueFunction('msg',function($v,$e){return new HtmlButton('msg-'.$e->getId(),'send',null,'
        sendMessage('.$e->getId().');
        $(".user").html("'.$e->getFirstname().' '.$e->getLastname().'");
        $(".cheat").modal("show");
        ');});
        $dt->setValueFunction('avatar',function($a) {
            if ($a[0] == '#') {
                return '<div class="avatarDt"><div style="background:'.$a.'" class="baseAvatarDt"></div><div class="status ui grey empty circular label"></div><i style="position: absolute;top: 5px;left: 4px;font-size:1.2em;color: white;" class="graduation cap icon"></i></div>';
            } else {
                return '<div class="avatarDt"><img style="margin-right: auto" class="ui avatar image" src="'.$a.'"><div class="status ui grey empty circular label"></div></div>';
            }
        }
        );
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

    public function displayMyPastExams($exams){
        $dt=$this->jquery->semantic()->dataTable('myPastExams',Exam::class,$exams);
        $dt->setFields([
            'qcm',
            'group',
            'dated',
            'datef'
        ]);
        $dt->setCaptions([
            TranslatorManager::trans('qcm',[],'main'),
            TranslatorManager::trans('group',[],'main'),
            TranslatorManager::trans('startDate',[],'main'),
            TranslatorManager::trans('endDate',[],'main'),
        ]);
        $dt->setIdentifierFunction('getId');
        $dt->setValueFunction('qcm',function($v){return $v->getName();});
        $dt->setValueFunction('group',function($v){return $v->getName();});
        $dt->insertDefaultButtonIn(4,'eye','_report',false);
        $this->jquery->getOnClick('._report',Router::path('exam.report'),'#response',[
            'attr'=>'data-ajax'
        ]);
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