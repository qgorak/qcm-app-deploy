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
    }
    
    /**
     * 
     * @route('/','name'=>'group')
     */
    public function index(){
        $this->_index();
    }
    
    private function _index($response = '') {
    	$this->jquery->getHref('a','',[
    		'hasloader'=>''
    	]);
        $this->displayMyGroups();
        $this->jquery->renderView ( 'Groupcontroller/index.html', [
            'response' => $response
        ] );
    }
    
    /**
     * @get("add","name"=>"add")
     */
    public function addGroup(){
    	if(URequest::isAjax()){
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
	        $groupForm->fieldAsSubmit('submit',null,Router::path('addSubmit'),'body',[
	        	'value'=>'Add the group'
	        ]);
	        $this->jquery->renderView ( 'GroupController/add.html', []) ;
    	}
    	else{
    		Startup::forward(Router::path('group'),false,false);
    	}
    }
    
    /**
     * @post("add","name"=>"addSubmit")
     */
    public function addSubmit(){
        $group = new Group();
        URequest::setPostValuesToObject($group);
        $group->setKeyCode(uniqid());
        $user=DAO::getOne(User::class,"id=?",true,[USession::get('activeUser')['id']]);
        $group->setUser($user);
        $this->loader->add($group);
        Startup::forward(Router::path('group'));
    }
    
    /**
     * @get("join","name"=>"join")
     */
    public function joinGroup(){
    	if(Urequest::isAjax()){
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
    		$this->jquery->renderView ( 'GroupController/join.html') ;
    	}
    	else{
    		Startup::forward(Router::path('group'),false,false);
    	}
    }
    
    /**
     * @post("join","name"=>"joinSubmit")
     */
    public function joinSubmit(){
    	$user=DAO::getById(User::class,USession::get('activeUser')['id'],['usergroups']);   	
    	$group=$this->loader->getByKey(URequest::post('GroupKey')); 
    	$alreadyInGroup=(DAO::exists(Usergroup::class,"idGroup=? AND idUser=?",[$group->getId(),$user->getId()]) || DAO::exists(Group::class,"id=? AND idUser=?","idGroup=? AND idUser=?",[$group->getId(),$user->getId()]))? true : false;
    	if(!$alreadyInGroup){
    		$userGroup=new Usergroup();
    		$userGroup->setIdGroup($group->getId());
    		$userGroup->setIdUser($user->getId());
    		$userGroup->setStatus(0);
    		DAO::save($userGroup);
    	}
    	Startup::forward(Router::path('group'));
    }
}