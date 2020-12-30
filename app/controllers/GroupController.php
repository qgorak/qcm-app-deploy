<?php
namespace controllers;

use Ubiquity\controllers\Router;
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
            $this->jquery->getHref ( '.trainermenu', '#response', [
                'hasLoader' => 'internal'
            ] );
            $this->jquery->attr('#trainermode','class','item active',true);
        }
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
        $this->uiService->displayMyGroups($myGroups);
        $acc = $this->uiService->groupAccordion($myGroups);
        foreach($myGroups as $group){
            $group->getName();
            $users=$this->loader->getUsers($group->getId());
            $content = $this->uiService->viewGroup($users,$group->getId());
            $grid=$this->uiService->groupTitleGrid($group);
            $acc->addItem(array($grid,$content));
        }
    }
    
    /**
     * @route('/','name'=>'group')
     */
    public function index(){
        $this->_index();
        $this->jquery->renderView('GroupController/index.html');
    }
    
    private function _index(){
        $this->displayMyGroups();
        $this->jquery->_add_event('#addGroup','$("#addModal").modal("show");','click');
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
        $this->loader->add($group);
        $this->_index();
        $this->jquery->renderView('GroupController/display.html');
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
                $this->jquery->semantic()->toast('body',['message'=>TranslatorManager::trans('joinSucceed',[],'main'),'class'=> 'success','position'=>'center top']);
            }
            else{
                $this->jquery->semantic()->toast('body',['message'=>TranslatorManager::trans('joinWarning',[],'main'),'position'=>'center top','class'=>'error']);
            }
        }
        else{
            $this->jquery->semantic()->toast('body',['message'=>TranslatorManager::trans('joinFailed',[],'main'),'position'=>'center top','class'=>'error']);
        }
        $this->uiService->joinForm();
        $this->jquery->renderView('GroupController/join.html');
    }

    /**
     * @get("joinform","name"=>"joinForm")
     */
    public function joinForm(){
        $this->uiService->joinForm();
        $this->jquery->renderView('GroupController/join.html');
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
        $this->_index();
        $this->jquery->renderView('GroupController/display.html');
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
        $this->jquery->ajax('get',Router::path('group'),'#response');
        $this->jquery->renderView('GroupController/demand.html');
    }
    
    /**
     * @post('ban','name'=>'banUser')
     */
    public function banUser(){
        $this->loader->banUser(URequest::post('group'), URequest::post('user'));
        $this->index();
    }
}