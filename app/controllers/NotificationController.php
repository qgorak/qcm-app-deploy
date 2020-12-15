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
        $this->jquery->ajaxInterval('get',Router::path('refresh'),30000,null,'#response',[
            'hasLoader'=>false
        ]);
        $notifJson=$this->getNotifications();
        USession::set('notifications',$notifJson);
        $this->_index($this->jquery->renderView('NotificationController/display.html',['notifications'=>$notifJson],true));
    }
    
    private function _index($response){
        $this->jquery->renderView ( 'NotificationController/index.html', [
            'notif' => $response
        ] );
    }
    
    /**
     * @get('refresh','name'=>'refresh')
     */
    public function refresh(){
        $notifJson=$this->getNotifications();
        if(USession::get('notifications')!=$notifJson){
            USession::set('notifications',$this->getNotifications());
        }
        $this->jquery->renderView('NotificationController/display.html',['notifications'=>$notifJson]);
    }
    
    private function getNotifications(){
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
        return $notifJson;
    }

    /**
     * @get('json','name'=>'notification.json')
     */
    public function json(){
        $exam=$this->loader->getExamNotification();
        $groupDemand=$this->loader->getGroupNotification();
        if(count($groupDemand)==0 && count($exam)==0){
            echo "false";
        }
        else{
            echo "true";
        }
    }
}

