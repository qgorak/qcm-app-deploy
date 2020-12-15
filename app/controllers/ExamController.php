<?php
namespace controllers;

use Ajax\service\JArray;
use Ubiquity\controllers\Router;
use Ubiquity\controllers\Startup;
use Ubiquity\orm\DAO;
use Ubiquity\security\acl\controllers\AclControllerTrait;
use Ubiquity\translation\TranslatorManager;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\http\USession;
use DateTime;
use models\Exam;
use models\Group;
use models\Qcm;
use models\Question;
use services\ExamDAOLoader;
use models\Useranswer;

/**
 * Controller ExamController
 * @allow('role'=>'@USER')
 * @route('exam','inherited'=>true,'automated'=>true)
 * @property \Ajax\php\ubiquity\JsUtils $jquery
 */
class ExamController extends ControllerBase{
    use AclControllerTrait;

    /**
     *
     * @autowired
     * @var ExamDAOLoader
     */
    private $loader;
    
    /**
     *
     * @param \services\ExamDAOLoader $loader
     */
    public function setLoader($loader) {
        $this->loader = $loader;
    }
    
    private function displayMyExam() {
        $exam=$this->loader->all();
        $exams=$this->jquery->semantic()->dataTable('myExam',Exam::class,$exam);
        $exams->setFields([
        	'dated',
        	'datef',
        	'qcm',
        	'group'
        ]);
        $exams->setCaptions([
            TranslatorManager::trans('startDate',[],'main'),
            TranslatorManager::trans('endDate',[],'main'),
            TranslatorManager::trans('qcm',[],'main'),
            TranslatorManager::trans('group',[],'main')
        ]);
        $exams->setValueFunction('qcm',function($v){return $v->getName();});
        $exams->setValueFunction('group',function($v){return $v->getName();});
    }
    
    /**
     * @route('/','name'=>'exam')
     */
    public function index(){
        $this->jquery->ajaxOnClick('#addExam',Router::path ('examAdd'),'#response',[
            'hasloader'=>'internal'
        ]);
        $this->displayMyExam();
        $this->jquery->execOn('ready','document','$("pre code").each(function(i, e) {hljs.highlightBlock(e)});');
        $this->_index($this->jquery->renderView('ExamController/display.html',[],true));
    }
    
    private function _index($response = '') {
        $this->jquery->renderView ( 'ExamController/index.html', [
            'response' => $response
        ] );
    }
    
    /**
     * @get('add','name'=>'examAdd')
     */
    public function add(){
        $qcm=$this->loader->allMyQCM();
        $groups=$this->loader->allMyGroup();
        $exam=$this->jquery->semantic()->dataForm('exam',new Exam());
        $exam->setFields([
        	'idQcm',
        	'idGroup'
        ]);
        $exam->setCaptions([
            TranslatorManager::trans('qcm',[],'main'),
            TranslatorManager::trans('group',[],'main')
        ]);
        $exam->fieldAsDropDown('idQcm',JArray::modelArray($qcm,'getId','getName'));
        $exam->fieldAsDropDown('idGroup',JArray::modelArray($groups,'getId','getName'));
        $this->jquery->postFormOnClick('#examSubmit',Router::path ('examAddSubmit'),'examAdd','#response',[
        	'hasloader'=>'internal'
        ]);
        $this->jquery->renderView('ExamController/add.html');
    }
    
    /**
     * @post('add','name'=>'examAddSubmit')
     */
    public function addSubmit(){
        $exam=new Exam();
        $dated=str_replace(',','',URequest::post('dated'));
        $dated = new DateTime($dated);
        $datef=str_replace(',','',URequest::post('datef'));
        $datef = new DateTime($datef);
        $exam->setDated(date_format($dated,'Y-m-d H:i'));
        $exam->setDatef(date_format($datef,'Y-m-d H:i'));
        $exam->setQcm(DAO::getById(Qcm::class,URequest::post('idQcm'),false));
        $exam->setGroup(DAO::getById(Group::class,URequest::post('idGroup'),false));
        $exam->setOptions(URequest::post('options'));
        DAO::save($exam);
        $this->displayMyExam();
        $this->jquery->renderView('ExamController/display.html');
    }
    
