<?php
namespace controllers;

use Ubiquity\controllers\Router;
use Ubiquity\utils\http\USession;
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
        if(!USession::exists('questions')){
            $questionLoader = new QuestionDAOLoader();
            $myQuestions = array();
            $myQuestions['notchecked'] = $questionLoader->my();
            $myQuestions['checked'] = array();
            USession::init('questions', $myQuestions);
        }
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
     * @get("index","name"=>"indexQcm")
     */
	public function index(){
	    $this->jquery->ajaxOnClick('#addQcm', Router::path('addQcm'),'#response',[
	        'hasLoader'=>'internal'
	    ]);
	    $myQcm = $myQcm = $this->loader->my();
	    $this->_index($this->jquery->renderView ( 'QcmController/templates/myQcm.html',[
	        'qcm' => $myQcm
	    ],true));
	}
	
	private function _index($response = '') {
	    $this->jquery->renderView ( 'QcmController/index.html', [
	        'response' => $response
	    ] );
	}
	
	/**
	 *
	 * @get("add","name"=>"addQcm")
	 */
	public function add() {
	    $dtQuestionNotChecked = $this->uiService->questionDataTable('dtQuestionNotChecked',USession::get('questions')['notchecked'],false);
	    $dtQuestionChecked = $this->uiService->questionDataTable('dtQuestionChecked',USession::get('questions')['checked'],true);
	    $this->jquery->ajaxOnClick('#cancel', Router::path('indexQcm'),'#response',[
	        'hasLoader'=>'internal'
	    ]);
	    $this->jquery->ajaxOnClick ( '.notchecked ._element', 'qcm/addQuestion/', '#response', [
	        'hasLoader' => 'internal',
	        'attr' => 'data-ajax',
	        'jsCallback'=>''
	    ] );
	    $this->jquery->ajaxOnClick ( '.checked ._element', 'qcm/deleteQuestion/', '#response', [
	        'hasLoader' => 'internal',
	        'attr' => 'data-ajax'
	    ] );
	    $this->_index($this->jquery->renderView ( 'QcmController/add.html', [
	        'questions' => USession::get('questions')['notchecked'],
	        'questionsToAdd' => USession::get('questions')['checked']
	    ]) );
	}
	
	/**
	 *
	 * @get("addQuestion/{id}","name"=>"addQuestion")
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
	    $this->add();
	}
	
	/**
	 *
	 * @get("deleteQuestion/{id}","name"=>"deleteQuestion")
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
	    $this->add();
	}

}
