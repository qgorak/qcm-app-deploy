<?php
namespace controllers;

use Ajax\semantic\html\collections\HtmlMessage;
use models\Question;
use Ubiquity\controllers\Router;
use Ubiquity\orm\DAO;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\http\USession;
use models\Qcm;
use services\DAO\QcmDAOLoader;
use services\DAO\QuestionDAOLoader;
use Ubiquity\security\acl\controllers\AclControllerTrait;
use services\UI\QcmUIService;

/**
 * Controller QcmController
 * @allow('role'=>'@USER')
 * @route('qcm','inherited'=>true, 'automated'=>true)
 * @property \Ajax\php\ubiquity\JsUtils $jquery
 */
class QcmController extends ControllerBase{
    use AclControllerTrait;
    
    /**
     *
     * @autowired
     * @var QcmDAOLoader
     */
    private $loader;
    private $uiService;
    
    public function initialize() {
        parent::initialize ();
        $this->uiService = new QcmUIService( $this->jquery );
        if (! URequest::isAjax ()) {
            $this->loadView('/main/UI/trainerNavbar.html');
            $this->jquery->getHref ( '.trainermenu', '#response', [
                'hasLoader' => 'internal'
            ] );
        }
        $this->jquery->attr('#trainermode','class','item active',true);
    }

    public function finalize() {
        if (! URequest::isAjax ()) {
            $this->loadView('/main/UI/closeColumnCloseMenu.html');
        }
        parent::finalize();
    }
    
    /**
     *
     * @param \services\DAO\QcmDAOLoader $loader
     */
    public function setLoader($loader) {
        $this->loader = $loader;
    }
    
    /**
     * @route('/','name'=>'qcm')
     */
	public function index($msg=''){
	    $questionLoader = new QuestionDAOLoader();
	    $myQuestions = array();
	    $myQuestions['notchecked'] = $questionLoader->my();
	    $myQuestions['checked'] = array();
	    $this->uiService->modal();
	    USession::set('questions', $myQuestions);
	    $this->jquery->getHref('#addQcm', '',[
	        'hasLoader'=>'internal',
	        'historize'=>false
	    ]);
	    $this->uiService->getQcmDataTable($this->loader->my());
	    $this->jquery->renderView ( 'QcmController/index.html',[
	        'msg' => $msg
	    ]);
	}


	
	/**
	 * @get("add","name"=>'qcm.add')
	 */
	public function add() {
        $questionLoader = new QuestionDAOLoader();
        USession::set('questions',array());
	    $this->uiService->qcmForm();
	    $this->jquery->postFormOnClick('#create', Router::path('qcm.submit'), 'qcmForm','#response',[
	        'hasLoader'=>'internal'
	    ]);
	 //   $this->jquery->ajax('get', Router::path('qcm.display.bank'),'#responseBank' );
        $this->uiService->questionBankImportDataTable($questionLoader->my());
	    $this->jquery->renderView ( 'QcmController/add.html', []);
	}
	
	/**
	 * @get("addQuestion/{id}","name"=>"qcm.add.question")
	 */
	public function addQuestionToQcm($id) {
	    $myQuestions = USession::get('questions');
	    $question = DAO::getById(Question::class,$id,false);
	    $myQuestions[$question->getId()]=$question;
	    USession::set('questions', $myQuestions);
	    echo $this-> getQuestionJsonArray($this->loader->getquestions());
	}
	
	/**
	 * @get("questionBankImport","name"=>'qcm.display.bank')
	 */
	public function displayQuestionBankImport(){
	    $this->uiService->questionDataTable('dtQuestionNotChecked',USession::get('questions')['notchecked'],false);
	    $this->uiService->questionDataTable('dtQuestionChecked',USession::get('questions')['checked'],true);
	    $this->jquery->getHref('#cancel', '',[
	        'hasLoader'=>'internal',
	        'historize'=>false
	    ]);
	    $this->jquery->ajaxOnClick ( '._add', Router::path('qcm.add.question',['']) , '#responseBank', [
	        'hasLoader' => 'internal',
	        'method' => 'get',
	        'attr' => 'data-ajax',
	    ] );
	    $this->jquery->ajaxOnClick ( '._remove', Router::path('qcm.delete.question',['']) , '#responseBank', [
	        'hasLoader' => 'internal',
	        'method' => 'delete',
	        'attr' => 'data-ajax',
	    ] );
	    $this->jquery->ajaxOn('change','#input-Filter', Router::path('qcm.filter'),"#responseBank",[
	        'preventDefault'=>false,
	        'method' => 'post',
	        'params' =>'{"tags":$("#input-Filter").val()}',
	        'hasLoader'=>'internal'
	    ]);
	    $this->jquery->renderView ( 'QcmController/templates/questionBankImport.html', [] );    
	}
	
	/**
	 * @delete("deleteQuestion/{id}","name"=>"qcm.delete.question")
	 */
	public function removeQuestionToQcm($id) {
        $myQuestions = USession::get('questions');
        $question = DAO::getById(Question::class,$id,false);
        unset($myQuestions[$question->getId()]);
	    USession::set('questions', $myQuestions);
	    $this->displayQuestionBankImport();
        echo $this-> getQuestionJsonArray($this->loader->getquestions());
	}
	
	/**
	 * @post("filterQuestionBank","name"=>"qcm.filter")
	 */
	public function filterQuestionBank() {
	    $this->displayQuestionBankImport();
	}
	
	/**
	 * @get("delete/{id}",'name'=>'qcm.delete')
	 */
	public function delete($id) {
		$this->loader->remove($id);
		$msg = $this->jquery->semantic()->htmlMessage('','success !');
		$this->index($msg);
	}
	
	/**
	 * @get("preview/{id}","name"=>"qcm.preview")
	 */
	public function preview($id) {
		$qcm = $this->loader->get($id);
		$this->jquery->renderView ( 'QcmController/qcm.html', [
				'qcm' => $qcm,
		]) ;
	}
	
	/**
	 * @post("add","name"=>"qcm.submit")
	 */
	public function submit() {
	    $qcm = new Qcm();
	    $qcm->setName(URequest::post ( 'name', 'no name' ) );
	    $qcm->setDescription(URequest::post ( 'description', '' ) );
	    $this->loader->add ($qcm);
	    USession::delete('questions');
	    $this->index();
	}

    private function getQuestionJsonArray($questions){
        $json= [];
        foreach ($questions as $question){
            $res = '';
            $checkTags=array_key_exists('tags',$question->_rest);
            if($checkTags!==false){
                foreach ($question->_rest['tags'] as $tag){
                    $res = $res.'<div class="ui '.$tag->_rest['color'].' label">'.$tag->_rest['name'].'</div>';
                }
            }
            $question->_rest['tags']=$res;
            $typeq = [1=>['name'=>'QCM','icon'=>'check square'],2=>['name'=>'courte','icon'=>'bars'],3=>['name'=>'longue','icon'=>'align left'],4=>['name'=>'code','icon'=>'code']];
            $question->_rest['idTypeq']='<div class="ui label" style="display:inline-flex;"><i id="icon-" class="icon '.$typeq[$question->_rest['idTypeq']]['icon'].'"></i>'.$typeq[$question->_rest['idTypeq']]['name'].'</div>';
            array_push($json,$question->_rest);
        }
        return json_encode($json);
    }
}