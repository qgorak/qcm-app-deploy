<?php

namespace services;

use Ubiquity\orm\DAO;
use Ubiquity\utils\http\USession;
use models\Question;
use models\User;
use models\Tag;
use models\Answer;

class QuestionDAOLoader {

	public function get($id): ?Question {
		return DAO::getById ( Question::class, $id, true );
	}
	
	public function getByTags($tags): ?array {
	    $res=array();
	    foreach($tags as $tag) {
	        if (empty($res)) {
	            $res = DAO::getManyToMany($tag, 'questions',true);
	        }else{
	            $res = array_intersect($res, DAO::getManyToMany($tag, 'questions',true));
	            }     
	        }
	        return $res;
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

	public function update(Question $item,array $tags) {
	    $creator = new User();
	    $creator->setId(USession::get('activeUser')['id']);
	    $item->setUser($creator);
	    $item->setTags($tags);
		DAO::deleteAll( Answer::class , 'idQuestion='.$item->getId());
		DAO::update($item);
		DAO::insertOrUpdateAllManyToMany($item);
	}
	
	public function getTypeq(){
		return [1=>'QCM',2=>'courte',3=>'longue',4=>'code'];
	}
}

