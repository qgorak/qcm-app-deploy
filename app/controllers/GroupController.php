<?php
namespace controllers;

use Ajax\semantic\html\collections\HtmlMessage;
use Ubiquity\controllers\Router;
use Ubiquity\utils\http\URequest;
use models\Group;
use models\Question;
use services\GroupDAOLoader;

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
        $this->jquery->exec('var userInGroup=[];',true);
        $groupForm=$this->jquery->semantic()->dataForm('groupForm', Group::class);
        $groupForm->setFields([
            "name",
            "description",
            "usergroups",
            "addUserToGroup",
            "userInGroup"
        ]);
        $groupForm->fieldAsHidden("userInGroup");
        $groupForm->fieldAsButton("addUserToGroup","",["value"=>"Add user to group"]);
        $groupForm->addSubmit('groupFormSubmit','Add group');
        $this->jquery->getOnClick('#groupForm-addUserToGroup-0',Router::path("user.exist",["'+document.getElementById('groupForm-usergroups').value+'"]),null,[
            'jsCondition'=>'document.getElementById("groupForm-usergroups").value!=""',
            'jsCallback'=>'if(!userInGroup.includes(data) && data!="false"){
            var table = document.getElementById("usersInGroup").getElementsByTagName("tbody")[0];
            var row = table.insertRow(0);
            var cell1 = row.insertCell(0);
            cell1.innerHTML = data;
            userInGroup.push(data);
            document.getElementById("groupForm-userInGroup-0").value = JSON.stringify(userInGroup);}'
        ]);
        $this->jquery->postFormOnClick('#groupFormSubmit',Router::path('submit'), 'groupForm','body');
        $this->jquery->renderView ( 'GroupController/add.html', []) ;
        
    }
    
    
    /**
     * @post("add","name"=>"submit")
     */
    public function submit(){
        $group = new Group();
        $group->setName(URequest::post ( 'name', 'no name' ));
        $group->setDescription(URequest::post ( 'description', 'no desc' ));
        $this->loader->add($group);
        var_dump(URequest::getDatas());
    }
}

