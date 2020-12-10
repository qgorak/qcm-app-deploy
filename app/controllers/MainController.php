<?php
namespace controllers;
use Ubiquity\security\acl\controllers\AclControllerTrait;

/**
 * @route('_default',"automated"=>"true","inherited"=>"true")
 * @property \Ajax\php\ubiquity\JsUtils $jquery
 * Controller MainController
 *
 */
class MainController extends ControllerBase{
    use AclControllerTrait;
    
    /**
     * @allow('role'=>'@GUEST')
     */
    public function index(){
        $this->loadDefaultView();
    }

}