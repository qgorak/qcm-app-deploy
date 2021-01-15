<?php

namespace services\DAO;

use models\Answer;
use models\User;
use models\Useranswer;
use models\Usergroup;
use Ubiquity\orm\DAO;
use models\Exam;
use models\Group;
use models\Qcm;
use Ubiquity\utils\http\USession;

class ExamDAOLoader {

	public function get($id): ?Exam {
		return DAO::getById(Exam::class,$id,true);
	}

	public function add(Exam $exam): void {
	    $exam = new Exam();
		DAO::insert ( $exam );
	}
	
	public function all(): array {
		return DAO::getAll ( Exam::class );
	}

    public function my(): array {
        return DAO::uGetAll(Exam::class,'qcm.idUser = ?',true,[USession::get('activeUser')['id']]);;
    }
	
	public function clear(): void {
		DAO::deleteAll ( Exam::class, '1=1' );
	}

	public function remove(string $id): bool {
	    DAO::deleteAll(Exam::class,"id=?",[$id]);
		return DAO::delete ( Exam::class, $id );
	}


	public function update(Exam $exam): bool {
		return DAO::update ( $exam );
	}
	
	public function allMyQCM(){
	    return DAO::getAll(Qcm::class,'idUser=?',false,[USession::get('activeUser')['id']]);
	}
    public function examGroup($idGroup){
        return DAO::getAll(Exam::class,'idGroup=?',['group','qcm'],[$idGroup]);
    }
	public function allMyGroup(){
	    return DAO::getAll(Group::class,"idUser=?",false,[USession::get('activeUser')['id']]);
	}
    public function allMyExamInProgress(){
        return DAO::uGetAll(Exam::class,"qcm.idUser=? AND datef>now() AND dated<now()",true,[USession::get('activeUser')['id']]);
    }
    public function allMyComingExam(){
        return DAO::uGetAll(Exam::class,"qcm.idUser=? AND datef>now() AND dated>now()",true,[USession::get('activeUser')['id']]);
    }
    public function getExamTotalScore($id){
        $exam=DAO::uGetOne(Exam::class,'id= ?',['qcm.questions'],[$id]);
        $questions = $exam->getQcm()->getQuestions();
        $totalScoreExam = 0;
        foreach ($questions as $question){
            $answers = DAO::getAll(Answer::class, 'idQuestion=?',false,[$question->getId()]);
            foreach ($answers as $answer){
                $totalScoreExam+=$answer->getScore();
            }
        }
        return $totalScoreExam;
    }
    public function getExamUsersScores($id){
        $exam=DAO::uGetOne(Exam::class,'id= ?',['qcm.questions','group'],[$id]);
        $users = [];
        $usersResults['users'] = [];
        $usersResults['mark'] = [];
        $userGroup=DAO::getAll(Usergroup::class,"idGroup=? AND status='1'",false,[$exam->getGroup()->getId()]);
        foreach($userGroup as $value){
            \array_push($users,DAO::getById(User::class,$value->getIdUser(),false));
        }
        $questions = $exam->getQcm()->getQuestions();
        foreach ($users as $user){
            $mark=0;
            foreach ($questions as $question){
                $uas = DAO::getAll(Useranswer::class,'idExam=? AND idQuestion=? AND idUser=?',false,[$exam->getId(),$question->getId(),$user->getId()]);
                if(count($uas)==0){
                    $mark=-1;
                }else {
                    foreach ($uas as $ua) {
                        $val = json_decode($ua->getValue());
                        $mark += $val->points;
                        if($val->corrected==false){
                            $mark==-2;
                            break;
                        }
                    }
                }
            }
            \array_push($usersResults['mark'],  $mark);
            \array_push($usersResults['users'],  $user->getId());
        }
        return $usersResults;
    }

    public function getExamSuccessRate($id){
        $usersResults = $this->getExamUsersScores($id);
        $examTotalScore = $this->getExamTotalScore($id);
        $countUsers = count($usersResults['users']);
        $graduatingUsers = 0;
        $notGraduatingUsers = 0;
        $missingUsers = 0;
        for($i = 0; $i < $countUsers; ++$i) {
            if($usersResults['mark'][$i]>=intval($examTotalScore/2)){
                $graduatingUsers++;
            }else{
                $notGraduatingUsers++;
            }
            if($usersResults['mark'][$i]==-1){
                $missingUsers++;
            }
        }
        $participants = $countUsers-$missingUsers;
         $notGraduatingUsers = $participants-$graduatingUsers;
        return [$notGraduatingUsers,$graduatingUsers,$countUsers,$missingUsers,$participants];
    }

}

