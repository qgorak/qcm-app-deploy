<?php
namespace controllers;

use Ubiquity\controllers\Controller;
use Ubiquity\translation\TranslatorManager;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\http\USession;

/**
 * ControllerBase.
 **/
abstract class ControllerBase extends Controller{
	protected $headerView = "@activeTheme/main/vHeader.html";
	protected $footerView = "@activeTheme/main/vFooter.html";

	public function initialize() {
		if (! URequest::isAjax ()) {
		    $user = USession::get('activeUser');
		    $this->loadView ( $this->headerView ,[
		        'user' => $user
		    ] );
		    if(USession::get('activeUser',false)){
		    	USession::delete('language');
		    	TranslatorManager::setLocale(USession::get('activeUser')['language']);
		        $this->loadView('/main/UI/userNavbar.html');
		    }
		    else{
		    	if(!USession::get('language',false)){
		    		USession::set('language','en_EN');
		    		$language='en_EN';
		    	}
		    	$language=USession::get('language');
		    	TranslatorManager::setLocale(USession::get('language'));
		        $this->loadView('/main/UI/Navbar.html',['lang'=>$language]);
		    }
		    $this->loadView('/main/UI/AuthModal.html');
		}
	}

	public function finalize() {
		if (! URequest::isAjax ()) {
            $this->loadView('/main/UI/Footer.html');
			$this->loadView ( $this->footerView );
		}
	}
	
	public function _getRole(){
		if(isset(USession::get('activeUser')['id'])){
			return '@USER';
		}
		return '@GUEST';
	}
	
	public function onInvalidControl() {
	    \header('location:/');
	}
}