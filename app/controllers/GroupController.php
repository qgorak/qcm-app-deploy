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
        $groupForm=$this->jquery->semantic()->dataForm('groupForm', Group::class);
        $groupForm->setFields([
            "name",
            "description",
            "usergroups"
        ]);
        $groupForm->addSubmit('groupFormSubmit','Add group');
        $this->jquery->postFormOnClick('#groupFormSubmit',Router::path('add'), 'groupForm','body');
        $this->jquery->renderDefaultView();
    }
    
    /**
     * @post("add","name"=>"add")
     */
    public function addGroup(){
        $group=new Group();
        URequest::setPostValuesToObject($group);       
        $user=DAO::getOne(User::class,"id=?",true,[USession::get('activeUser')['id']]);
        $group->setUser($user);
        DAO::insert($group,true);
    }
}

