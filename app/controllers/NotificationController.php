<?php
namespace controllers;

use Ubiquity\controllers\Router;
use models\User;
use services\NotificationDAOLoader;

/**
 * Controller NotificationController
 * @route('notification','inherited'=>true,'automated'=>true)
 * @property \Ajax\php\ubiquity\JsUtils $jquery
 */
class NotificationController extends ControllerBase{
    
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
    
    private function displayMyNotification(){
        $exam=$this->loader->getExamNotification();
        $groupDemand=$this->loader->getGroupNotification();
        $notifJson=[];
        foreach($exam as $value){
            array_push($notifJson,[$value,Router::path('')]);
        }
        foreach($groupDemand as $value){
            array_push($notifJson,[$value,Router::path('groupDemand',[$value])]);
        }
        $this->jquery->semantic()->htmlList('notifications',$notifJson);
    }
    
    private function _index($response = '') {
        $this->jquery->renderView ( 'NotificationController/index.html', [
            'response' => $response
        ] );
    }
    
    public function index(){
        $exam=$this->loader->getExamNotification();
        $groupDemand=$this->loader->getGroupNotification();
        $notifJson=[];
        foreach($exam as $value){
            array_push($notifJson,['id'=>$value,'link'=>Router::path('')]);
        }
        foreach($groupDemand as $value){
            array_push($notifJson,['id'=>$value,'link'=>Router::path('groupDemand',[$value])]);
        }
        $notification=$this->jquery->semantic()->htmlItems('notifications',$notifJson);
        $notification->compile($this->jquery);
        $this->_index();
    }

}

