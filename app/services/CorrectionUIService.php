<?php

namespace services;

use Ajax\php\ubiquity\JsUtils;
use models\Answer;
use Ubiquity\utils\http\USession;
use Ajax\semantic\html\collections\form\HtmlFormTextarea;
use Ubiquity\controllers\Router;

class CorrectionUIService {
    protected $jquery;
    protected $semantic;
    public function __construct(JsUtils $jq) {
        $this->jquery = $jq;
        $this->semantic = $jq->semantic ();
    }
    
    public function correctionAccordion() {
        $acc=$this->jquery->semantic()->htmlAccordion("accordion3");
        $acc->setStyled();
        $acc->setExclusive(false);
        $acc->setClass('ui styled fluid accordion');
        return $acc;
    }

    public function correctionAnswersDataTable($answers) {
        $dt=$this->jquery->semantic()->dataTable('dtCorrectionAnswers', Answer::class,$answers);
        $dt->setFields ( [
            'checked',
            'caption',
            'score'
        ] );
        $dt->setCaptions ( [
            'User answer',
            'caption',
            'score'
        ] );
        $dt->fieldAsCheckbox('checked');
        return $dt;
    }

    public function shortAnswerTable($answer) {
        $dt=$this->jquery->semantic()->dataTable('dtCorrectionAnswers', Answer::class,array($answer));
        $dt->setFields ( [
            'value',
            'scoreUser',
            'caption',
            'possible_answer',
        ] );
        $dt->setCaptions ( [
            'User answer',
            'scoreUser',
            'Reponses possibles',
        ] );
        $dt->fieldAsInput('value','disabled');
        return $dt;
    }

    public function longAnswerTable($answer,$idQuestionCreator) {
        $dt=$this->jquery->semantic()->dataTable('dtCorrectionAnswers', Answer::class,array($answer));
        $dt->setFields ( [
            'value',
            'scoreUser',
            'comment',
        ] );
        $dt->setCaptions ( [
            'User answer',
            'scoreUser',
            'Commentaire',
        ] );
        $dt->fieldAsTextarea('value','disabled');
        if(USession::get('activeUser')['id']==$idQuestionCreator){
            $dt->fieldAsInput('comment',['test'=>'test']);
            $dt->insertDefaultButtonIn(4,'plus');
            $dt->fieldAsInput('scoreUser');
            $dt->setEdition(true);
        }
        return $dt;
    }
    public function longAnswerForm($answer,$idQuestionCreator,$totalScore) {
        $form=$this->jquery->semantic()->htmlForm('frmCorrectionAnswers');
        $form->addItem(new HtmlFormTextarea("userAnswer","userAnwser",$answer->value));
        $scoreInput = $form->addInput('score','userScore','number',$answer->scoreUser);
        $form->addItem(new HtmlFormTextarea("comment","comment",$answer->comment));
        $form->addInput('identifiers','','hidden',$answer->identifiers);
        if(USession::get('activeUser')['id']==$idQuestionCreator){
            $this->jquery->attr('#score','min',-100,true);
            $this->jquery->attr('#score','max',$totalScore,true);
            $this->jquery->attr('#score','step',0.5,true);
            $scoreInput->setDisabled(false);
            $form->addButton('submitCorrection','submit');
            $this->jquery->postFormOnClick('#submitCorrection',Router::path('correct.answer'),'frmCorrectionAnswers','',['hasLoader'=>'internal']);
        }else{
            $this->jquery->attr('#score','step',0.5,true);
            $this->jquery->attr('#comment','disabled','',true);
            $scoreInput->setDisabled(true);
        }
        $this->jquery->attr('#userAnswer','disabled','',true);
        return $form;
    }
}