<?php

namespace services\UI;

use Ajax\php\ubiquity\JsUtils;
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
	
	public function displayInfos($user,$groups,$waitInGroups){
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
        $dtWait=$this->jquery->semantic()->dataTable('waitInGroups', Group::class, $waitInGroups);
        $dtWait->setFields ( [
            'id',
            'name',
            'description',
            'wait'
        ] );
        $dtWait->setCaptions([
            'id',
            TranslatorManager::trans('name',[],'main'),
            TranslatorManager::trans('description',[],'main')
        ]);
        $dtWait->fieldAsElement('wait','i','hourglass icon');
        $dtWait->setIdentifierFunction ( 'getId' );
        $dtInGroups = $this->jquery->semantic ()->dataTable ( 'inGroups', Group::class, $groups );
        $dtInGroups->setFields ( [
            'name',
            'description'
        ] );
        $dtInGroups->setCaptions([
            "Groups I'm in",
        ]);
        $dtInGroups->setClass(['ui single line very basic table']);
        $dtInGroups->setIdentifierFunction ( 'getId' );

	}
}