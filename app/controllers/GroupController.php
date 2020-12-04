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
        $groups = $this->loader->myGroups();
        $dt = $this->jquery->semantic ()->dataTable ( 'dtItems', Group::class, $groups );
        $msg = new HtmlMessage ( '', "Aucun élément à afficher !" );
        $msg->addIcon ( "x" );
        $dt->setEmptyMessage ( $msg );
        $dt->setFields ( [
            'id',
            'name',
            'description'
        ] );
        $dt->setIdentifierFunction ( 'getId' );
        $dt->addDeleteButton( false );
        
        $dt->setEdition ();
        $this->jquery->getOnClick ( '._delete', Router::path ('groupDelete',""), 'body', [
            'hasLoader' => 'internal',
            'attr' => 'data-ajax'
        ] );
        
        $this->jquery->getOnClick ( '._edit', Router::path ('groupEdit'), '#response', [
            'hasLoader' => 'internal',
            'attr' => 'data-ajax'
        ] );
    }
    
    /**
     * 
     * @route('/','name'=>'group')
     */
    public function index(){
        $this->_index();
    }
    
    private function _index($response = '') {
        $this->jquery->getHref('.container a','',[
            'hasloader'=>'internal'
        ]);
        $this->displayMyGroups();
        $this->jquery->renderView ( 'Groupcontroller/index.html', [
            'response' => $response
        ] );
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
        $groupForm->fieldAsSubmit('submit',null,Router::path('GroupAddSubmit'),'body',[
        	'value'=>'Add the group'
        ]);
        if (URequest::isAjax ()) {
            $this->jquery->renderView ( 'GroupController/add.html' );
        } else {
            $this->_index ( $this->jquery->renderView ( 'GroupController/add.html', [ ], true ) );
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
        $this->jquery->exec("window.location.href='/group'",true);
        $this->jquery->renderView( 'GroupController/index.html') ;
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
		$groupForm->fieldAsSubmit('submit',null,Router::path('joinSubmit'),'body',[
			'value'=>'Join the group'
		]);
		if (URequest::isAjax ()) {
		    $this->jquery->renderView ( 'GroupController/join.html' );
		} else {
		    $this->_index ( $this->jquery->renderView ( 'GroupController/join.html', [ ], true ) );
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
    	$this->jquery->exec("window.location.href='/group'",true);
    	$this->jquery->renderView( 'GroupController/index.html') ;
    }
    
    /**
     * @get('delete/{id}','name'=>'groupDelete')
     * @param string $id
     */
    public function groupDelete(string $id){
        $this->loader->remove ( $id );
        $msg = $this->jquery->semantic ()->htmlMessage ( '', 'Item supprimé' );
        $this->jquery->exec("window.location.href='/group'",true);
        $this->jquery->renderView( 'GroupController/index.html') ;    
    }
}