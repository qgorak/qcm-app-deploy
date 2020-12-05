<?php

namespace controllers;

use Ubiquity\controllers\Router;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\http\USession;
use models\Answer;
use models\Question;
use models\Typeq;
use models\User;
use services\QuestionDAOLoader;
use services\UIService;


/**
 * Controller QuestionController
 * @route('question','inherited'=>true,'automated'=>true)
 * @property \Ajax\php\ubiquity\JsUtils $jquery
 */
class QuestionController extends ControllerBase {
    
    /**
     *
     * @autowired
     * @var QuestionDAOLoader
     */
    private $loader;
    private $uiService;
    
    /**
     *
     * @param \services\QuestionDAOLoader $loader
     */
    public function setLoader($loader) {
        $this->loader = $loader;
    }
    
    public function initialize(){
        parent::initialize();
        $this->uiService = new UIService ( $this->jquery );
    }
    

    /**
     *
     * @route('/','name'=>'question')
     */
    public function index() {
        $dt=$this->uiService->getQuestionDataTable($this->loader-> my());
        $this->jquery->getOnClick ( '._delete', 'delete', 'body', [
            'hasLoader' => 'internal',
            'attr' => 'data-ajax'
        ] );
        $this->jquery->getOnClick ( '._edit', Router::path ( 'Question.update', [
            ''
        ] ), '#response', [
            'hasLoader' => 'internal',
            'attr' => 'data-ajax'
        ] );
        
        $this->jquery->getHref('#add', '',[
            'hasLoader'=>'internal',
            'historize'=>false
        ]);
        $this->_index ($this->jquery->renderView ( 'QuestionController/template/myQuestion.html',[

        ],true));
    }
    
    private function _index($response='') {
        $this->jquery->renderView ( 'QuestionController/index.html', [
            'response' => $response
        ] );
    }
    
    /**
     *
     * @get("add",'name'=>'question.add')
     */
    public function add() {
        $this->jquery->postFormOnClick('#create', Router::path('question.submit'), 'questionForm','#response',[
            'hasLoader'=>'internal'
        ]);
        $this->jquery->getHref('#cancel', '',[
            'hasLoader'=>'internal',
            'historize'=>false
        ]);
        $this->jquery->exec('$("#text-dropdown-questionForm-typeq-0").html("Select a type");',true);
        $frm = $this->uiService->questionForm ();
        $this->jquery->getOnClick ( '.menu .item', 'question/getform', '#response-form', [
            'stopPropagation'=>false,
            'attr' => 'data-value',
            'hasLoader' => 'internal',
            'jsCallback'=>'$("#dropdown-form-typeq-0").dropdown("toggle");
                           $("#dropdown-form-typeq-0").attr("tabindex","2");'
        ] );
        $this->jquery->ajaxOn('change', '.menu .item', 'question/getform');
        $this->jquery->renderView ( 'QuestionController/add.html', []) ;
    }
    
    /**
     *
     * @get("one/{id}","name"=>"getOne")
     */
    public function getOne($id) {
        $question = $this->loader->get($id);
        $answers = $question->getAnswers();
        $this->jquery->renderView ( 'QuestionController/question.html', [ 
            'question' => $question,
            'answers' => $answers
        ]) ;
    }

    public function getform($type,$nbAnswer = 1) {
        USession::set('answers',array());
        $this->jquery->renderView('QuestionController/template/'.$type.'.html', ['nbAnswer'=>$nbAnswer]);

    }
    
    /**
     *
     * @post("addAnswerToQuestion","name"=>"question.add.answer")
     */
    public function addAnswerToQuestion() {
        $question= new Question ();
        $answer = new Answer();
        $answer->setQuestion($question);
        $answer->setCaption(URequest::post ( 'answerCaption', 'no caption' ) );
        $question->setCaption ( URequest::post ( 'caption', 'no caption' ) );
        $creator = new User();
        $creator->setId(USession::get('activeUser')['id']);
        $question->setUser($creator);
        $this->loader->add ( $question,$answer );
        $this->jquery->renderView ( 'QuestionController/add.html' , [ ]);
        
    }
    
    /**
     *
     * @post("add","name"=>"question.submit")
     */
    public function submit() {
        $question= new Question ();
        $answer = new Answer();
        $answer->setQuestion($question);
        $answer->setCaption(URequest::post ( 'answerCaption', 'no caption' ) );
        $question->setCaption ( URequest::post ( 'caption', 'no caption' ) );
        $creator = new User();
        $creator->setId(USession::get('activeUser')['id']);
        $question->setUser($creator);
        $this->loader->add ( $question,$answer );
        $this->jquery->renderView ( 'QuestionController/add.html' , [ ]);

    }
}
