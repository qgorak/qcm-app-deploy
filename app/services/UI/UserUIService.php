<?php

namespace services\UI;

use Ajax\php\ubiquity\JsUtils;
use models\Exam;
use models\Group;
use Ubiquity\controllers\Router;
use Ubiquity\translation\TranslatorManager;

class UserUIService {
    
	protected $jquery;
	protected $semantic;
	
	public function __construct(JsUtils $jq) {
		$this->jquery = $jq;
		$this->semantic = $jq->semantic ();
	}
	
	public function displayInfos($user){
        $info=$this->jquery->semantic()->dataForm('myInfo',$user);
        $info->setFields([
            'language',
        	'code_style',
            'submitLang'
        ]);
        $info->setCaptions([
            TranslatorManager::trans('language',[],'main'),
        	TranslatorManager::trans('code_style',[],'main')
        ]);
        $info->setIdentifierFunction ( 'getId' );
        $info->fieldAsInput('email');
        $info->fieldAsDropDown('language',['en_EN'=>'English','fr_FR'=>'Français']);
        $info->fieldAsDropDown('code_style',['dark'=>TranslatorManager::trans('dark',[],'main'),'default'=>TranslatorManager::trans('light',[],'main')]);
        $info->fieldAsSubmit('submitLang',null, Router::path('langSubmit'),'#response',[
            'value'=>TranslatorManager::trans('submitLang',[],'main')
        ]);
	}

    public function displayDashboard($groups,$waitInGroups,$exams){
        $dtWait=$this->jquery->semantic()->dataTable('waitInGroups', Group::class, $waitInGroups);
        $dtWait->setFields ( [
            'name',
            'description',
            'wait'
        ] );
        $dtWait->setCaptions([
            TranslatorManager::trans('name',[],'main'),
            TranslatorManager::trans('description',[],'main')
        ]);
        $dtWait->fieldAsElement('wait','i','hourglass icon');
        $dtWait->setIdentifierFunction ( 'getId' );
        $dtWait->setClass(['ui single line very basic table']);
        $dtInGroups = $this->jquery->semantic ()->dataTable ( 'inGroups', Group::class, $groups );
        $dtInGroups->setFields ( [
            'name',
            'description'
        ] );
        $dtInGroups->setCaptions([
            TranslatorManager::trans('name',[],'main'),
            TranslatorManager::trans('description',[],'main')
        ]);
        $dtInGroups->setClass(['ui single line very basic table']);
        $dtInGroups->setIdentifierFunction ( 'getId' );
        $dtInExams = $this->jquery->semantic ()->dataTable ( 'pastExam', Exam::class, $exams);
        $dtInExams->setFields([
            'dated',
            'datef',
            'qcm',
            'group'
        ]);
        $dtInExams->setCaptions([
            TranslatorManager::trans('startDate',[],'main'),
            TranslatorManager::trans('endDate',[],'main'),
            TranslatorManager::trans('qcm',[],'main'),
            TranslatorManager::trans('group',[],'main')
        ]);
        $dtInExams->setValueFunction('qcm',function($v){return $v->getName();});
        $dtInExams->setValueFunction('group',function($v){return $v->getName();});

    }
}