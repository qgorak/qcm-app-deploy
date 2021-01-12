<?php
namespace controllers;

use models\Answer;
use models\Question;
use models\User;
use Ubiquity\orm\DAO;
use Ubiquity\security\acl\controllers\AclControllerTrait;
use models\Useranswer;
use services\UI\CorrectionUIService;
use services\DAO\ExamDAOLoader;
use Ubiquity\utils\http\URequest;

/**
 * Controller CorrectionController
 * @allow('role'=>'@USER')
 * @route('Correction','inherited'=>true,'automated'=>true)
 * @property \Ajax\php\ubiquity\JsUtils $jquery
 */
class CorrectionController extends ControllerBase{
    use AclControllerTrait;
    
    /**
     *
     * @autowired
     * @var ExamDAOLoader
     */
    private $loader;
    private $uiService;
    
    public function initialize(){
        parent::initialize();
        $this->uiService = new CorrectionUIService ( $this->jquery );
    }
    
    /**
     *
     * @param \services\DAO\ExamDAOLoader $loader
     */
    public function setLoader($loader) {
        $this->loader = $loader;
    }
    
    public function index() {
    }
    
    /**
     * @route('myresult/{idExam}/{idUser}','name'=>'Correction.myExam')
     */
    public function result($idExam,$idUser){
        $exam = $this->loader->get($idExam);
        $qcm = $exam->getQcm();
        $userAnswers = DAO::uGetAll(Useranswer::class,'idUser=? and exam.idQcm=?',['question'],[$idUser,$qcm->getId()]);
        $result = $this->correctExam($userAnswers);
        $this->jquery->renderView('CorrectionController/result.html',['totalScore'=>$result[1],'userScore'=>$result[2]]);
    }

    /**
     * @route('liveresult/{idExam}/{idUser}','name'=>'liveresult.exam')
     */
    public function liveresult($idExam,$idUser){
        $exam = $this->loader->get($idExam);
        $qcm = $exam->getQcm();
        $questions = DAO::getManyToMany($qcm,'questions');
        $countQ = count($questions);
        $userAnswers = DAO::uGetAll(Useranswer::class,'idUser=? and exam.idQcm=?',['question'],[$idUser,$qcm->getId()]);
        $counUA = count($userAnswers);
        $res = $counUA/$countQ*100;
        $result = $this->correctExam($userAnswers);
        $this->jquery->semantic()->htmlProgress('Progression',$res);
        $this->jquery->renderView('CorrectionController/liveresult.html',['totalScore'=>$result[1],'userScore'=>$result[2],'countUA'=>$counUA]);
    }

    /**
     * @route('correctUserAnswer/{idExam}/{idUser}/{idQuestion}','name'=>'liveresult.correctq')
     */
    public function liveresultCorrectQ($idExam,$idUser,$idQuestion){
        $exam = $this->loader->get($idExam);
        $question = DAO::getById(Question::class,$idQuestion,false);
        $userAnswer = DAO::GetOne(Useranswer::class,'idUser=? and idExam=? and idQuestion=?',false,[$idUser,$idExam,$idQuestion]);
        $userScore=0;
        switch ($question->getIdTypeq()) {
            case 1:
                $res=$this->correctQcmAnswer($question,$userAnswer);
                $userScore+=$res[1];
                $this->jquery->renderView('CorrectionController/templates/correctqcm.html',['caption'=>$question->getCaption()]);
                break;
            case 2:
                $res=$this->correctShortAnswer($question,$userAnswer);
                $userScore+=$res[1];
                $this->jquery->renderView('CorrectionController/templates/correctshort.html',['caption'=>$question->getCaption(),'frm'=>$res[2][1]]);
                break;
            case 3:
                $res=$this->correctLongAnswer($question,$userAnswer);
                $userScore+=$res[1];
                $this->jquery->renderView('CorrectionController/templates/correctlong.html',['caption'=>$question->getCaption(),'frm'=>$res[2][1]]);
                break;
        }
    }

