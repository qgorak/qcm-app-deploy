<?php

namespace services;

use Ubiquity\orm\DAO;
use Ubiquity\utils\http\USession;
use models\Question;
use models\User;
use models\Tag;

class QuestionDAOLoader {

	public function get($id): ?Question {
		return DAO::getById ( Question::class, $id );
	}
	
	public function getByTag($tag): ?array {
		return DAO::getManyToMany($tag, 'questions',true);
	}
	

	public function add(Question $item,array $tags): void {
	    $creator = new User();
	    $creator->setId(USession::get('activeUser')['id']);
	    $item->setUser($creator);
	    $item->setTags($tags);
		DAO::insert ( $item);
		DAO::insertOrUpdateAllManyToMany($item);
	}

	public function all(): array {
		return DAO::getAll ( Question::class,false );
	}
	
	public function my(): array{
	    $userid = USession::get('activeUser')['id'];
	    return DAO::getAll( Question::class, 'idUser='.$userid,true);
	}

	public function clear(): void {
		DAO::deleteAll ( Question::class, '1=1' );
	}

	public function remove(string $id): bool {
		return DAO::delete ( Question::class, $id );
	}

	public function update(Question $item): bool {
		return DAO::update ( $item );
	}
}

