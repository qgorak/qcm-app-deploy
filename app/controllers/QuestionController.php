<?php

namespace controllers;

use Ajax\semantic\html\elements\HtmlLabel;
use Ubiquity\controllers\Router;
use Ubiquity\orm\DAO;
use Ubiquity\security\acl\controllers\AclControllerTrait;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\http\USession;
use models\Answer;
use models\Question;
use models\Tag;
use services\DAO\QuestionDAOLoader;
use services\UI\QuestionUIService;

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
     * @param \services\DAO\QuestionDAOLoader $loader
     */
    public function setLoader($loader) {
        $this->loader = $loader;
    }
    
    public function initialize(){
        parent::initialize();
        $this->uiService = new QuestionUIService( $this->jquery );
        if (! URequest::isAjax ()) {
            $this->loadView('/main/UI/trainerNavbar.html');
            $this->jquery->getHref ( '.trainermenu', '#response', [
                'hasLoader' => 'internal'
            ] );
        }
        $this->jquery->attr('#trainermode','class','item active',true);
    }

    /**
     * @route('/','name'=>'question')
     */
    public function index($msg='') {
        $answer_array= array();
        $answer = new Answer();
        $answer->setScore(0);
        \array_push($answer_array,$answer);
        USession::set('answers',$answer_array);
        $this->uiService->questionTagsFilterDd();
        $this->uiService->questionTypeFilterDd();
        $this->uiService->modal();
        $this->jquery->exec('window.scrollTo(0, 0);',true);
        $this->jquery->ajax('get', Router::path('tag.my'),"#myTags",[
            'hasLoader'=>true
        ]);
        $this->jquery->getHref ( '#add', '#response', [
            'hasLoader' => 'internal',
            'historize'=>false
        ] );
        $myquestions=$this->displayMyQuestions();
        $this->jquery->renderView('QuestionController/index.html',['msg'=>$msg,'myquestions'=>$myquestions]);
    }

    
    private function getMultipleChoicesJquery(){
        $this->jquery->execOn('click','.button-add','var clone = $(".box:first").clone();
                                                    clone.find("input:text").val("");
                                                    clone.find("#score").val(0);
                                                    clone.insertAfter(".box:last")');
        $this->jquery->exec('$(document).on("click", ".button-remove", function() {
                            $(this).closest(".box").remove();
                            });',true);
    }
    
    /**
     * @get("add",'name'=>'question.add')
     */
    public function add() {
        $this->jquery->getHref ( '#cancel', '#response', [
            'hasLoader' => 'internal'
        ] );
    	$types=$this->loader->getIconTypeq();
        $this->uiService->questionForm (new Question(),$types);
        $lang=(USession::get('activeUser')['language']=='en_EN')? 'en' : 'fr';
        $this->jquery->exec('includeCkEditor("#ckeditor","'.$lang.'");',true);
        $this->jquery->renderView ( 'QuestionController/add.html', []) ;
    }
	
    
    /**
     * @get("delete/{id}",'name'=>'question.delete')
     */
    public function delete($id) {
        $question = $this->loader->get($id);
        $creator = $question->getUser();
        if($creator->getId()==USession::get('activeUser')['id']){
            $this->loader->remove($id);
        }
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
        		$this->jquery->renderView ( 'QuestionController/display/questionshort.html', [
        		'question' => $question,
        		'answers' => $answers
        		]) ;
        		break;
        	case 3:
        		$this->jquery->renderView ( 'QuestionController/display/questionlong.html', [
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
                $this->getMultipleChoicesJquery();
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
     * @post("getByTags","name"=>"question.getBy.tags")
     */
    public function getByTags() {
        $postTags = URequest::getInput('tags');
        if(\strlen($postTags['tags'])>0){
           $tagIdArray = \explode(',',URequest::getPost()['tags']);
    	   $tagObjects = array();
    	   $tag = new Tag();
    	   foreach($tagIdArray as $tagId) {
    	       $tag->setId($tagId);
    	       \array_push($tagObjects,$tag);
    	   }
    	   $questions = $this->loader->getByTags($tagObjects);
        }else{
            $questions = $this->loader->my();
        }
        echo $this->getQuestionJsonArray($questions);
    }

    /**
     * @get("displayMyQuestions","name"=>"question.my")
     */
    public function displayMyQuestions() {
    	$this->uiService->getQuestionDataTable($this->loader->my(),$this->loader->getTypeq());
    	return $this->jquery->renderView( 'QuestionController/template/myQuestions.html', [] ,true);
    }
    
    /**
     * @post("add","name"=>"question.submit")
     */
    public function submit() {
        $question= new Question ();
        URequest::setValuesToObject($question);
        $question->setIdTypeq(URequest::post('type'));
        $tagsObjects = $this->getTagPostData();
        $answerObjects = $this->getAnswersPostData();
        $this->loader->add ( $question, $tagsObjects );
        foreach($answerObjects as $answer) {
            $answer->setQuestion($question);
            DAO::insert($answer,true);
        }
        $this->index();
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
        switch ($post['type']) {
            case 1:
                $answerObjects=$this->getQcmAnswersData($post);
                break;
            case 2:
                $answerObjects=$this->getShortAnswersData($post);
                break;
            case 3:
                $answerObjects=$this->getLongAnswerData($post);
                break;
            case 4:
                break;
        }
        return $answerObjects;
    }

    private function getQcmAnswersData($post){
        if(\strlen($post['answers'])>0){
            $strAnswersArray = \explode("&", \str_replace( '&amp;', '&', $post['answers']));
            $postAnswers = array();
            foreach($strAnswersArray as $item) {
                $array = \explode("=", $item);
                \array_push($postAnswers,$array);
            }
            $answerObjects = array();
            for ($i = 0; $i < \count($postAnswers); $i += 2) {
                $answerToInsert = new Answer();
                $answerToInsert->setCaption($postAnswers[$i][1]);
                $answerToInsert->setScore($postAnswers[$i+1][1]);
                \array_push($answerObjects,$answerToInsert);
            }
        }
        return $answerObjects;
    }

    private function getShortAnswersData($post){
        if(\strlen($post['answers'])>0){
            $strAnswersArray = \explode("&", \str_replace( '&amp;', '&', $post['answers']));
            $postAnswers = array();
            foreach($strAnswersArray as $item) {
                $array = \explode("=", $item);
                \array_push($postAnswers,$array);
            }
            $answersPossibilities = array();
            for ($i = 0; $i < \count($postAnswers)-1; $i ++) {
                $answersPossibilities[$i]=$postAnswers[$i+1][1];
            }
        }
        $answer = new Answer();
        $answer->setCaption(\json_encode($answersPossibilities));
        $answer->setScore($postAnswers[0][1]);
        return array($answer);
    }

    private function getLongAnswerData($post){
        $strAnswersArray = \explode("&", \str_replace( '&amp;', '&', $post['answers']));
        $array = \explode("=", $strAnswersArray[0]);
        $answer=new Answer();
        $answer->setScore($array[1]);
        return array($answer);
    }
    
    private function getTagPostData(){
        $post = URequest::getDatas()['mytags'];
        $tagsObjects = array();
        if($post!==''){
            $tagsId = \explode(',',$post);
            for ($i = 0; $i < \count($tagsId); $i++) {
                $tagToInsert = new Tag();
                $tagToInsert->setId($tagsId[$i]);
                \array_push($tagsObjects,$tagToInsert);
            }
        }
        return $tagsObjects;
    }

    /**
     * @post("jsonPagination","name"=>"question.json")
     */
    public function jsonPaginate() {
        $questions = $this->loader->my(URequest::getPost()['p']);
        echo $this->getQuestionJsonArray($questions);
    }

    private function getQuestionJsonArray($questions){
        $json= [];
        foreach ($questions as $question){
            $res = '';
            foreach ($question->_rest['tags'] as $tag){
                $res = $res.'<div class="ui '.$tag->_rest['color'].' label">'.$tag->_rest['name'].'</div>';
            }
            $question->_rest['tags']=$res;
            $typeq = [1=>['name'=>'QCM','icon'=>'check square'],2=>['name'=>'courte','icon'=>'bars'],3=>['name'=>'longue','icon'=>'align left'],4=>['name'=>'code','icon'=>'code']];
            $question->_rest['idTypeq']='<div class="ui label" style="display:inline-flex;"><i id="icon-" class="icon '.$typeq[$question->_rest['idTypeq']]['icon'].'"></i>'.$typeq[$question->_rest['idTypeq']]['name'].'</div>';
            array_push($json,$question->_rest);
        }
        return json_encode($json);
    }
}