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
    public function index($msg='') {
        $answer_array= array();
        $answer = new Answer();
        $answer->setScore(0);
        array_push($answer_array,$answer);
        USession::set('answers',$answer_array);
        $toolbar=$this->uiService->questionBankToolbar();
        $modal=$this->uiService->modal();
        $this->jquery->ajax('get', Router::path('question.my'),"#myquestions");
        $this->jquery->ajaxOn('change','#input-Filter', Router::path('question.getBy.tags',['']),"#myquestions",[
            'method' => 'post',
            'params' =>'{"tags":$("#input-Filter").val()}',
            'hasLoader'=>'internal'
        ]);
        $this->jquery->getHref('#add', '',[
            'hasLoader'=>'internal',
            'historize'=>false
        ]);
        $this->_index ($this->jquery->renderView('QuestionController/template/QuestionBank.html',['msg'=>$msg],true), [
        ] );
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
            'attr' => 'data-ajax'
        ] );
        $this->jquery->ajaxOnClick ( '._remove', Router::path('question.delete.answer',['']) , '#response-form', [
            'hasLoader' => 'internal',
            'method' => 'delete',
            'attr' => 'data-ajax'
        ] ); 
    }
    
    /**
     *
     * @get("add",'name'=>'question.add')
     */
    public function add() {
        $this->jquery->getHref('#cancel', '',[
            'hasLoader'=>'internal',
            'historize'=>false
        ]);
        $this->uiService->tagManagerJquery();
        $this->jquery->exec('$("#text-dropdown-questionForm-typeq-0").html("Select a type");',true);
        $frm = $this->uiService->questionForm ();
        $frm->fieldAsSubmit ( 'submit', 'green', Router::path('question.submit'), '#response', [
            'ajax' => [
                'hasLoader' => 'internal',
                'params'=>'{"answers":$("#frmAnswer").serialize(),"ckcontent":window.editor.getData(),"tags":$("#checkedTagForm").serializeArray()}'
            ]
        ] );
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
     * @get("delete/{id}",'name'=>'question.delete')
     */
    public function delete($id) {
    	$this->loader->remove($id);
    	$msg = $this->jquery->semantic()->htmlMessage('','success !');
    	$this->index($msg);
    }
    
    /**
     *
     * @get("patch/{id}",'name'=>'question.patch')
     */
    public function patch($id) {
    	$question = $this->loader->get($id);
    	$type=$question->getTypeq();

    	$this->jquery->ajax('get', 'question/getform/'.$type->getId().'','#response-form');
    	$this->jquery->exec('$("#dropdown-questionForm-typeq-0").prop("selectedIndex", '.$type->getId().')',true);
    	$this->jquery->getHref('#cancel', '',[
    			'hasLoader'=>'internal',
    			'historize'=>false
    	]);
    	$this->uiService->tagManagerJquery();
    	$frm = $this->uiService->questionForm ($question);
    	$frm->fieldAsSubmit ( 'submit', 'green', Router::path('question.submit.patch'), '#response', [
    			'ajax' => [
    					'hasLoader' => 'internal',
    					'params'=>'{"answers":$("#frmAnswer").serialize(),"ckcontent":window.editor.getData(),"tags":$("#checkedTagForm").serializeArray()}'
    			]
    	] );
    	USession::set('answers', $question->getAnswers());
    	$lang=(USession::get('activeUser')['language']=='en_EN')? 'en' : 'fr';
    	$this->jquery->renderView ( 'QuestionController/patch.html', [
    			'identifier'=>'#questionForm-ckcontent',
    			'lang'=>$lang
    	]) ;
    }
    
    /**
     *
     * @get("preview/{id}","name"=>"question.preview")
     */
    public function preview($id) {
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
                $this->jquery->renderView('QuestionController/template/1.html', ['answers'=>USession::get('answers')]);
                break;
            case 2:
            	$this->jquery->renderView('QuestionController/template/2.html', ['answers'=>USession::get('answers')]);
            	break;
            case 3:
            	$this->jquery->renderView('QuestionController/template/3.html', ['answers'=>USession::get('answers')]);
            	break;
            case 4:
            	$this->getMultipleChoicesJquery();
            	$this->jquery->renderView('QuestionController/template/4.html', ['answers'=>USession::get('answers')]);
            	break;
        }
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
        $postTags = URequest::getInput('tags');
        if(strlen($postTags['tags'])>0){
           $tagIdArray = explode(',',URequest::getPost()['tags']);
    	   $tagObjects = array();
    	   $tag = new Tag();
    	   foreach($tagIdArray as $tagId) {
    	       $tag->setId($tagId);
    	       array_push($tagObjects,$tag);
    	   }
    	   $questions = $this->loader->getByTags($tagObjects);
    	   $dt=$this->uiService->getQuestionDataTable($questions);
    	   $this->_index($this->jquery->renderView ( 'QuestionController/template/myQuestions.html',[
    	   ],true));
        }else{
           $this->displayMyQuestions();
        }
    }
    
    /**
     *
     * @get("displayMyQuestions","name"=>"question.my")
     */
    public function displayMyQuestions() {
    	$dt=$this->uiService->getQuestionDataTable($this->loader->my());
    	$this->_index($this->jquery->renderView( 'QuestionController/template/myQuestions.html', [] ,true));
    }
    
    /**
     *
     * @post("add","name"=>"question.submit")
     */
    public function submit() {
        $post = URequest::getDatas();
        $tagsObjects = array();
        if (array_key_exists ( 'tags', $post  )){
                $tags = $post['tags'];
            for ($i = 0; $i < count($tags); $i++) {
        	   $tagToInsert = new Tag();
        	   $tagToInsert->setId($tags[$i]['name']);
        	   array_push($tagsObjects,$tagToInsert);
            }
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
        $msg = $this->jquery->semantic()->htmlMessage('','success !');
        $this->index($msg);
    }
    

}