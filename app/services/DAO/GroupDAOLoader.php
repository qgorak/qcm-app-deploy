<?php

namespace services\DAO;

use Ubiquity\orm\DAO;
use Ubiquity\utils\http\USession;
use models\Group;
use models\User;
use models\Usergroup;

class GroupDAOLoader {

	public function get($id): ?Group {
		return DAO::getById ( Group::class, $id );
	}

	public function add(Group $group) {
	    $creator = new User();
	    $creator->setId(USession::get('activeUser')['id']);
	    $group->setUser($creator);
		DAO::insert ( $group ); 
		return json_encode($group);
	}
	
	public function all(): array {
		return DAO::getAll ( Group::class );
	}
	
	public function myGroups(): array{
		$user = DAO::getById(User::class, USession::get('activeUser')['id']);
	    return $user->getGroups();
	}

	public function inGroups(){
	    return $this->getAllGroups(USession::get('activeUser')['id']);	    
	}
	
	public function clear(): void {
		DAO::deleteAll ( Group::class, '1=1' );
	}

	public function remove(string $id): bool {
	    DAO::deleteAll(Usergroup::class,"idGroup=?",[$id]);
		return DAO::delete ( Group::class, $id );
	}


	public function update(Group $group): bool {
		return DAO::update ( $group );
	}
	
	public function getByKey($key) {
		return DAO::getOne(Group::class,"keyCode=?",true,[$key]);
	}
	
	public function getJoiningDemand($id){
	    $users=[];
	    $userGroups=DAO::uGetAll(Usergroup::class,"idGroup=? AND status=0",false,[$id]);
	    foreach($userGroups as $value){
	        array_push($users,DAO::getById(User::class, $value->getIdUser(),false));
	    }
	    return $users;
	}
	
	public function acceptDemand($groupId,$userId){
	    $userGroup=DAO::getOne(Usergroup::class,"idUser=? AND idGroup=?",false,[$userId,$groupId]);
	    $userGroup->setStatus('1');
	    DAO::update($userGroup);
	}
	
	public function refuseDemand($groupId,$userId){
	    $userGroup=DAO::getOne(Usergroup::class,"idUser=? AND idGroup=?",false,[$userId,$groupId]);
	    DAO::toDelete($userGroup);
	    DAO::flushDeletes();
	}
	
	public function getUsers($groupId){
	    $users=[];
	    $userGroup=DAO::uGetAll(Usergroup::class,"idGroup=? AND status='1'",false,[$groupId]);
	    foreach($userGroup as $value){
	        array_push($users,DAO::getById(User::class,$value->getIdUser(),false));
	    }
	    return $users;
	}
	
	public function banUser($groupId,$userId){
	    DAO::deleteAll(Usergroup::class,'idGroup=? AND idUser=?',[$groupId,$userId]);
	}
	
	public function isCreator($groupId,$userId){
	    $group=DAO::getById(Group::class, $groupId);
	    if($group->getUser()==$userId){
	        return true;
	    }
	    return false;
	}
	
	public function isInGroup($groupId,$userId){
	    if(DAO::exists(Usergroup::class,"idGroup=? AND idUser=? AND status='1'",[$groupId,$userId])){
	        return true;
	    }
	    return false;
	}
	
	public function getAllGroups($userId){
	    $retour=[];
	    $userGroups=DAO::uGetAll(Usergroup::class,"idUser=? AND status='1'",false,[$userId]);
	    foreach($userGroups as $value){
	        array_push($retour,DAO::getById(Group::class,$value->getIdGroup(),false));
	    }
	    return $retour;
	}
	
	public function alreadyDemand($groupId,$userId){
	    if(DAO::getOne(Usergroup::class,'idGroup=? AND idUser=?',false,[$groupId,$userId])!=null){
	        return true;
	    }
	    return false;
	}
}

