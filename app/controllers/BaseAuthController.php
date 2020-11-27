<?php
namespace controllers;
use Ubiquity\utils\http\USession;
use Ubiquity\utils\http\URequest;
use Ubiquity\orm\DAO;
use models\User;

 /**
 * Auth Controller BaseAuthController
  */
class BaseAuthController extends \Ubiquity\controllers\auth\AuthController{

	protected function onConnect($connected) {
		$urlParts=$this->getOriginalURL();
		USession::set($this->_getUserSessionKey(), $connected);
		if(isset($urlParts)){
			$this->_forward(implode("/",$urlParts));
		}else{
			//TODO
			//Forwarding to the default controller/action
		}
	}

	protected function _connect() {
		if(URequest::isPost()){
			$email=URequest::post($this->_getLoginInputName());
			$password=URequest::post($this->_getPasswordInputName());
			$user=DAO::getOne(User::class,"email = ?",true,[URequest::post('email')]);
			if($user!==null){
			    if(URequest::post('password')==$user->getPassword()){
			        return $user;
			    }
			}
		}
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Ubiquity\controllers\auth\AuthController::isValidUser()
	 */
	public function _isValidUser($action=null) {
		return USession::exists($this->_getUserSessionKey());
	}

	public function _getBaseRoute() {
		return 'BaseAuthController';
	}
}