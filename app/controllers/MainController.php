<?php
namespace controllers;
use Ubiquity\security\acl\controllers\AclControllerTrait;
use Ubiquity\utils\http\USession;

/**
 * @allow('role'=>['@GUEST','@USER'])
 * @property \Ajax\php\ubiquity\JsUtils $jquery
 * Controller MainController
 *
 */
class MainController extends ControllerBase{
    use AclControllerTrait;
    
    /**
     * 
     * @route('_default')
     */
    public function index(){
        $this->loadDefaultView();
    } 
    
    /**
     * @route('change/{lang}','name'=>'changeLanguage')
     * @param mixed $lang
     */
    public function changeLanguage($lang){
        if($lang=='en_EN'){
            USession::set('language','en_EN');
        }
        elseif($lang=='fr_FR'){
            USession::set('language','fr_FR');
        }
        header('location:/');
    }

}