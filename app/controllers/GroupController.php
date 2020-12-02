<?php
namespace controllers;

use Ubiquity\controllers\Router;
use models\Group;
use Ubiquity\orm\DAO;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\http\USession;
use models\User;

/**
 * Controller GroupController
 * @route('group','inherited'=>true,'automated'=>true)
 * @property \Ajax\php\ubiquity\JsUtils $jquery
 */
class GroupController extends ControllerBase{
    
    /**
     * 
     * @route("/","name"=>"groupe")
     */
    public function index(){
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
        $this->jquery->getOnClick('#groupForm-addUserToGroup-0',Router::path('user.exist',["'+document.getElementById('groupForm-usergroups').value+'"]),null,[
            'jsCondition'=>'function(){if(document.getElementById("groupForm-usergroups").value!==null){return true;}}',
            'jsCallback'=>'if(!userInGroup.includes(data)){
            var table = document.getElementById("usersInGroup").getElementsByTagName("tbody")[0];
            var row = table.insertRow(0);
            var cell1 = row.insertCell(0);
            cell1.innerHTML = data;
            userInGroup.push(data);
            document.getElementById("groupForm-userInGroup-0").value = JSON.stringify(userInGroup);}'
        ]);
        $this->jquery->postFormOnClick('#groupFormSubmit',Router::path('add'), 'groupForm','body');
        $this->jquery->renderDefaultView();
    }
    
    /**
     * @post("add","name"=>"add")
     */
    public function addGroup(){
        var_dump(URequest::getDatas());
    }
}

