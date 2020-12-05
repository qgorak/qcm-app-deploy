<?php
namespace controllers;

use Ajax\semantic\html\collections\HtmlMessage;
use Ubiquity\controllers\Router;
use Ubiquity\orm\DAO;
use Ubiquity\utils\http\URequest;
use models\Group;
use services\GroupDAOLoader;
use models\User;
use Ubiquity\utils\http\USession;
use models\Usergroup;
use Ubiquity\controllers\Startup;
use Ubiquity\translation\TranslatorManager;
use Ajax\semantic\html\elements\HtmlButton;

/**
 * Controller GroupController
 * @route('group','inherited'=>true,'automated'=>true)
 * @property \Ajax\php\ubiquity\JsUtils $jquery
 */
class GroupController extends ControllerBase{
     
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
        $groups = $this->loader->myGroups();//seulement les groupes qui m'appartiennent
        $inGroups=$this->loader->inGroups();//seulement les groupes ou je suis membre
        $dtMyGroups = $this->jquery->semantic ()->dataTable ( 'myGroups', Group::class, $groups );
        $dtMyGroups->setFields ( [
            'id',
            'name',
            'description'
        ] );
        $dtMyGroups->setIdentifierFunction ( 'getId' );
        $dtMyGroups->addAllButtons(false);
        $dtMyGroups->setEdition ();
        $this->jquery->getOnClick('._display', Router::path ('groupView',[""]),'#response',[
            'hasLoader'=>'internal',
            'attr'=>'data-ajax'
        ]);
        $this->jquery->getOnClick ( '._delete', Router::path ('groupDelete',[""]), '#response', [
            'hasLoader' => 'internal',
            'attr' => 'data-ajax'
        ] );       
        $this->jquery->getOnClick ( '._edit', Router::path ('groupDemand',[""]), '#response', [
            'hasLoader' => 'internal',
            'attr' => 'data-ajax'
        ] );
        $dtInGroups = $this->jquery->semantic ()->dataTable ( 'inGroups', Group::class, $inGroups );
        $dtInGroups->setFields ( [
            'id',
            'name',
            'description'
        ] );
        $dtInGroups->setIdentifierFunction ( 'getId' );
        $dtInGroups->setEdition ();
    }
    
    /**
     *
     * @route('/','name'=>'group')
     */
    public function index(){
        $this->jquery->ajaxOnClick('#addGroup',Router::path ('groupAdd',[""]),'#response',[
            'hasloader'=>'internal'
        ]);
        $this->jquery->ajaxOnClick('#joinGroup',Router::path ('groupJoin',[""]),'#response',[
            'hasloader'=>'internal'
        ]);
        $this->displayMyGroups();
        $this->_index($this->jquery->renderView('GroupController/display.html',[],true));
    }
    
    private function _index($response = '') {
        $this->jquery->renderView ( 'Groupcontroller/index.html', [
            'response' => $response
        ] );
    }
    
    /**
     * @get('{id}','name'=>'groupView')
     * @param mixed $id
     */
    public function viewGroup($id){
        $users=$this->loader->getUsers($id);
        $usersDt=$this->jquery->semantic()->dataTable('dtUsers',User::class,$users);
        $usersDt->setFields([
            'firstname',
            'lastname',
            'email'
        ]);
        $usersDt->setCaptions([
            'firstname',
            'lastname',
            'email'
        ]);
        $usersDt->setIdentifierFunction ( 'getId' );
        if(URequest::isAjax()){
            $this->jquery->renderView('GroupController/view.html');
        }
        else{
            $this->_index($this->jquery->renderView('GroupController/view.html',[],true));
        }
    }
    
    /**
     * @get("add","name"=>"groupAdd")
     */
    public function addGroup(){
        $groupForm=$this->jquery->semantic()->dataForm('groupForm', Group::class);
        $groupForm->setFields([
            "name",
            "description",
            "submit"
        ]);
        $groupForm->setCaptions([
            'Name',
            'Description'
        ]);
        $groupForm->fieldAsInput('name',[
            'rules'=>'empty'
        ]);
        $groupForm->fieldAsTextarea('description',[
            'rules'=>'empty'
        ]);
        $groupForm->fieldAsSubmit('submit',null,Router::path('GroupAddSubmit'),'#response',[
            'value'=>TranslatorManager::trans('addSubmit',[],'main')
        ]);
        $this->displayMyGroups();
        if(URequest::isAjax()){
            $this->jquery->renderView('GroupController/add.html');
        }
        else{
            $this->_index($this->jquery->renderView('GroupController/add.html',[],true));
        }
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
        $this->jquery->renderView('GroupController/display.html');
    }
    
    /**
     * @get("join","name"=>"groupJoin")
     */
    public function joinGroup(){
		$groupForm=$this->jquery->semantic()->dataForm('groupForm',Usergroup::class);
		$groupForm->setFields([
			'GroupKey',
			'submit'
		]);
		$groupForm->setCaptions([
			'Key of the group'
		]);
		$groupForm->fieldAsInput('GroupKey',[
			'rules'=>'empty'
		]);
		$groupForm->fieldAsSubmit('submit',null,Router::path('joinSubmit'),'#response',[
		    'value'=>TranslatorManager::trans('joinSubmit',[],'main')
		]);
		$this->displayMyGroups();
		if(URequest::isAjax()){
		    $this->jquery->renderView('GroupController/join.html');
		}
		else{
		    $this->_index($this->jquery->renderView('GroupController/join.html',[],true));
		}
    }
    
    /**
     * @post("join","name"=>"joinSubmit")
     */
    public function joinSubmit(){
    	$user=DAO::getById(User::class,USession::get('activeUser')['id'],['usergroups']);   	
    	$group=$this->loader->getByKey(URequest::post('GroupKey')); 
    	if($group!=null){
    	    if(!($user->isInGroup($group->getId()) || $user->isCreator($group->getId()))){
    	        $userGroup=new Usergroup();
    	        $userGroup->setIdGroup($group->getId());
    	        $userGroup->setIdUser($user->getId());
    	        $userGroup->setStatus(0);
    	        DAO::save($userGroup);
    	    }
    	}
    	$this->displayMyGroups();
    	$this->jquery->renderView('GroupController/display.html');
    }
    
    /**
     * @get('delete/{id}','name'=>'groupDelete')
     * @param string $id
     */
    public function groupDelete(string $id){
        $this->loader->remove ( $id );
    }
    
    private function demand($id){
        $users=$this->loader->getJoiningDemand($id);
        $usersDt=$this->jquery->semantic()->dataTable('usersDemand',User::class,$users);
        $usersDt->setFields([
            'firstname',
            'lastname',
            'email'
        ]);
        $usersDt->setCaptions([
            'firstname',
            'lastname',
            'email'
        ]);
        $usersDt->setIdentifierFunction ( 'getId' );
        $usersDt->addEditDeleteButtons(false);
        $this->jquery->ajaxOnClick('._edit',Router::path('groupDemandAccept',['true',URequest::getUrlParts()[2]]),'#response',[
            'attr'=>'data-ajax'
        ]);
        $this->jquery->ajaxOnClick('._delete',Router::path('groupDemandAccept',['false',URequest::getUrlParts()[2]]),'#response',[
            'attr'=>'data-ajax'
        ]);
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
     * @get('demand/{bool}/{groupId}/{userId}','name'=>'groupDemandAccept')
     * @param mixed $userId
     * @param mixed $groupId
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
}