    /**
     * @get('get/{id}','name'=>'exam.get')
     */
    public function getExam($id){
        $exam=$this->loader->get($id);
        $qcm=$exam->getQcm();
        $date=$exam->getDated();
        $this->jquery->getOnClick('#startExam', Router::path('exam.start',['']),'#response',[
        		'attr'=>'data-ajax',
                'jsCallback'=>'$("#btNext").css("display","block");'
        ]);
        $bt=$this->jquery->semantic()->htmlButton('btNext','next');
        $bt->addToProperty('style', 'display:none;');
        $this->jquery->postFormOnClick("#btNext", Router::path('exam.next'), 'frmUserAnswer','#response');
        $this->jquery->exec('var ws = new WebSocket("ws:/127.0.0.1:2346");
    ws.onopen = function() {
        alert("connection success");
        ws.send("Connected to exam");
    };
    ws.onmessage = function(e) {
        alert("message from server：" + e.data);
    };',true);
        $this->jquery->renderView('ExamController/exam.html',['name'=>$qcm->getName(),'date'=>$date,'id'=>$id]);
    }
    
    /**
     * @get('start/{id}','name'=>'exam.start')
     */
    public function ExamStart($id){
        $exam=$this->loader->get($id);
        $qcm=$exam->getQcm();
        $qcm = DAO::getById ( Qcm::class, $qcm->getId() ,true);
        $questions = $qcm->getQuestions();
        USession::set('questions_exam', $questions);
        USession::set('exam_id', $exam->getId());
        $this->nextQuestion();
    }

    /**
     *
     * @post('next','name'=>'exam.next')
     */
    public function nextQuestion(){
        $remainingQuestions = USession::getArray('questions_exam');
        $question = $remainingQuestions[0];
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            Startup::forward(Router::path('question.preview', [
                $question->getId()
            ]));
        } else {
            switch ($question->getIdTypeq()){
                case 1:
                    $userAnswer=$this->postMultipleAnswerData();
                case 2:
                    $userAnswer=$this->postMultipleAnswerData();
                case 3:
                    $userAnswer=$this->postSingleAnswerData();
            }
            $userAnswer->setQuestion($question);
            DAO::insert($userAnswer);
            unset($remainingQuestions[0]);
            if (count($remainingQuestions) > 0) {
                $remainingQuestions = array_values($remainingQuestions);
                $question = $remainingQuestions[0];
                USession::set('questions_exam', $remainingQuestions);
                $_SERVER['REQUEST_METHOD'] = 'GET';
                Startup::forward(Router::path('question.preview', [
                    $question->getId()
                ]));
            } else {
                $this->ExamEnd();
            }
        }
    }
    

    private function ExamEnd(){
        $this->jquery->exec('$("#btNext").css("display","none");',true);
        $bt=$this->jquery->semantic()->htmlButton('result','See result');
        $this->jquery->ajaxOnClick('#result', Router::path('Correction.myExam', [
            USession::get('exam_id'),
            USession::get('activeUser')['id']
        ]),'#response');
        $this->jquery->renderView('ExamController/end.html',);
    }
    
    /**
     * @get('oversee/{id}','name'=>'examStart')
     */
    public function ExamOverseePage($id){
        $exam=$this->loader->get($id);
        $this->jquery->renderView('ExamController/oversee.html',);
    }
    private function postMultipleAnswerData(){
        $userAnswer = new Useranswer();
        $userAnswer->setValue(json_encode(URequest::getDatas()));
        $userAnswer->setIdUser(USession::get('activeUser')['id']);
        $userAnswer->setIdExam(USession::get('exam_id'));
        return $userAnswer;
    }

    private function postSingleAnswerData(){
        $value = URequest::getDatas();
        $value['corrected'] = false;
        $value['points'] = 0;
        $userAnswer = new Useranswer();
        $userAnswer->setValue(json_encode($value));
        $userAnswer->setIdUser(USession::get('activeUser')['id']);
        $userAnswer->setIdExam(USession::get('exam_id'));
        return $userAnswer;
    }
}

