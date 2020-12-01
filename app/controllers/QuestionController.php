<?php

namespace controllers;

use Ajax\semantic\html\collections\HtmlMessage;
use Ubiquity\controllers\Router;
use Ubiquity\orm\DAO;
use Ubiquity\utils\http\URequest;
use models\Question;
use models\User;
use services\QuestionDAOLoader;
use Ubiquity\utils\http\USession;
use Ajax\php\ci\JsUtils;


/**
 * Controller QuestionController
 * @route('question','automated'=>true)
 * @property \Ajax\php\ubiquity\JsUtils $jquery
 */
class QuestionController extends ControllerBase {
    
    /**
     *
     * @autowired
     * @var QuestionDAOLoader
     */
    private $loader;
    
    /**
     *
     * @param \services\QuestionSessionLoader $loader
     */
    public function setLoader($loader) {
        $this->loader = $loader;
    }
    private function displayItems() {
        $items = $this->loader-> my();
        $dt = $this->jquery->semantic ()->dataTable ( 'dtItems', Question::class, $items );
        $msg = new HtmlMessage ( '', "Aucun élément à afficher !" );
        $msg->addIcon ( "x" );
        $dt->setEmptyMessage ( $msg );
        $dt->setFields ( [
            'id',
            'caption'
        ] );
        
        $dt->setIdentifierFunction ( 'getId' );
        $dt->addEditDeleteButtons ( false );
        
        $dt->setEdition ();
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
    }
    


    public function index() {
        $this->_index ();
    }
    
    /**
     *
     * @get("add")
     */
    public function add() {
    	$frm=$this->jquery->semantic()->dataForm('frm', new Question());
    	$frm->setFields(['caption','type']);
    	$frm->fieldAsDropDown('type',['qcm','ouverte']);
    	
    	$frm->fieldAsTextarea('caption',['rules'=>'empty']);
    	
    	
        $this->jquery->postFormOnClick ( '#btValidate', 'question/add', 'frmItem', 'body', [
            'hasLoader' => 'internal'
        ] );
        
        
        $this->jquery->exec('$(\'#drop\').dropdown()',true);
        $this->jquery->exec(' $("#test").change(function () {
		 $("#test").attr("lenomquejeveux",$(\'#test\').find(":selected").attr(\'data-value\'));

    });',true);
        
        $this->jquery->ajaxOn('change','#test', '/question','#response', 
        		[
        				'attr' => 'value'
        		]);
        if (URequest::isAjax ()) {
            $this->jquery->renderView ( 'QuestionController/add.html' , [ ]);
        } else {
            $this->jquery->renderView ( 'QuestionController/add.html', [ ]) ;
        }
    }
    
    /**
     *
     * @get("getform/{type}")
     */
    public function getform($type) {

    		$this->jquery->renderView ( 'QuestionController/add.html', [ ]) ;
    }
    
    
    /**
     *
     * @post("add")
     */
    public function submit() {
        $question= new Question ();
        $question->setCaption ( URequest::post ( 'caption', 'no caption' ) );
        $creator = new User();
        $creator->setId(USession::get('activeUser')['id']);
        $question->setUser($creator);
        $this->loader->add ( $question );
        $this->jquery->renderView ( 'QuestionController/add.html' , [ ]);
    }
    private function _index($response = '') {
        $this->displayItems ();
        
        $this->jquery->renderView ( 'QuestionController/index.html', [
            'response' => $response
        ] );
    } 
}
    