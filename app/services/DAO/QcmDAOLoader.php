<?php

namespace services\DAO;

use models\Question;
use Ubiquity\orm\DAO;
use Ubiquity\utils\http\USession;
use models\Qcm;
use models\User;

class QcmDAOLoader {

	public function get($id): ?Qcm {
		return DAO::getById ( Qcm::class, $id ,true);
	}

	public function add(Qcm $qcm): void {
        $creator = new User();
        $creator->setId(USession::get('activeUser')['id']);
        $qcm->setUser($creator);
        $questions = USession::get('questions');
        $qcm->setQuestions($questions);
        $qcm->setCdate(\date_create()->format('Y-m-d H:i:s'));
		DAO::insert($qcm,true);
	}

	public function all(): array {
		return DAO::getAll ( Qcm::class );
	}
	
	public function my(): array{
	    $userid = USession::get('activeUser')['id'];
	    return DAO::getAll( Qcm::class, 'idUser=?',['questions.answers'],[$userid]);
	}

	public function clear(): void {
		DAO::deleteAll ( Qcm::class, '1=1' );
	}

	public function remove(string $id): bool {
		return DAO::delete ( Qcm::class, $id );
	}

	public function update($item): bool {
		return DAO::update ( $item );
	}

    public function getquestions(): array {
        $userid = USession::get('activeUser')['id'];
        $checkedquestion = USession::get('questions');
        $res = DAO::getAll(Question::class, 'idUser=?',['tags','answers'],[$userid]);
        foreach ($checkedquestion as $question){
            if (isset($checkedquestion[$question->getId()])) {
                unset($res[$question->getId()]);
            }
        }
        return $res;
    }
    public function getByTags($tags): ?array {
        $res=array();
        $i=0;
        foreach($tags as $tag) {
            $temp = DAO::getManyToMany($tag, 'questions',['tags','answers']);
            if($i>0){
                $newRes = [];
                foreach ($temp as $question){
                    if (isset($res[$question->getId()])) {
                        $newRes[$question->getId()]=$question;
                    }
                }
                $res=$newRes;
            }else{
                $res=$temp;
            }
            $i++;
        }
        $myQuestions = USession::get('questions');
        foreach ($myQuestions  as $question){
            if (isset($myQuestions[$question->getId()])) {
                unset($res[$question->getId()]);
            }
        }
        return $res;
    }
}