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
use Ubiquity\assets\AssetsManager;


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
        $answer_array= array();
        array_push($answer_array,new Answer());
        USession::set('answers',$answer_array);
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
            'hasLoader'=>'internal',
            'attr'=>'data-value'
        ]);
        $this->jquery->getHref('#cancel', '',[
            'hasLoader'=>'internal',
            'historize'=>false
        ]);
        $this->jquery->exec('$("#text-dropdown-questionForm-typeq-0").html("Select a type");',true);
        $frm = $this->uiService->questionForm ();
        $this->jquery->getOnClick ( '#dropdown-questionForm-typeq-0 .menu .item', 'question/getform', '#response-form', [
            'stopPropagation'=>false,
            'attr' => 'data-value',
            'hasLoader' => false,

        ] );
        $lang=(USession::get('activeUser')['language']=='en_EN')? 'en' : 'fr';
        $this->jquery->renderView ( 'QuestionController/add.html', [
            'identifier'=>'#questionForm-caption',
            'lang'=>$lang
        ]) ;
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

    public function getform($type) {
        $this->jquery->postFormOnClick ( '#addAnswer', Router::path('question.add.answer',['']) ,'frmAnswer', '#response-form', [
            'hasLoader' => 'internal',
            'method' => 'post',
            'attr' => 'data-ajax',
        ] );
        
        $this->jquery->renderView('QuestionController/template/'.$type.'.html', ['answers'=>USession::get('answers')]);

    }
    
    /**
     *
     * @post("addAnswerToQuestion","name"=>"question.add.answer")
     */
    public function addAnswerToQuestion() {
        $postAnswers = URequest::getPost();
        $answerObjects = array();

        for ($i = 1; $i <= count($postAnswers); $i++) {
            $answerToInsert = new Answer();
            $answerToInsert->setCaption($postAnswers['caption-'.$i]);
            array_push($answerObjects,$answerToInsert);
        }
        $newanswer = new Answer();
        $newanswer->setCaption('');
        array_push($answerObjects,$newanswer);
        USession::set('answers', $answerObjects);
        $this->getform(1);
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
