<?php

namespace services;

use Ajax\php\ubiquity\JsUtils;
use Ajax\semantic\html\modules\HtmlDropdown;
use Ubiquity\controllers\Router;
use models\User;

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
	        'login',
	        'password'
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
	    $frm->fieldAsSubmit ( 'submit', 'green', Router::path('loginPost'), '#responseauth', [
	        'ajax' => [
	            'hasLoader' => 'internal',
	            'params'=>'$("#loginForm").serialize()',
	            'jsCallback'=>'if(data=="logged"){location.reload();$("#authmodal").modal("hide")}'
	        ]
	    ] );
	    return $frm;
	}
	
	public function registerForm(){
	    $u = new User();
	    $frm = $this->jquery->semantic ()->dataForm ( 'registerForm', $u);
	    $frm->setFields ( [
	        'firstname',
	        'lastname',
	        'email',
	        'password',
	        'language',
	        'submit'
	    ] );
	    $frm->setCaptions([
	        'firstname',
	        'lastname',
	        'email',
	        'password',
	
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
	    $dd = $this->jquery->semantic()->htmlDropdown('language','',['en_EN'=>'English','fr_FR'=>'Français']);
	    $dd->asSelect('language',false);
	    $frm->fieldAsElement('language','div',$dd,[
	        'rules' => [
	            'empty'
	        ]]);
	    $frm->fieldAsSubmit ( 'submit', 'green', Router::path('registerPost'), '#responseauth', [
	        'ajax' => [
	            'hasLoader' => 'internal',
	            'params'=>'$("#registerForm").serialize()'
	        ]
	    ] );
	    return $frm;
	}
	
	public function loginErrorMessage($message){
	    $msg = $this->jquery->semantic()->htmlMessage('msglogin',$message);
	    $msg->setIcon('x');
	    return $msg;
	}
}