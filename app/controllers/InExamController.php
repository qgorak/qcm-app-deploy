<?php
namespace controllers;

use Ubiquity\controllers\Router;
use Ubiquity\controllers\Startup;
use Ubiquity\orm\DAO;
use Ubiquity\security\acl\controllers\AclControllerTrait;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\http\USession;
use DateTime;
use models\Exam;
use models\Group;
use models\Qcm;
use services\DAO\ExamDAOLoader;
use models\Useranswer;
use services\datePickerTranslator;
use services\UI\ExamUIService;

/**
 * Controller inExamController
 * @allow('role'=>'@USER')
 * @route('inexam','inherited'=>true,'automated'=>true)
 * @property \Ajax\php\ubiquity\JsUtils $jquery
 */
class inExamController extends ControllerBase{
    use AclControllerTrait;

    /**
     *
     * @autowired
     * @var ExamDAOLoader
     */
    private $loader;
    private $uiService;

    public function initialize() {
        parent::initialize ();
        $this->uiService = new ExamUIService( $this->jquery );
    }

    /**
     *
     * @param \services\DAO\ExamDAOLoader $loader
     */
    public function setLoader($loader) {
        $this->loader = $loader;
    }

    public function index() {}

    /**
     * @get('get/{id}','name'=>'exam.get')
     */
    public function getExam($id){
        $exam=$this->loader->get($id);
        $qcm=$exam->getQcm();
        $target=$qcm->getUser();
        $date=$exam->getDated();
        $this->jquery->getOnClick('#startExam', Router::path('exam.start',['']),'#response',[
            'attr'=>'data-ajax',
            'jsCallback'=>'$("#btNext").css("display","block");'
        ]);
        $bt=$this->jquery->semantic()->htmlButton('btNext','next');
        $bt->addToProperty('style', 'display:none;');
        $this->jquery->exec('var count=0;
        var ws = new WebSocket("ws:/127.0.0.1:2346");
        ws.onopen=function(){
            ws.send(\'{"exam":'.$id.',"idOwner":'.$qcm->getUser().',"id":'.USession::get('activeUser')['id'].'}\');
        };
        $(window).on("blur focus", function (e) {
        var prevType = $(this).data("prevType");
        if (prevType != e.type) {
    		if (e.type=="blur"){
            count++;
            ws.send(\'{"exam":'.$id.',"user":'.\json_encode(USession::get('activeUser')).',"target":'.$target.',"cheat":\'+count+\'}\');
        }
    	}
        $(this).data("prevType", e.type);
        });
        $( "#IdPQ" ).change(function() {
            idPQ = $( "#IdPQ" ).val();
            ws.send(\'{"exam":'.$id.',"user":'.\json_encode(USession::get('activeUser')).',"target":'.$target.',"idPQ": \'+idPQ+\'}\');
        });
        $("#next").click(function(event){ws.send(\'{"exam":'.$id.',"id":'.USession::get('activeUser')['id'].',"target":"\'+target+\'","message":"\'+$("textarea[name=message]").val()+\'"}\');$(".cheat").modal("hide");$("textarea[name=message]").val("");event.stopPropagation();});
        ws.onmessage = function(e) {
            console.log(e.data);
           var obj=JSON.parse(e.data);
           if("message" in obj){
            alert(obj.message);
           }
        };',true);
        $this->jquery->postFormOnClick("#btNext", Router::path('exam.next'), 'frmUserAnswer','#response',[
            'stopPropagation'=>false,
            'preventDefault'=>false,
            'before'=>'idPQ = $( "#IdQ" ).val();',
            'jsCallback'=>'$( "#IdPQ" ).val(idPQ);$("#IdPQ").trigger("change");'
        ]);
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
            if (\count($remainingQuestions) > 0) {
                $remainingQuestions = \array_values($remainingQuestions);
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
        $this->jquery->semantic()->htmlButton('result','See result');
        $this->jquery->ajaxOnClick('#result', Router::path('Correction.myExam', [
            USession::get('exam_id'),
            USession::get('activeUser')['id']
        ]),'#response');
        $this->jquery->renderView('ExamController/end.html',);
    }

    private function postMultipleAnswerData(){
        $userAnswer = new Useranswer();
        $userAnswer->setValue(\json_encode(URequest::getDatas()));
        $userAnswer->setIdUser(USession::get('activeUser')['id']);
        $userAnswer->setIdExam(USession::get('exam_id'));
        return $userAnswer;
    }

    private function postSingleAnswerData(){
        $value = URequest::getDatas();
        $value['corrected'] = false;
        $value['points'] = 0;
        $value['comment'] = '';
        $userAnswer = new Useranswer();
        $userAnswer->setValue(\json_encode($value));
        $userAnswer->setIdUser(USession::get('activeUser')['id']);
        $userAnswer->setIdExam(USession::get('exam_id'));
        return $userAnswer;
    }
}