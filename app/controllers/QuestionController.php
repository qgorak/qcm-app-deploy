<?php

namespace controllers;

use Ubiquity\controllers\Router;
use Ubiquity\orm\DAO;
use Ubiquity\security\acl\controllers\AclControllerTrait;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\http\USession;
use models\Answer;
use models\Question;
use models\Tag;
use services\QuestionDAOLoader;
use services\UIService;

/**
 * Controller QuestionController
 * @allow('role'=>'@USER')
 * @route('question','inherited'=>true,'automated'=>true)
 * @property \Ajax\php\ubiquity\JsUtils $jquery
 */
class QuestionController extends ControllerBase {
    use AclControllerTrait;
    
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
        $this->jquery->ajax('get', Router::path('question.my'),"#myquestions",[
            'hasLoader'=>false,
        ]);
        $this->jquery->ajax('get', Router::path('tag.my'),"#myTags",[
            'hasLoader'=>false,
        ]);
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
     * @get("add",'name'=>'question.add')
     */
    public function add() {
    	$types=$this->loader->getTypeq();
        $this->jquery->getHref('#cancel', '',[
            'hasLoader'=>'internal',
            'historize'=>false
        ]);
        $this->uiService->tagManagerJquery();

        $frm = $this->uiService->questionForm ('',$types);
        $frm->fieldAsSubmit ( 'submit', 'green', Router::path('question.submit'), '#response', [
            'ajax' => [
                'hasLoader' => 'internal',
                'params'=>'{"answers":$("#frmAnswer").serialize(),"ckcontent":window.editor.getData(),"tags":$("#checkedTagForm").serializeArray()}'
            ]
        ] );
        
        $lang=(USession::get('activeUser')['language']=='en_EN')? 'en' : 'fr';
        $this->jquery->renderView ( 'QuestionController/add.html', [
            'identifier'=>'#questionForm-ckcontent',
            'lang'=>$lang
        ]) ;
    }
	
    
    /**
     * @get("delete/{id}",'name'=>'question.delete')
     */
    public function delete($id) {
    	$this->loader->remove($id);
    	$msg = $this->jquery->semantic()->htmlMessage('','success !');
    	$this->index($msg);
    }
    
    /**
     * @get("patch/{id}",'name'=>'question.patch')
     */
    public function patch($id) {
    	$question = $this->loader->get($id);
    	$types=$this->loader->getTypeq();

    	$this->jquery->ajax('get', 'question/getform/'.$question->getIdTypeq().'','#response-form',[
    	    'hasLoader'=>false,
    	]);
    	$this->jquery->getHref('#cancel', '',[
    			'hasLoader'=>false,
    			'historize'=>false
    	]);
    	$this->uiService->tagManagerJquery();
    	$frm = $this->uiService->questionForm ($question,$types);
    	$frm->fieldAsSubmit ( 'submit', 'green', Router::path('question.submit.patch'), '#response', [
    			'content'=>'Edit',
    	        'ajax' => [
    					'hasLoader' => 'internal',
    					'params'=>'{"answers":$("#frmAnswer").serialize(),"ckcontent":window.editor.getData(),"tags":$("#checkedTagForm").serializeArray()}'
    			]
    	] );
    	$frm->addField('id');
    	$frm->fieldAsHidden('id',[
    	    'value'=>$id
    	]);
    	USession::set('answers', $question->getAnswers());
    	$lang=(USession::get('activeUser')['language']=='en_EN')? 'en' : 'fr';
    	$this->jquery->renderView ( 'QuestionController/patch.html', [
    			'identifier'=>'#questionForm-ckcontent',
    			'lang'=>$lang
    	]) ;
    }
    
    /**
     * @get("preview/{id}","name"=>"question.preview")
     */
    public function preview($id) {
        $question = $this->loader->get($id);
        $answers = $question->getAnswers();
        $type = $question->getIdTypeq();
        switch ($type) {
        	case 1:
        		$this->jquery->renderView ( 'QuestionController/display/questionqcm.html', [
        				'question' => $question,
        				'answers' => $answers
        		]) ;
        		break;
        	case 2:
        		$this->jquery->renderView ( 'QuestionController/display/questionlong.html', [
        		'question' => $question,
        		'answers' => $answers
        		]) ;
        		break;
        	case 3:
        		$this->jquery->renderView ( 'QuestionController/display/questionshort.html', [
        		'question' => $question,
        		'answers' => $answers
        		]) ;
        		break;
        	case 4:
        		$this->jquery->renderView ( 'QuestionController/display/questioncode.html', [
        		'question' => $question,
        		'answers' => $answers
        		]) ;
        		break;
        }
        
    }

    public function getform($type,$msg='') {
        switch ($type) {
            case 1:
                $this->getMultipleChoicesJquery();
                $this->jquery->renderView('QuestionController/template/1.html', ['answers'=>USession::get('answers'),'msg'=>$msg]);
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
     * @post("addAnswerToQuestion","name"=>"question.add.answer")
     */
    public function addAnswerToQuestion() {
        $postAnswers = URequest::getDatas();
        $answerObjects = array();
        for ($i = 0; $i < count($postAnswers['caption']); $i++) {
            $answerToInsert = new Answer();
            $answerToInsert->setCaption($postAnswers['caption'][$i]);
            $answerToInsert->setScore($postAnswers['score'][$i]);
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
     * @delete("removeAnswerFromQuestion/{index}","name"=>"question.delete.answer")
     */
    public function removeAnswerFromQuestion(int $index) {
        if($index!=1){
            $answers = USession::get('answers');
            unset($answers [$index-1]);
            $answers = array_values($answers);
            USession::set('answers', $answers);
            $this->getform(1);
        }else{
            $this->getform(1,'You cant');
        }
    }
    
    /**
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
     * @get("displayMyQuestions","name"=>"question.my")
     */
    public function displayMyQuestions() {
    	$dt=$this->uiService->getQuestionDataTable($this->loader->my());
    	$this->_index($this->jquery->renderView( 'QuestionController/template/myQuestions.html', [] ,true));
    }
    
    /**
     * @post("add","name"=>"question.submit")
     */
    public function submit() {
        $question= new Question ();
        URequest::setValuesToObject($question);
        $question->setIdTypeq(URequest::post('typeq'));
        $tagsObjects = $this->getTagPostData();
        $answerObjects = $this->getAnswersPostData();
        $this->loader->add ( $question, $tagsObjects );
        foreach($answerObjects as $answer) {
            $answer->setQuestion($question);
            DAO::insert($answer,true);
        }
        $msg = $this->jquery->semantic()->htmlMessage('','success !');
        $this->index($msg);
    }
    
    /**
     * @post("submitpatch","name"=>"question.submit.patch")
     */
    public function submitPatch() {
        $question= new Question ();
        URequest::setValuesToObject($question);
        $question->setIdTypeq(URequest::post('typeq'));
        $tagsObjects = $this->getTagPostData();
        $answerObjects = $this->getAnswersPostData();
        $question->setAnswers($answerObjects);
        $this->loader->update( $question, $tagsObjects );
        foreach($answerObjects as $answer) {
            $answer->setQuestion($question);
            DAO::insert($answer,true);
        }
        $msg = $this->jquery->semantic()->htmlMessage('','success !');
        $this->index($msg);
    }
    
    private function getAnswersPostData(){
        $post = URequest::getDatas();
        if(strlen($post['answers'])>0){
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
        } 
        return $answerObjects;
    }
    
    private function getTagPostData(){
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
        return $tagsObjects;
    }
}