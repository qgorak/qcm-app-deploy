<?php

namespace services;

use Ajax\php\ubiquity\JsUtils;
use models\Answer;
use Ubiquity\utils\http\USession;

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
}