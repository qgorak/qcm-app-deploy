<?php
namespace controllers;

use Ubiquity\controllers\Router;
use Ubiquity\orm\DAO;
use Ubiquity\utils\http\URequest;
use models\Group;
use services\GroupDAOLoader;
use models\User;
use Ubiquity\utils\http\USession;
use models\Usergroup;
use Ubiquity\translation\TranslatorManager;
use services\UIService;
use Ubiquity\security\acl\controllers\AclControllerTrait;

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
    
    /**
     *
     * @param \services\GroupDAOLoader $loader
     */
    public function setLoader($loader) {
        $this->loader = $loader;
    }
    
    private function displayMyGroups() {
        $myGroups = $this->loader->myGroups();
        $inGroups=$this->loader->inGroups();
        $dt=new UIService($this->jquery);
        $this->jquery->execAtLast("$('#addGroup').click(function() {
        	$('#addModal').modal('show');
        });
        $('#addForm-submit-0').click(function() {
        	$('#addModal').modal('hide');
        });
        $('#joinGroup').click(function() {
        	$('#joinModal').modal('show');
        });
        $('#joinForm-submit-0').click(function() {
        	$('#joinModal').modal('hide');
        });
            $('.ui.accordion').accordion();");
        $dt->displayMyGroups($myGroups, $inGroups);
    }
    
    /**
     * @route('/','name'=>'group')
     */
    public function index(){
        $groupForm=$this->jquery->semantic()->dataForm('addForm', Group::class);
        $groupForm->setFields([
            "name",
            "description",
            "submit"
        ]);
        $groupForm->setCaptions([
            TranslatorManager::trans('name',[],'main'),
            TranslatorManager::trans('description',[],'main')
        ]);
        $groupForm->fieldAsInput('name',[
            'rules'=>'empty'
        ]);
        $groupForm->fieldAsTextarea('description',[
            'rules'=>'empty'
        ]);
        $groupForm->fieldAsSubmit('submit','green',Router::path('GroupAddSubmit'),'#response',[
            'value'=>TranslatorManager::trans('addSubmit',[],'main')
        ]);
        $joinForm=$this->jquery->semantic()->dataForm('joinForm',Usergroup::class);
        $joinForm->setFields([
            'GroupKey',
            'submit'
        ]);
        $joinForm->setCaptions([
            TranslatorManager::trans('groupKey',[],'main')
        ]);
        $joinForm->fieldAsInput('GroupKey',[
            'rules'=>'empty'
        ]);
        $joinForm->fieldAsSubmit('submit','green',Router::path('joinSubmit'),'#response',[
            'value'=>TranslatorManager::trans('joinSubmit',[],'main')
        ]);
        $this->displayMyGroups();
        $this->jquery->renderView('GroupController/index.html');
    }
    
    private function _viewGroup($id){
        $users=$this->loader->getUsers($id);
        $usersDt=$this->jquery->semantic()->dataTable('dtUsers',User::class,$users);
        $usersDt->setFields([
            'firstname',
            'lastname',
            'email'
        ]);
        $usersDt->setCaptions([
            TranslatorManager::trans('firstname',[],'main'),
            TranslatorManager::trans('lastname',[],'main'),
            TranslatorManager::trans('email',[],'main')
        ]);
        $usersDt->setIdentifierFunction ( 'getId' );
        $usersDt->setProperty('group', $id);
        $usersDt->addDeleteButton(false);
        $this->jquery->postOnClick('._delete',Router::path('banUser'),'{"group":$("#dtUsers").attr("group"),"user":$(this).attr("data-ajax")}',"#response");
    }
    
    /**
     * @post("add","name"=>"GroupAddSubmit")
     */
    public function addSubmit(){
        $group = new Group();
        URequest::setPostValuesToObject($group);
        $group->setKeyCode(uniqid());
        $user=DAO::getOne(User::class,"id=?",true,[USession::get('activeUser')['id']]);
        $group->setUser($user);
        $this->loader->add($group);
        $this->displayMyGroups();
        $msg=$this->jquery->semantic()->htmlMessage('msg','Votre groupe a bien été créé',['success']);
        $msg->setTimeout(3000);
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
                $msg=$this->jquery->semantic()->htmlMessage('msg','Votre groupe a bien été créé',['success']);
            }
            else{
                $msg=$this->jquery->semantic()->htmlMessage('msg','Vous n\'êtes pas autorisé à rejoindre ce groupe',['warning']);
            }
        }
        else{
            $msg=$this->jquery->semantic()->htmlMessage('msg','Le groupe n\'existe pas',['error']);
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
            $this->jquery->renderView('GroupController/view.html');
        }
        else{
            $this->_index($this->jquery->renderView('GroupController/view.html',[],true));
        }
    }
    
    /**
     * @get('delete/{id}','name'=>'groupDelete')
     * @param string $id
     */
    public function groupDelete(string $id){
        $this->loader->remove ( $id );
        $this->displayMyGroups();
        $this->jquery->renderView('GroupController/display.html');
    }
    
    private function demand($id){
        $users=$this->loader->getJoiningDemand($id);
		$dt=new UIService($this->jquery);
		$dt->groupJoinDemand($users);
    }
    
    /**
     * @get('demand/{id}','name'=>'groupDemand')
     * @param mixed $id
     */
    public function getUserDemand($id){
        $this->demand($id);
        if(URequest::isAjax()){
            $this->jquery->renderView('GroupController/demand.html');
        }
        else{
            $this->_index($this->jquery->renderView('GroupController/demand.html',[],true));
        }
    }
    
    /**
     * @get('valid/{bool}/{groupId}/{userId}','name'=>'groupDemandAccept')
     * @param mixed $userId
     * @param mixed $groupId
     * @param mixed $bool
     */
    public function acceptDemand($bool,$groupId,$userId){
        if($bool=="true"){
            $this->loader->acceptDemand($groupId,$userId);
        }
        elseif($bool=="false"){
            $this->loader->refuseDemand($groupId,$userId);
        }
        $this->demand($groupId);
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