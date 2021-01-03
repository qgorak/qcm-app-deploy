<?php

namespace services\DAO;

use models\Exam;
use models\Group;
use models\Usergroup;
use Ubiquity\orm\DAO;
use models\User;
use Ubiquity\utils\http\USession;

class UserDAOLoader {
    
    public function get($id): ?User{
        return DAO::getById ( User::class , $id );
    }
    
    public function all(): array {
        return DAO::getAll ( User::class,false );
    }
    
    public function clear(): void {
        DAO::deleteAll ( User::class, '1=1' );
    }
    
    public function remove(string $id): bool {
        return DAO::delete ( User::class, $id );
    }
    
    public function update(User $user): bool {
        return DAO::update ( $user );
    }
    
    public function getByEmail($email){
        return DAO::getOne(User::class,'email=?',false,[$email]);
    }

    private function getAllGroups($userId,$status){
        $retour=[];
        $userGroups=DAO::getAll(Usergroup::class,"idUser=? AND status=?",false,[$userId,$status]);
        foreach($userGroups as $value){
            \array_push($retour,DAO::getById(Group::class,$value->getIdGroup(),false));
        }
        return $retour;
    }

    public function getPastExam(){
        $retour=[];
        $groups = $this->getAllGroups(USession::get('activeUser')['id'],"1");
        foreach($groups as $value){
            $retour = array_merge($retour,DAO::uGetAll(Exam::class,"group.id=?",true,[$value->getId()]));
        }
        return $retour;
    }

    public function inGroups(){
        return $this->getAllGroups(USession::get('activeUser')['id'],"1");
    }

    public function waitGroups(){
        return $this->getAllGroups(USession::get('activeUser')['id'],"0");
    }
}