<?php

namespace controllers;

use Ubiquity\assets\AssetsManager;
use Ubiquity\controllers\Router;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\http\USession;
use models\Answer;
use models\Question;
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
        $answer_array= array();
        $answer = new Answer();
        $answer->setScore(0);
        array_push($answer_array,$answer);
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
    
    private function getMultipleChoicesJquery(){
        $this->jquery->postFormOnClick ( '#addAnswer', Router::path('question.add.answer',['']) ,'frmAnswer', '#response-form', [
            'hasLoader' => 'internal',
            'method' => 'post',
            'attr' => 'data-ajax',
        ] );
        $this->jquery->ajaxOnClick ( '._remove', Router::path('question.delete.answer',['']) , '#response-form', [
            'hasLoader' => 'internal',
            'method' => 'delete',
            'attr' => 'data-ajax',
        ] );
        
    }
    
    /**
     *
     * @get("add",'name'=>'question.add')
     */
    public function add() {
        $this->jquery->postFormOnClick('#create', Router::path('question.submit'), 'questionAnswerForm','#response',[
            'hasLoader'=>'internal',
            'params'=>'{"answers":$("#frmAnswer").serialize()}'
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
        $includeCkEditor=AssetsManager::js("js/ckeditor/includeEN.js");
        if(USession::get('activeUser')['language']=='fr_FR'){
            $includeCkEditor=AssetsManager::js("js/ckeditor/includeFR.js");
        }
        $this->jquery->renderView ( 'QuestionController/add.html', ['ckEditor'=>$includeCkEditor]) ;
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
        switch ($type) {
            case 1:
                $this->getMultipleChoicesJquery();
                break;
        }
        $this->jquery->renderView('QuestionController/template/'.$type.'.html', ['answers'=>USession::get('answers')]);
    }
    
    /**
     *
     * @post("addAnswerToQuestion","name"=>"question.add.answer")
     */
    public function addAnswerToQuestion() {
        $postAnswers = URequest::getPost();
        $answerObjects = array();
        for ($i = 1; $i <= count($postAnswers)/2; $i++) {
            $answerToInsert = new Answer();
            $answerToInsert->setCaption($postAnswers['caption-'.$i]);
            $answerToInsert->setScore($postAnswers['score-'.$i]);
            array_push($answerObjects,$answerToInsert);
        }
        $newanswer = new Answer();
        $newanswer->setCaption('');
        $newanswer->setScore(0);
        array_push($answerObjects,$newanswer);
        USession::set('answers', $answerObjects);
        return $this->getform(1);
    }
    
    /**
     *
     * @delete("removeAnswerFromQuestion/{index}","name"=>"question.delete.answer")
     */
    public function removeAnswerFromQuestion(int $index) {
        $answers = USession::get('answers');
        unset($answers [$index-1]);
        $answers = array_values($answers);
        USession::set('answers', $answers);
        $this->getform(1);
    }
    
    /**
     *
     * @post("add","name"=>"question.submit")
     */
    public function submit() {
        
        $post = URequest::getPost();

        $strArray = explode("&", str_replace( '&amp;', '&', $post['answers']));

        foreach($strArray as $item) {
            $array = explode("=", $item);
            $returndata[] = $array;
        }
        $question= new Question ();
        $answerObjects = array();
        for ($i = 0; $i < count($returndata)/2; $i++) {
            $answerToInsert = new Answer();
            $answerToInsert->setCaption($returndata[$i][1]);
            $answerToInsert->setScore($returndata[$i+1][1]);
            var_dump($answerToInsert->getCaption());
            array_push($answerObjects,$answerToInsert);
        }


        $question->setCaption ( URequest::post ( 'caption', 'no caption' ) );
        $question->setUser(USession::get('activeUser')['id']);
        $this->loader->add ( $question);
        $this->jquery->renderView ( 'QuestionController/add.html' , [ ]);

    }
}
