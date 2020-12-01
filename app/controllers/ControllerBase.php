<?php
namespace controllers;

use Ubiquity\controllers\Controller;
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
		    $this->loadView('/main/UI/Navbar.html');
		    $this->loadView('/main/UI/AuthModal.html');
		}
	}

	public function finalize() {
		if (! URequest::isAjax ()) {
			$this->loadView ( $this->footerView );
		}
	}
}

