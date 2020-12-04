<?php

namespace controllers;

use Ajax\semantic\html\collections\HtmlMessage;
use Ubiquity\controllers\Router;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\http\USession;
use models\Answer;
use models\Question;
use models\User;
use services\QuestionDAOLoader;


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
    
    /**
     *
     * @param \services\QuestionDAOLoader $loader
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
        $dt->onRowClick('alert(\'ok\')');
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
    


    /**
     *
     * @route('/','name'=>'question')
     */
    public function index() {
        $this->_index ();
    }
    
    private function _index($response = '') {
        $this->displayItems ();
        $this->jquery->renderView ( 'QuestionController/index.html', [
            'response' => $response
        ] );
    } 
    
    /**
     *
     * @get('add','name'=>'questionAdd')
     */
    public function add() {
        $this->jquery->postFormOnClick ( '#btValidate', Router::path('QuestionAddSubmit'), 'frmItem', 'body', [
            'hasLoader' => 'internal'
        ] );

        $this->jquery->ajaxOn('click','#addAnswer', "QuestionController/getform/qcm/'+document.getElementById('nbAnswer').value+'", '#response',
            [
                'jsCallback'=>'$("#nbAnswer").get(0).value++'
            ]);
        $this->jquery->exec('$(\'#drop\').dropdown()',true);
        $this->jquery->ajaxOn('change','#test',"QuestionController/getform/'+document.getElementById('test').value+'/'+document.getElementById('nbAnswer').value+'",'#response', );
        $this->jquery->renderView ( 'QuestionController/add.html', []) ;
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

    public function getform($type,$nbAnswer = 1) {

        $this->jquery->renderView('QuestionController/template/'.$type.'.html', ['nbAnswer'=>$nbAnswer]);

    }
    
    /**
     *
     * @post("add","name"=>"QuestionAddSubmit")
     */
    public function submit() {
        $question= new Question ();
        $answer = new Answer();
        $answer->setQuestion($question);
        $answer->setCaption(URequest::post ( 'answerCaption', 'no caption' ) );
        $question->setCaption ( URequest::post ( 'caption', 'no caption' ) );
        $creator = new User();
        $creator->setId(USession::get('activeUser')['id']);
        $question->setUser($creator);
        $this->loader->add ( $question,$answer );
        $this->jquery->renderView ( 'QuestionController/add.html' , [ ]);

    }
   
	public function qsd($param,$param2){
		
	}

}
