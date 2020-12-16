<?php
namespace controllers;

use Ajax\semantic\widgets\business\user\FormLogin;
use Ubiquity\orm\DAO;
use Ubiquity\security\acl\controllers\AclControllerTrait;
use models\Useranswer;
use services\CorrectionUIService;
use services\ExamDAOLoader;
use models\Answer;
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

    /**
     * @post('correctAnswer','name'=>'correct.answer')
     */
    public function correctAnswer(){
        $post =URequest::getPost();
        $identifiers = explode(',',$post['identifiers']);
        $userAnswer = DAO::getOne(Useranswer::class,'idUser=? and idExam=? and idQuestion=?',false,[$identifiers[0],$identifiers[1],$identifiers[2]]);
        $userAnswerValue = json_decode($userAnswer);
        $userAnswerValue->points=$post["score"];
        $userAnswerValue->comment=$post["comment"];
        $userAnswer->setValue(json_encode($userAnswerValue));
        DAO::update($userAnswer);

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
        $label = $this->jquery->semantic()->htmlLabel('mark',$score.'/'.$totalScore);
        $acc->addItem(array($question->getCaption().$label,$dt));
        return [$totalScore,$score];
    }

    private function correctShortAnswer($acc,$question,$userAnswer){
        $questionCreator = $question->getUser();
        $answer = $question->getAnswers()[0];
        $userAnswerValue = json_decode($userAnswer);
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
        $acc->addItem(array($question->getCaption().$label,$frm));
        return [$totalScore,$score];
    }

    private function correctLongAnswer($acc,$question,$userAnswer){
        $questionCreator = $question->getUser();
        $answer = $question->getAnswers()[0];
        $userAnswerValue = json_decode($userAnswer);
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
        $acc->addItem(array($question->getCaption().$label,$frm));
        return [$totalScore,$score];
    }

    
 
}

