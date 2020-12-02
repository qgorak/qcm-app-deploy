<?php

namespace services;

use Ubiquity\orm\DAO;
use Ubiquity\utils\http\USession;
use models\Group;
use models\User;

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
	
	public function my(): array{
	    $userid = USession::get('activeUser')['id'];
	    return DAO::getAll( Group::class, 'idUser='.$userid);
	}
	

	public function clear(): void {
		DAO::deleteAll ( Group::class, '1=1' );
	}

	public function remove(string $id): bool {
		return DAO::delete ( Group::class, $id );
	}


	public function update(Group $group): bool {
		return DAO::update ( $group );
	}


}

