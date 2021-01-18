<?php

namespace services\DAO;

use Ubiquity\orm\DAO;
use Ubiquity\utils\http\USession;
use models\Question;
use models\User;
use models\Answer;

class QuestionDAOLoader {

	public function get($id): ?Question {
		return DAO::getById ( Question::class, $id, true );
	}
	
	public function getByTags($tags): ?array {
	    $res=array();
        $i=0;
	    foreach($tags as $tag) {
            $temp = DAO::getManyToMany($tag, 'questions',['tags']);
                if($i>0){
                    $newRes = [];
                    foreach ($temp as $question){
                        if (isset($res[$question->getId()])) {
                            array_push($newRes,$question);
                        } 
                    }
                    $res=$newRes;
                }else{
                    $res=$temp;
                }
            $i++;
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
	
	public function my($page=1): array{
        $myquestions =DAO::paginate(Question::class,$page,30,'idUser='.USession::get('activeUser')['id'],['tags']);
	    return $myquestions;
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
	    DAO::deleteAll( Answer::class ,'idQuestion=?',[$item->getId()]);
		DAO::update($item);
		DAO::insertOrUpdateAllManyToMany($item);
	}
	
	public function getTypeq(){
		return [1=>'QCM',2=>'courte',3=>'longue',4=>'code'];
	}

    public function getIconTypeq(){
        return [[1,'QCM','check square'],[2,'courte','bars'],[3,'longue','align left'],[4,'code','code']];
    }
}