    private function correctExam($userAnswers){
        $acc = $this->uiService->correctionAccordion();
        $userScore=0;
        $totalScore=0;
        foreach($userAnswers as $userAnswer){
            $question = $userAnswer->getQuestion();
            switch ($question->getIdTypeq()) {
                case 1:
                    $res=$this->correctQcmAnswer($question,$userAnswer);
                    $acc->addItem($res[2]);
                    $userScore+=$res[1];
                    $totalScore+=$res[0];
                    break;
                case 2:
                    $res=$this->correctShortAnswer($question,$userAnswer);
                    $acc->addItem($res[2]);
                    $userScore+=$res[1];
                    $totalScore+=$res[0];
                    break;
                case 3:
                    $res=$this->correctLongAnswer($question,$userAnswer);
                    $acc->addItem($res[2]);
                    $userScore+=$res[1];
                    $totalScore+=$res[0];
                    break;
            }
        }
        return [$acc,$totalScore,$userScore];
    }

    /**
     * @post('correctAnswer','name'=>'correct.answer')
     */
    public function correctAnswer(){
        $post =URequest::getPost();
        $identifiers = \explode(',',$post['identifiers']);
        $userAnswer = DAO::getOne(Useranswer::class,'idUser=? and idExam=? and idQuestion=?',false,[$identifiers[0],$identifiers[1],$identifiers[2]]);
        $userAnswerValue = \json_decode($userAnswer);
        $userAnswerValue->points=$post["score"];
        $userAnswerValue->comment=$post["comment"];
        $userAnswer->setValue(\json_encode($userAnswerValue));
        DAO::update($userAnswer);

    }

    private function correctQcmAnswer($question,$userAnswer){
        $answers = DAO::getAll(Answer::class,'idQuestion=?',false,[$question->getId()]);
        $userAnswers = \json_decode($userAnswer);
        $answersToDisplay=array();
        $score=0;
        $totalScore=0;
        foreach($answers as $answer){
            foreach($userAnswers->userAnswer as $uAnswer){
                if($uAnswer==$answer->getId()){
                    $score+=$answer->getScore();
                    $answer->checked = true;
                    break;
                }else{
                    $answer->checked = false;
                }
            }
            $totalScore += $answer->getScore();
            \array_push($answersToDisplay,$answer); 
        }
        $dt = $this->uiService->correctionAnswersDataTable($answersToDisplay);
        $label = $this->jquery->semantic()->htmlLabel('mark',$score.'/'.$totalScore);
        $item = array($question->getCaption().$label,$dt);
        return [$totalScore,$score,$item];
    }

    private function correctShortAnswer($question,$userAnswer){
        $questionCreator =  DAO::getOne(User::class,'id=?',false,[$question->getUser()]);
        $answer = DAO::getOne(Answer::class,'idQuestion=?',false,[$question->getId()]);
        $userAnswerValue = \json_decode($userAnswer);
        $score=0;
        $totalScore=0;
        $answer->value=$userAnswerValue->answer;
        $answer->scoreUser=$userAnswerValue->points;
        $answer->comment = $userAnswerValue->comment;
        $answer->identifiers = $userAnswer->getidUser().','.$userAnswer->getidExam().','.$userAnswer->getidQuestion();
        $frm = $this->uiService->shortAnswerForm($answer,$questionCreator->getId(),$totalScore);
        $totalScore+=$answer->getScore();
        $score+=$userAnswerValue->points;
        $label = $this->jquery->semantic()->htmlLabel('mark',$score.'/'.$totalScore);
        $item = array($question->getCaption().$label,$frm);
        return [$totalScore,$score,$item];
    }

    private function correctLongAnswer($question,$userAnswer){
        $questionCreator =  DAO::getOne(User::class,'id=?',false,[$question->getUser()]);
        $answer = DAO::getOne(Answer::class,'idQuestion=?',false,[$question->getId()]);
        $userAnswerValue = \json_decode($userAnswer);
        $score=0;
        $totalScore=0;
        $answer->value = $userAnswerValue->answer;
        $answer->scoreUser = $userAnswerValue->points;
        $answer->comment = $userAnswerValue->comment;
        $answer->identifiers = $userAnswer->getidUser().','.$userAnswer->getidExam().','.$userAnswer->getidQuestion();
        $totalScore += $answer->getScore();
        $score += $userAnswerValue->points;
        $frm = $this->uiService->longAnswerForm($answer,$questionCreator->getId(),$totalScore);
        $label = $this->jquery->semantic()->htmlLabel('mark',$score.'/'.$totalScore);
        $item = array($question->getCaption().$label,$frm);
        return [$totalScore,$score,$item];
    }
}