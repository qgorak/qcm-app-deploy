<?php

namespace services;

use Ubiquity\orm\DAO;
use models\Answer;
use models\Question;
use Ubiquity\utils\http\USession;
use models\User;
use models\Questiontag;

class QuestionDAOLoader {

	public function get($id): ?Question {
		return DAO::getById ( Question::class, $id );
	}

	public function add(Question $item,array $tags): void {
	    $creator = new User();
	    $creator->setId(USession::get('activeUser')['id']);
	    $item->setUser($creator);
		DAO::insert ( $item );
		foreach($tags as $tag) {
		    $questiontag = new Questiontag();
		    $questiontag->setIdQuestion($item->getId());
		    $questiontag->setIdTag($tag);
		    DAO::insert($questiontag);
		}
	}

	public function all(): array {
		return DAO::getAll ( Question::class,false );
	}
	
	public function my(): array{
	    $userid = USession::get('activeUser')['id'];
	    return DAO::getAll( Question::class, 'idUser='.$userid,false);
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

