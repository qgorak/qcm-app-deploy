<?php
namespace controllers;
 /**
 * @route('_default',"automated"=>"true","inherited"=>"true")
 * Controller MainController
 */
class MainController extends ControllerBase{

	public function index(){
		$this->loadView("MainController/index.html");
	}
}
