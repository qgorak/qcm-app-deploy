<?php
namespace controllers;

use Ubiquity\orm\DAO;
use Ubiquity\utils\http\URequest;
use models\Group;
use services\DAO\GroupDAOLoader;
use models\User;
use Ubiquity\utils\http\USession;
use models\Usergroup;
use Ubiquity\translation\TranslatorManager;
use Ubiquity\security\acl\controllers\AclControllerTrait;
use services\UI\GroupUIService;

/**
 * Controller GroupController
 * @allow('role'=>'@USER')
 * @route('group','inherited'=>true,'automated'=>true)
 * @property \Ajax\php\ubiquity\JsUtils $jquery
 */
class GroupController extends ControllerBase{
    use AclControllerTrait;
     
    /**
     *
     * @autowired
     * @var GroupDAOLoader
     */
    private $loader;
    private $uiService;
    
    public function initialize(){
        parent::initialize();
        $this->uiService = new GroupUIService( $this->jquery );
        if (! URequest::isAjax ()) {
            $this->loadView('/main/UI/trainerNavbar.html');
            $this->jquery->getHref ( 'a', '#response', [
                'hasLoader' => 'internal'
            ] );
        }
        $this->jquery->attr('#trainermode','class','item active',true);
    }
    /**
     *
     * @param \services\DAO\GroupDAOLoader $loader
     */
    public function setLoader($loader) {
        $this->loader = $loader;
    }
    
    private function displayMyGroups() {
        $myGroups = $this->loader->myGroups();
        $inGroups=$this->loader->inGroups();
        $waitGroups=$this->loader->waitGroups();
        $this->uiService->displayMyGroups($myGroups, $inGroups,$waitGroups);
    }
    
    /**
     * @route('/','name'=>'group')
     */
    public function index(){
        $this->displayMyGroups();
        $this->jquery->renderView('GroupController/index.html');
    }
    

    private function _viewGroup($id){
        $users=$this->loader->getUsers($id);
        $this->uiService->viewGroup($users, $id);
    }
    
    /**
     * @post("add","name"=>"GroupAddSubmit")
     */
    public function addSubmit(){
        $group = new Group();
        URequest::setPostValuesToObject($group);
        $group->setKeyCode(\uniqid());
        $user=DAO::getOne(User::class,"id=?",true,[USession::get('activeUser')['id']]);
        $group->setUser($user);
        $newGroup=$this->loader->add($group);
        $this->displayMyGroups();
        $msg=$this->jquery->semantic()->htmlMessage('msg',TranslatorManager::trans('createGroupSucceed',[],'main'),['success']);
        $msg->setTimeout(3000);
        echo $newGroup;
    }
    
    /**
     * @post("join","name"=>"joinSubmit")
     */
    public function joinSubmit(){
        $user=USession::get('activeUser')['id'];
        $group=$this->loader->getByKey(URequest::post('GroupKey'));
        if($group!=null){
            if(!($this->loader->isInGroup($group->getId(), $user) || $this->loader->isCreator($group->getId(), $user) || $this->loader->alreadyDemand($group->getId(), $user))){
                $userGroup=new Usergroup();
                $userGroup->setIdGroup($group->getId());
                $userGroup->setIdUser($user);
                $userGroup->setStatus(0);
                DAO::save($userGroup);
                $msg=$this->jquery->semantic()->htmlMessage('msg',TranslatorManager::trans('joinSucceed',[],'main'),['success']);
            }
            else{
                $msg=$this->jquery->semantic()->htmlMessage('msg',TranslatorManager::trans('joinWarning',[],'main'),['warning']);
            }
        }
        else{
            $msg=$this->jquery->semantic()->htmlMessage('msg',TranslatorManager::trans('joinFailed',[],'main'),['error']);
        }
        $msg->setTimeout(3000); 
        $this->displayMyGroups();
        $this->jquery->renderView('GroupController/display.html');
    }
    
    /**
     * @get('view/{id}','name'=>'groupView')
     * @param mixed $id
     */
    public function viewGroup($id){
        $this->_viewGroup($id);
        if(URequest::isAjax()){
            $this->jquery->getHref ( '#cancel', '#response', [
                'hasLoader' => 'internal'
            ] );
            $this->jquery->renderView('GroupController/view.html');
        }
    }
    
    /**
     * @get('delete/{id}','name'=>'groupDelete')
     * @param string $id
     */
    public function groupDelete(string $id){
        $this->loader->remove ( $id );
    }
    
    private function demand($groupId){
        $users=$this->loader->getJoiningDemand($groupId);
        $this->uiService->groupJoinDemand($users,$groupId);
    }
    
    /**
     * @get('demand/{id}','name'=>'groupDemand')
     * @param mixed $id
     */
    public function getUserDemand($id){
        if(URequest::isAjax()){
            if($this->loader->isCreator($id,USession::get('activeUser')['id'])){
                $this->demand($id);
                $this->jquery->getHref ( '#cancel', '#response', [
                    'hasLoader' => 'internal'
                ] );
                $this->jquery->renderView('GroupController/demand.html');
            }
            else{
                //FORWARD TO 403 FORBIDDEN
            }
        }
   }
    
    /**
     * @post('valid','name'=>'groupDemandAccept')
     */
    public function acceptDemand(){
        if(URequest::post('valid')){
            $this->loader->acceptDemand(URequest::post('group'),URequest::post('user'));
        }
        else{
            $this->loader->acceptDemand(URequest::post('group'),URequest::post('user'));
        }
        $this->demand(URequest::post('group'));
        $this->jquery->renderView('GroupController/demand.html');
    }
    
    /**
     * @post('ban','name'=>'banUser')
     */
    public function banUser(){
        $this->loader->banUser(URequest::post('group'), URequest::post('user'));
        $this->_viewGroup(URequest::post('group'));
        $this->jquery->renderView('GroupController/view.html');
    }
}