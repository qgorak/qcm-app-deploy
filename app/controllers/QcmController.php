<?php
namespace controllers;

use Ubiquity\controllers\Router;
use Ubiquity\orm\DAO;
use Ubiquity\utils\http\USession;
use models\Question;
use services\QcmDAOLoader;

/**
 * Controller QcmController
 * @route('qcm','inherited'=>true, 'automated'=>true)
 * @property \Ajax\php\ubiquity\JsUtils $jquery
 */
class QcmController extends ControllerBase{
    
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
	    $myQuestions = DAO::getAll( Question::class, 'idUser='.USession::get('activeUser')['id']);
	    $this->jquery->ajaxOnClick('#cancel', Router::path('indexQcm'),'#response',[
	        'hasLoader'=>'internal'
	    ]);
	    $this->_index($this->jquery->renderView ( 'QcmController/add.html', [
	        'questions' => $myQuestions
	    ]) );
	}
	

}
