<?php
namespace controllers;

use models\Answer;
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
        $this->jquery->exec('
        $(".ui.sidebar").sidebar("setting", "transition", "overlay");
        var count=0;
        var ws = new WebSocket("ws:/127.0.0.1:2346");
        ws.onopen=function(){
            ws.send(\'{"exam":'.$id.',"idOwner":'.$qcm->getUser().',"user":'.\json_encode(USession::get('activeUser')).'}\');
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
        $("#submitMessage2").click(function(event){ws.send(\'{"id":'.USession::get('activeUser')['id'].',"exam":'.$id.',"target":'.$target.',"message":"\'+$("#message").val()+\'"}\');});
        $("#next").click(function(event){ws.send(\'{"exam":'.$id.',"id":'.USession::get('activeUser')['id'].',"target":"\'+target+\'","message":"\'+$("textarea[name=message]").val()+\'"}\');$(".cheat").modal("hide");$("textarea[name=message]").val("");event.stopPropagation();});
        ws.onmessage = function(e) {
            console.log(e.data);
           var obj=JSON.parse(e.data);
           if("message" in obj){
           $(\'#messages_box\').append(\'<div class="yours message"><div class="ui segment messagecontent ">\'+obj.message+\'</div><div class="messagecdate">\'+obj.cdate+\'</div>\');
           }
        };',true);
        $this->jquery->postFormOnClick("#btNext", Router::path('exam.next'), 'frmUserAnswer','#response',[
            'stopPropagation'=>false,
            'preventDefault'=>false,
            'before'=>'idPQ = $( "#IdQ" ).val();',
            'jsCallback'=>'$( "#IdPQ" ).val(idPQ);$("#IdPQ").trigger("change");'
        ]);
        $this->jquery->postOnClick('#post_message',Router::path('message.exam.post'),'{ message: $("#message").val(), target:'.$target.',exam:'.$id.' }','',[
            'jsCallback'=>'$( "#submitMessage2" ).trigger( "click" );$(\'#messages_box\').append(\'<div class="mine message"><div class="mine message"><div class="ui segment messagecontent ">\'+$("#message").val()+\'</div><div class="messagecdate">0000-00-00</div></div>\');
                            $("#message").val("");',

        ]);
        $this->jquery->execOn('click','#test','$(".ui.sidebar").sidebar("toggle");');
        $this->jquery->renderView('ExamController/exam.html',['idUser'=>USession::get('activeUser')['id'],'name'=>$qcm->getName(),'date'=>$date,'id'=>$id]);
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
                    $userAnswer=$this->postMultipleAnswerData(0,$question);
                    break;
                case 2:
                    $userAnswer=$this->postMultipleAnswerData(1,$question);
                    break;
                case 3:
                    $userAnswer=$this->postSingleAnswerData();
                    break;
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

    private function postMultipleAnswerData($type,$question){
        $userAnswer = new Useranswer();
        $val =\json_encode(URequest::getDatas());
        $val =\json_decode($val);
        $val->points=0;
        $userAnswer->setIdUser(USession::get('activeUser')['id']);
        $userAnswer->setIdExam(USession::get('exam_id'));
        if($type == 0){
            print_r($val);
            $answers = DAO::getAll(Answer::class,'idQuestion=?',false,[$question->getId()]);
            foreach($answers as $answer){
                foreach($val->userAnswer as $uAnswer){
                    if($uAnswer==$answer->getId()){
                        $val->points=$answer->getScore();
                        $val->corrected=true;
                    }
                }
            }
        }else{
            $val->corrected=false;
        }

        $userAnswer->setValue(\json_encode($val));
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