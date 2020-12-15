<?php
namespace controllers;

use Ubiquity\orm\DAO;
use Ubiquity\security\acl\controllers\AclControllerTrait;
use models\Useranswer;
use services\CorrectionUIService;
use services\ExamDAOLoader;
use models\Answer;

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
     * @param \services\ExamDAOLoader $loader
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
        $questions = DAO::getManyToMany($qcm, 'questions',true);
        $acc = $this->uiService->correctionAccordion();
        $userScore=0;
        $totalScore=0;
        foreach($questions as $question){
            $userAnswer = DAO::getOne(Useranswer::class,'idUser=? and idExam=? and idQuestion=?',false,[$idUser,$idExam,$question->getId()]);
            switch ($question->getIdTypeq()) {
                case 1:
                    $res=$this->correctQcmAnswer($acc,$question,$userAnswer); 
                    $userScore+=$res[1];
                    $totalScore+=$res[0];
                    break;
                case 2:
                    $res=$this->correctShortAnswer($acc,$question,$userAnswer);
                    $userScore+=$res[1];
                    $totalScore+=$res[0];
                    break;
                case 3:
                    $res=$this->correctLongAnswer($acc,$question,$userAnswer);
                    $userScore+=$res[1];
                    $totalScore+=$res[0];
                    break;
            }
        }
        $this->jquery->renderView('CorrectionController/result.html',['totalScore'=>$totalScore,'userScore'=>$userScore]);
    }
    
    
    private function correctQcmAnswer($acc,$question,$userAnswer){
        $answers = $question->getAnswers();
        $userAnswers = json_decode($userAnswer);
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
            array_push($answersToDisplay,$answer); 
        }
        $dt = $this->uiService->correctionAnswersDataTable($answersToDisplay);
        $acc->addItem(array($question->getCaption(),$dt));
        return [$totalScore,$score];
    }

    private function correctShortAnswer($acc,$question,$userAnswer){
        $answer = $question->getAnswers()[0];
        $userAnswer = json_decode($userAnswer);
        $score=0;
        $totalScore=0;
        $answer->value=$userAnswer->answer;
        $answer->scoreUser=0;
        $totalScore+=$answer->getScore();
        $dt = $this->uiService->shortAnswerTable($answer);
        $acc->addItem(array($question->getCaption(),$dt));
        return [$totalScore,$score];
    }

    private function correctLongAnswer($acc,$question,$userAnswer){
        $answer = $question->getAnswers()[0];
        $userAnswer = json_decode($userAnswer);
        $score=0;
        $totalScore=0;
        $answer->value = $userAnswer->answer;
        $answer->scoreUser = $userAnswer->points;
        $totalScore += $answer->getScore();
        $totalScore += $userAnswer->points;
        $dt = $this->uiService->longAnswerTable($answer);
        $acc->addItem(array($question->getCaption(),$dt));
        return [$totalScore,$score];
    }

    
 
}

