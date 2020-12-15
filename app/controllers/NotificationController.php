<?php
namespace controllers;

use Ubiquity\controllers\Router;
use services\NotificationDAOLoader;
use Ubiquity\security\acl\controllers\AclControllerTrait;
use Ubiquity\utils\http\USession;

/**
 * Controller NotificationController
 * @allow('role'=>'@USER')
 * @route('notification','inherited'=>true,'automated'=>true)
 * @property \Ajax\php\ubiquity\JsUtils $jquery
 */
class NotificationController extends ControllerBase{
    use AclControllerTrait;
    
    /**
     *
     * @autowired
     * @var NotificationDAOLoader
     */
    private $loader;
    
    /**
     *
     * @param \services\GroupDAOLoader $loader
     */
    public function setLoader($loader) {
        $this->loader = $loader;
    }
    
    /**
     * @route('/','name'=>'notification')
     */
    public function index(){
        date_default_timezone_set('Europe/Paris');
        $exam=$this->loader->getExamNotification();
        $groupDemand=$this->loader->getGroupNotification();
        $notifJson=[];
        foreach($exam as $value){
            array_push($notifJson,['id'=>$value->getId(),'title'=>'Examen en cours','timer'=>strtotime($value->getDated())-strtotime(date("Y-m-d H:i:s"))]);
        }
        foreach($groupDemand as $value){
        	array_push($notifJson,['id'=>$value->getId(),'title'=>'Demande d\'accès au groupe','timer'=>null]);
        }
        $this->jquery->renderView('NotificationController/index.html',[
            'notifications'=>$notifJson
        ]);
    }
}

