<?php
namespace services;

use Ubiquity\orm\DAO;
use Ubiquity\utils\http\USession;
use models\Exam;
use models\Usergroup;
use models\User;

class NotificationDAOLoader{
    
    public function getGroupNotification(){
        $user=DAO::getById(User::class, USession::get('activeUser')['id']);
        $groupNotif=[];
        foreach($user->getGroups() as $group){
            if(DAO::getOne(Usergroup::class,'idGroup=? AND status="0"',false,[$group->getId()])!=null){
                array_push($groupNotif,$group->getId());
                continue;
            }
        }
        return $groupNotif;
    }
    
    public function getExamNotification(){
        $user=DAO::getById(User::class, USession::get('activeUser')['id']);
        $userGroups=DAO::uGetAll(Usergroup::class,"idUser=? AND status='1'",false,[$user->getId()]);
        $examNotif=[];
        foreach($userGroups as $group){
            $exam=DAO::uGetOne(Exam::class,'idGroup=? AND datef>=now()',false,[$group->getIdGroup()]);
            if($exam!=null){
                array_push($examNotif,$exam->getId());
            }
        }
        return $examNotif;
    }
    
}