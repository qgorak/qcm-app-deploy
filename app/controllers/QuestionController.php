<?php

namespace controllers;

use Ubiquity\controllers\Router;
use Ubiquity\orm\DAO;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\http\USession;
use models\Answer;
use models\Question;
use models\Tag;
use models\Typeq;
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
        $toolbar=$this->uiService->questionBankToolbar();
        $this->_index ($this->jquery->renderView('QuestionController/template/QuestionBank.html',[],true), [
        ] );
    }
    
    private function _index($response='') {

    	$this->jquery->getHref('#add', '',[
    			'hasLoader'=>'internal',
    			'historize'=>false
    	]);
    	$this->jquery->ajax('get', Router::path('question.my'),"#myquestions");
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
        $this->jquery->postFormOnClick('#create', Router::path('question.submit'), 'questionForm','#response',[
            'hasLoader'=>'internal',
            'params'=>'{"answers":$("#frmAnswer").serialize(),"ckcontent":window.editor.getData(),"tags":$("#checkedTagForm").serializeArray()}'
        ]);
        $this->jquery->getHref('#cancel', '',[
            'hasLoader'=>'internal',
            'historize'=>false
        ]);
        $this->jquery->ajax('get',Router::path('tag.my'),'#tagManager',[
            'hasLoader'=>'internal',
            'historize'=>false,
            'jsCallback'=>'$(".ui.menu .item:first-child").popup({
   								 popup : $(".ui.popup"),
    						     on : "click"
							});;'
        ]);
        $this->jquery->postFormOnClick('#addTag', Router::path('tag.submit'), 'tagForm','#tagManager',[
            'hasLoader'=>'internal',
            'jsCallback'=>"$('#nametag').val('');"
        ]);
        $this->jquery->exec('$("#text-dropdown-questionForm-typeq-0").html("Select a type");',true);
        $frm = $this->uiService->questionForm ();
        $this->jquery->getOnClick ( '#dropdown-questionForm-typeq-0 .menu .item', 'question/getform', '#response-form', [
            'stopPropagation'=>false,
            'attr' => 'data-value',
            'hasLoader' => false,
            'jsCallback' =>'$("#input-dropdown-questionForm-typeq-0").attr("name","typeq");
                            $("#input-dropdown-questionForm-typeq-0").val($(self).attr("data-value"))'

        ] );
        $lang=(USession::get('activeUser')['language']=='en_EN')? 'en' : 'fr';
        $this->jquery->renderView ( 'QuestionController/add.html', [
            'identifier'=>'#questionForm-ckcontent',
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
            $answerToInsert->setCaption(html_entity_decode($postAnswers['caption-'.$i]));
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
     * @post("getByTags","name"=>"question.getBy.tags")
     */
    public function getByTags() {
    	$tag = new Tag();
    	$tag->setId(3);
    	$this->loader->getByTag($tag);
    	$dt=$this->uiService->getQuestionDataTable();
    	$this->jquery->ajaxOn('change','#input-Filter', Router::path('question.getBy.tags',['']),"#myquestions",[
    			'preventDefault'=>false,
    			'method' => 'post',
    	]);
    	
    	$this->jquery->renderView ( 'QuestionController/template/myQuestions.html',[
    	]);
    }
    
    /**
     *
     * @get("displayMyQuestions","name"=>"question.my")
     */
    public function displayMyQuestions() {

    	$dt=$this->uiService->getQuestionDataTable($this->loader->my());
    	$this->jquery->ajaxOn('change','#input-Filter', Router::path('question.getBy.tags',['']),"#myquestions",[
    			'preventDefault'=>false,
    			'method' => 'post',
    	]);
    	$this->jquery->renderView ( 'QuestionController/template/myQuestions.html', [] );
    }
    
    /**
     *
     * @post("add","name"=>"question.submit")
     */
    public function submit() {
        $post = URequest::getPost();
        $tags = URequest::getInput()['tags'];
        $tagsObjects = array();
        for ($i = 0; $i < count($tags); $i++) {
        	$tagToInsert = new Tag();
        	$tagToInsert->setId($tags[$i]['name']);
        	array_push($tagsObjects,$tagToInsert);
        }
        $strAnswersArray = explode("&", str_replace( '&amp;', '&', $post['answers']));
        $postAnswers = array();
        foreach($strAnswersArray as $item) {
            $array = explode("=", $item);
            array_push($postAnswers,$array);
        }
        $answerObjects = array();
        for ($i = 0; $i < count($postAnswers); $i += 2) {
            $answerToInsert = new Answer();
            $answerToInsert->setCaption($postAnswers[$i][1]);
            $answerToInsert->setScore($postAnswers[$i+1][1]);
            array_push($answerObjects,$answerToInsert);
        }
        $question= new Question ();
        $typeq= new Typeq ();
        $typeq->setId($post['typeq']);
        $question->setCaption ( $post['caption'] );
        $question->setCkContent ( $post['ckcontent'] );
        $question->setTypeq($typeq);
        $question->setUser(USession::get('activeUser')['id']);
        $this->loader->add ( $question, $tagsObjects );
        foreach($answerObjects as $answer) {
            $answer->setQuestion($question);
            DAO::insert($answer,true);
        }
        $this->jquery->renderView ( 'QuestionController/add.html' , [ ]);
    }
}