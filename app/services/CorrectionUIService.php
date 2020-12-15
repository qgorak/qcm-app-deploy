<?php

namespace services;

use Ajax\php\ubiquity\JsUtils;
use models\Answer;

class CorrectionUIService {
    protected $jquery;
    protected $semantic;
    public function __construct(JsUtils $jq) {
        $this->jquery = $jq;
        $this->semantic = $jq->semantic ();
    }
    
    public function correctionAccordion() {
        $acc=$this->jquery->semantic()->htmlAccordion("accordion3");
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
            'score'
        ] );
        $dt->setCaptions ( [
            'User answer',
            'scoreUser',
            'Reponses possibles',
            'score'
        ] );
        $dt->fieldAsInput('value','disabled');
        return $dt;
    }

    public function longAnswerTable($answer) {
        $dt=$this->jquery->semantic()->dataTable('dtCorrectionAnswers', Answer::class,array($answer));
        $dt->setFields ( [
            'value',
            'scoreUser',
            'caption',
            'score'
        ] );
        $dt->setCaptions ( [
            'User answer',
            'scoreUser',
            'Commentaire',
            'score'
        ] );
        $dt->fieldAsTextarea('value','disabled');
        return $dt;
    }
}