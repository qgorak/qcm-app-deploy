<?php

namespace services\DAO;

use Ubiquity\orm\DAO;
use Ubiquity\utils\http\USession;
use models\Exam;
use models\Usergroup;
use models\User;
use models\Group;

class NotificationDAOLoader{
    
    public function getGroupNotification(){
        $user=DAO::getById(User::class, USession::get('activeUser')['id']);
        $groupNotif=[];
        foreach($user->getGroups() as $group){
            if(DAO::getOne(Usergroup::class,'idGroup=? AND status="0"',false,[$group->getId()])!=null){
                \array_push($groupNotif,$group);
                continue;
            }
        }
        return $groupNotif;
    }
    
    public function getExamNotification(){
        $user=DAO::getById(User::class, USession::get('activeUser')['id']);
        $userGroups=DAO::getAll(Usergroup::class,"idUser=? AND status='1'",false,[$user->getId()]);
        $examNotif=[];
        foreach($userGroups as $group){
            $exam=DAO::getOne(Exam::class,'idGroup=? AND datef>now()',false,[$group->getIdGroup()]);
            if($exam!=null){
                \array_push($examNotif,$exam);
            }
        }
        return $examNotif;
    }
    
    public function notifications(){
    	$userMyGroups=DAO::getAll(Group::class,"idUser=?",false,[USession::get('activeUser')['id']]);
    	$userGroups=DAO::getAll(Usergroup::class,"idUser=? AND status='1'",false,[USession::get('activeUser')['id']]);
    	foreach($userMyGroups as $group){
    		if(DAO::getOne(Usergroup::class,'idGroup=? AND status="0"',false,[$group->getId()])!=null){
    			return "true";
    		}
    	}	
    	foreach($userGroups as $group){
    		$exam=DAO::getOne(Exam::class,'idGroup=? AND datef>now()',false,[$group->getIdGroup()]);
    		if($exam!=null){
    			return "true";
    		}
    	}
    	return "false";
    }
}