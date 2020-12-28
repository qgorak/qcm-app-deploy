<?php

namespace services\UI;

use Ajax\php\ubiquity\JsUtils;
use Ubiquity\controllers\Router;
use models\User;
use Ubiquity\translation\TranslatorManager;

class AuthUIService {
	protected $jquery;
	protected $semantic;
	public function __construct(JsUtils $jq) {
		$this->jquery = $jq;
		$this->semantic = $jq->semantic ();
	}
	
	public function loginForm(){
	    $u = new User();
	    $frm = $this->jquery->semantic ()->dataForm ( 'loginForm', $u);
	    $frm->setFields ( [
	        'email',
	        'password',
	        'submit'
	    ] );
	    $frm->setCaptions([
	        TranslatorManager::trans('login',[],'main'),
	        TranslatorManager::trans('password',[],'main')
	    ]);
	    $frm->fieldAsInput ( 'email', [
	        'rules' => [
	            'empty',
	            'length[5]'
	        ]]);
	    $frm->fieldAs( 'password','password', [
	        'rules' => [
	            'empty',
	            'length[5]'
	        ]]);
	    $frm->setValidationParams ( [
	        "on" => "blur",
	        "inline" => true
	    ] );
	    $frm->fieldAsSubmit ( 'submit', 'green', Router::path('loginPost'), '#responseauth', [
	        'ajax' => [
	            'hasLoader' => 'internal',
	            'params'=>'$("#loginForm").serialize()',
	            'jsCallback'=>'if(data=="logged"){location.reload();$("#authmodal").modal("hide");}'
	        ]
	    ] );
	    return $frm;
	}
	
	public function resetPasswordForm(){
	    $form=$this->jquery->semantic()->dataForm('resetForm', new User());
	    $form->setFields([
	        'email',
	        'submit'
	    ]);
	    $form->setCaptions([
	        'Saisir votre email'
	    ]);
	    $form->fieldAsInput('email');
	    $form->fieldAsSubmit('submit',null,Router::path('resetPassword'),'.header');
	}
	
	public function registerForm(){
	    $u = new User();
	    $u->setLanguage('en_EN');
	    $frm = $this->jquery->semantic ()->dataForm ( 'registerForm', $u);
	    $frm->setFields ([
	        'firstname',
	        'lastname',
	        'email',
	        'password',
	        'language',
	        'submit'
	        
	    ]);
	    $frm->setCaptions([
	        TranslatorManager::trans('firstname',[],'main'),
	        TranslatorManager::trans('lastname',[],'main'),
	        TranslatorManager::trans('password',[],'main'),
	        TranslatorManager::trans('email',[],'main'),
	        TranslatorManager::trans('language',[],'main')
	    ]);
	    $frm->fieldAsInput ( 'email', [
	        'rules' => [
	            'empty',
	            'length[5]'
	        ]]);
	    $frm->fieldAsInput ( 'password', [
	        'rules' => [
	            'empty',
	            'length[5]'
	        ]]);
	    $frm->setValidationParams ( [
	        "on" => "blur",
	        "inline" => true
	    ] );
	    $frm->fieldAsDropDown('language',['en_EN'=>'English','fr_FR'=>'Français'],false, [
	    		'rules' => [
	    				'empty'
	    		]]);	   
	    $frm->fieldAsSubmit ( 'submit', 'green', Router::path('registerPost'), '#responseauth', [
	        'ajax' => [
	            'hasLoader' => 'internal',
	            'params'=>'$("#registerForm").serialize()'
	        ]]);
	    return $frm;
	}
	
	public function loginErrorMessage($message){
	    $msg = $this->jquery->semantic()->htmlMessage('msglogin',$message);
	    $msg->setIcon('check');
	    return $msg;
	}
}