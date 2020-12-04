<?php

namespace services;

use Ubiquity\orm\DAO;
use Ubiquity\utils\http\USession;
use models\Group;
use models\User;
use models\Usergroup;

class GroupDAOLoader {

	public function get($id): ?Group {
		return DAO::getById ( Group::class, $id );
	}

	public function add(Group $group): void {
	    $creator = new User();
	    $creator->setId(USession::get('activeUser')['id']);
	    $group->setUser($creator);
		DAO::insert ( $group );
	}
	public function all(): array {
		return DAO::getAll ( Group::class );
	}
	
	public function myGroups(): array{
		$user = DAO::getById(User::class, USession::get('activeUser')['id']);
	    return $user->getAllGroups();
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
}

