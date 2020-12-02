<?php
namespace controllers;

use Ajax\semantic\html\collections\HtmlMessage;
use Ubiquity\controllers\Router;
use Ubiquity\orm\DAO;
use Ubiquity\utils\http\URequest;
use models\Group;
use models\Question;
use services\GroupDAOLoader;
use models\User;
use Ubiquity\utils\http\USession;
use models\Usergroup;

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
        $groups = $this->loader-> my();
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
    public function index(){
        $this->_index();
    }
    
    private function _index($response = '') {
        $this->displayMyGroups();
        $this->jquery->renderView ( 'Groupcontroller/index.html', [
            'response' => $response
        ] );
    }
    /**
     * @get("add","name"=>"add")
     */
    public function addGroup(){
        $groupForm=$this->jquery->semantic()->dataForm('groupForm', Group::class);
        $groupForm->setFields([
            "name",
            "description"
        ]);
        $groupForm->addSubmit('groupFormSubmit','Add group');
        $this->jquery->postFormOnClick('#groupFormSubmit',Router::path('submit'), 'groupForm','body');
        $this->jquery->renderView ( 'GroupController/add.html', []) ;
        
    }
    
    
    /**
     * @post("add","name"=>"submit")
     */
    public function addSubmit(){
        $group = new Group();
        $group->setName(URequest::post ( 'name', 'no name' ));
        $group->setDescription(URequest::post ( 'description', 'no desc' ));
        $group->setKey(uniqid());
        $user=DAO::getOne(User::class,"id=?",true,[USession::get('activeUser')['id']]);
        $group->setUser($user);
        $this->loader->add($group);
        var_dump(URequest::getDatas());
    }
    
    /**
     * @get("join","name"=>"join")
     */
    public function joinGroup(){
        $groupForm=$this->jquery->semantic()->htmlForm('groupForm',[
            'GroupKey'
        ]);
        $groupForm->addSubmit('groupFormSubmit','Join group');
        $this->jquery->postFormOnClick('#groupFormSubmit',Router::path('joinSubmit'), 'groupForm','body');
        $this->jquery->renderView ( 'GroupController/join.html') ;
    }
    
    /**
     * @post("join","name"=>"joinSubmit")
     */
    public function joinSubmit(){
        $userGroup=new Usergroup();
        $user=DAO::getById(User::class,USession::get('activeUser')['id']);
        $group=DAO::getOne(Group::class,'`key`=?',true,[URequest::post('GroupKey')]);
        $userGroup->setIdUser($user->getId());
        $userGroup->setIdGroup($group->getId());
        $userGroup->setStatus(0);
        DAO::insert($userGroup);
    }
}

