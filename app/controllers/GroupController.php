<?php
namespace controllers;

use Ubiquity\controllers\Router;
use models\Group;
use Ubiquity\orm\DAO;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\http\USession;

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
            "userGroup"
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
        $group->setUser('ok');
        //USession::get('activeUser')['id']
        var_dump($group);
        DAO::insert($group,true);
    }
}

