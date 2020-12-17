<?php

namespace services\UI;

use Ajax\php\ubiquity\JsUtils;
use Ubiquity\controllers\Router;
use Ubiquity\translation\TranslatorManager;

class UserUIService {
    
	protected $jquery;
	protected $semantic;
	
	public function __construct(JsUtils $jq) {
		$this->jquery = $jq;
		$this->semantic = $jq->semantic ();
	}
	
	public function displayInfos(){
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
        $info->fieldAsDropDown('language',['en_EN'=>'English','fr_FR'=>'Français']);
        $info->fieldAsDropDown('code_style',['dark'=>TranslatorManager::trans('dark',[],'main'),'default'=>TranslatorManager::trans('light',[],'main')]);
        $info->fieldAsSubmit('submitLang',null, Router::path('langSubmit'),'#response',[
            'value'=>TranslatorManager::trans('submitLang',[],'main')
        ]);
	}
}