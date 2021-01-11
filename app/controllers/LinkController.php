<?php
namespace controllers;

use Ubiquity\controllers\Router;
use Ubiquity\orm\DAO;
use Ubiquity\security\acl\controllers\AclControllerTrait;
use Ubiquity\translation\TranslatorManager;
use Ubiquity\utils\http\USession;
use services\DAO\GroupDAOLoader;
use models\Usergroup;

/**
 * Controller LinkiController
 */
class LinkController extends ControllerBase{
    use AclControllerTrait;
    
    /**
     *
     * @autowired
     * @var GroupDAOLoader
     */
    private $loader;
    
    /**
     *
     * @param \services\DAO\GroupDAOLoader $loader
     */
    public function setLoader($loader) {
        $this->loader = $loader;
    }
    
    public function index(){}
    
    /**
     * @allow('role'=>['@GUEST','@USER'])
     * @get('invitation/{key}','name'=>'joinLink')
     * @param string $key
     */
    public function joinByLink(string $key){
        $user=USession::get('activeUser');
        $group=$this->loader->getByKey($key);
        if($group!=null){
            if($user!=null){
                if(!($this->loader->isInGroup($group->getId(), $user['id']) || $this->loader->isCreator($group->getId(), $user['id']) || $this->loader->alreadyDemand($group->getId(), $user['id']))){
                    $userGroup=new Usergroup();
                    $userGroup->setIdGroup($group->getId());
                    $userGroup->setIdUser($user['id']);
                    $userGroup->setStatus(0);
                    DAO::save($userGroup);
                    $this->jquery->semantic()->toast('body',['message'=>TranslatorManager::trans('joinSucceed',[],'main'),'class'=> 'success','position'=>'center top']);
                }
                else{
                    $this->jquery->semantic()->toast('body',['message'=>TranslatorManager::trans('alreadyInGroup',[],'main'),'class'=> 'success','position'=>'center top']);
                }
            }
            else{
                USession::set('redirect',Router::path('joinLink',[$key]));
                $this->jquery->semantic()->toast('body',['message'=>TranslatorManager::trans('notConnected',[],'main'),'class'=> 'success','position'=>'center top']);
            }
        }
        else{
            $this->jquery->semantic()->toast('body',['message'=>TranslatorManager::trans('notGroup',[],'main'),'class'=> 'warning','position'=>'center top']);
        }
        $this->jquery->renderView('MainController/index.html');
    }
}