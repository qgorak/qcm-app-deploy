<?php
namespace controllers;

use Ajax\semantic\html\collections\HtmlMessage;
use Ubiquity\controllers\Router;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\http\USession;
use models\Qcm;
use models\Question;
use services\QcmDAOLoader;
use services\QuestionDAOLoader;
use services\UIService;

/**
 * Controller QcmController
 * @route('qcm','inherited'=>true, 'automated'=>true)
 * @property \Ajax\php\ubiquity\JsUtils $jquery
 */
class QcmController extends ControllerBase{
    private $uiService;
    public function initialize() {
        parent::initialize ();
        $this->uiService = new UIService ( $this->jquery );
    }
    
    /**
     *
     * @autowired
     * @var QcmDAOLoader
     */
    private $loader;
    
    /**
     *
     * @param \services\QcmDAOLoader $loader
     */
    public function setLoader($loader) {
        $this->loader = $loader;
    }
    
    /**
     *
     * @route('/','name'=>'qcm')
     */
	public function index($msg=''){
	    $questionLoader = new QuestionDAOLoader();
	    $myQuestions = array();
	    $myQuestions['notchecked'] = $questionLoader->my();
	    $myQuestions['checked'] = array();
	    $modal=$this->uiService->modal();
	    USession::set('questions', $myQuestions);
	    $this->jquery->getHref('#addQcm', '',[
	        'hasLoader'=>'internal',
	        'historize'=>false
	    ]);
	    $dt = $this->uiService->getQcmDataTable($this->loader->my());
	    $this->_index($this->jquery->renderView ( 'QcmController/templates/myQcm.html',[
	        'msg' => $msg
	    ],true));
	}
	
	private function _index($response = '') {
	    $this->jquery->renderView ( 'QcmController/index.html', [
	        'response' => $response
	    ] );
	}
	
	/**
	 *
	 * @get("add","name"=>'qcm.add')
	 */
	public function add() {
	    $frmQcm = $this->uiService->qcmForm();
	    $this->jquery->postFormOnClick('#create', Router::path('qcm.submit'), 'qcmForm','#response',[
	        'hasLoader'=>'internal'
	    ]);
	    $this->jquery->ajax('get', Router::path('qcm.display.bank'),'#responseBank' );
	    $this->jquery->renderView ( 'QcmController/add.html', []);
	}
	
	/**
	 *
	 * @get("addQuestion/{id}","name"=>"qcm.add.question")
	 */
	public function addQuestionToQcm($id) {
	    $myQuestions = USession::get('questions');
	    foreach ($myQuestions['notchecked'] as $key => $value) {
	        if($value->getId()==$id){
	            $question = $value;
	            array_push($myQuestions['checked'],$question);
	            unset($myQuestions['notchecked'][$key]);
	            break;
	        }    
	    }
	    USession::set('questions', $myQuestions);
	    $this->displayQuestionBankImport();
	}
	
	/**
	 *
	 * @get("questionBankImport","name"=>'qcm.display.bank')
	 */
	public function displayQuestionBankImport(){
	    $dtQuestionNotChecked = $this->uiService->questionDataTable('dtQuestionNotChecked',USession::get('questions')['notchecked'],false);
	    $dtQuestionChecked = $this->uiService->questionDataTable('dtQuestionChecked',USession::get('questions')['checked'],true);
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
	 *
	 * @delete("deleteQuestion/{id}","name"=>"qcm.delete.question")
	 */
	public function removeQuestionToQcm($id) {
	    $myQuestions = USession::get('questions');
	    foreach ($myQuestions['checked'] as $key => $value) {
	        if($value->getId()==$id){
	            $question = $value;
	            array_push($myQuestions['notchecked'],$question);
	            unset($myQuestions['checked'][$key]);
	            break;
	        }
	    }
	    USession::set('questions', $myQuestions);
	    $this->displayQuestionBankImport();
	}
	
	/**
	 *
	 * @post("filterQuestionBank","name"=>"qcm.filter")
	 */
	public function filterQuestionBank() {
	    $this->displayQuestionBankImport();

	}
	
	/**
	 *
	 * @get("delete/{id}",'name'=>'qcm.delete')
	 */
	public function delete($id) {
		$this->loader->remove($id);
		$msg = $this->jquery->semantic()->htmlMessage('','success !');
		$this->index($msg);
	}
	
	/**
	 *
	 * @get("preview/{id}","name"=>"qcm.preview")
	 */
	public function preview($id) {
		$qcm = $this->loader->get($id);
		$this->jquery->renderView ( 'QcmController/qcm.html', [
				'qcm' => $qcm,
		]) ;
	}
	
	/**
	 *
	 * @post("add","name"=>"qcm.submit")
	 */
	public function submit() {
	    $qcm = new Qcm();
	    $qcm->setName(URequest::post ( 'name', 'no name' ) );
	    $qcm->setDescription(URequest::post ( 'description', '' ) );
	    $this->loader->add ($qcm);
	    USession::delete('questions');
	    $this->_index($this->index(new HtmlMessage ( '', "Success !" )));
	}

}
