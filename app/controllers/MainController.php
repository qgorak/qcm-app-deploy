<?php
namespace controllers;
use Ubiquity\security\acl\controllers\AclControllerTrait;

/**
 * @allow('role'=>['@GUEST','@USER'])
 * @route('_default',"automated"=>"true","inherited"=>"true")
 * @property \Ajax\php\ubiquity\JsUtils $jquery
 * Controller MainController
 *
 */
class MainController extends ControllerBase{
    use AclControllerTrait;
    
    public function index(){
        $this->loadDefaultView();
    }

}