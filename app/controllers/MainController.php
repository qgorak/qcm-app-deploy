<?php
namespace controllers;
/**
 * @route('_default',"automated"=>"true","inherited"=>"true")
 * @property \Ajax\php\ubiquity\JsUtils $jquery
 * Controller MainController
 *
 */
class MainController extends ControllerBase{
    
    public function index(){
        $this->loadDefaultView();
    }
}