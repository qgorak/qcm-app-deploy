<?php
namespace controllers;

use models\Message;
use models\Question;
use models\User;
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
    private $uiService;
    
    public function initialize() {
        parent::initialize ();
        $this->uiService = new ExamUIService( $this->jquery );
        if (! URequest::isAjax ()) {
            $this->loadView('/main/UI/trainerNavbar.html');
            $this->jquery->getHref ( '.trainermenu', '#response', [
                'hasLoader' => 'internal'
            ] );
        }
    }
    public function finalize() {
        if (! URequest::isAjax ()) {
            $this->loadView('/main/UI/closeColumnCloseMenu.html');
        }
        parent::finalize();
    }
    
    /**
     *
     * @param \services\DAO\ExamDAOLoader $loader
     */
    public function setLoader($loader) {
        $this->loader = $loader;
    }
    
    private function displayMyExam() {
        $exam=$this->loader->my();
        $this->uiService->displayMyExams($exam);
    }
    
    /**
     * @route('/','name'=>'exam')
     */
    public function index(){}
    
    private function _index($response = '') {
        $this->jquery->renderView ( 'ExamController/index.html', [
            'response' => $response
        ] );
    }

    /**
     * @get('get/{id}','name'=>'exam.report')
     */
    public function get($id){
        $exam=$this->loader->get($id);
        $userScores = $this->loader->getExamSuccessRate($id);
        $this->jquery->ajaxOn('click','._element',Router::path('exam.overseeuser',[$exam->getId()]),'#response-getexam',['hasLoader'=>false,'attr'=>'data-ajax']);
        $this->uiService->usersDataTable($exam);
        $succesRate = ($userScores[1] / $userScores[2]) * 100;
        $presentRate = ($userScores[4] / $userScores[2]) * 100;
        $paperdone=$userScores[4]-$userScores[5];
        if($userScores[4]!= 0){
            $correctionRate = ($paperdone /$userScores[4]) * 100;
        }else{
            $correctionRate=0;
        }
        $this->jquery->ajaxOnClick('#cancelitem',Router::path('dashboard'),'#response',[
            'hasLoader'=>false
        ]);
        $this->jquery->renderView('ExamController/get.html',['success'=>$userScores[1],
            'successRate'=>$succesRate.' ',
            'failed'=>$userScores[0],
            'presentRate'=>$presentRate,
            'count'=>$userScores[4],
            'missing'=>$userScores[3],
            'paperleft'=>$userScores[5],
            'paperdone'=>$paperdone,
            'correctionRate'=>$correctionRate]);
    }

    
    /**
     * @get('add','name'=>'examAdd')
     */
    public function add(){
        $qcm=$this->loader->allMyQCM();
        $groups=$this->loader->allMyGroup();
        $this->uiService->examForm($qcm,$groups);
        $this->jquery->renderView('ExamController/add.html',['lang'=>USession::get('activeUser')['language']]);
    }
    
    /**
     * @post('add','name'=>'examAddSubmit')
     */
    public function addSubmit(){
        $lang=USession::get('activeUser')['language'];
        $exam=new Exam();
        $dated=\str_replace(',','',URequest::post('dated'));
        $datef=\str_replace(',','',URequest::post('datef'));
        if($lang=='fr_FR'){
            $translator = new datePickerTranslator();
            $dated=$translator->translate($dated);
            $datef=$translator->translate($datef);
        }
        $dated = new DateTime($dated);
        $datef = new DateTime($datef);
        $exam->setDated(\date_format($dated,'Y-m-d H:i'));
        $exam->setDatef(\date_format($datef,'Y-m-d H:i'));
        $exam->setQcm(DAO::getById(Qcm::class,URequest::post('idQcm'),false));
        $exam->setGroup(DAO::getById(Group::class,URequest::post('idGroup'),false));
        $exam->setOptions(URequest::post('options'));
        DAO::save($exam);
        $this->displayMyExam();
        $this->jquery->renderView('ExamController/display.html');
    }

    
    /**
     * @get('oversee/{id}/','name'=>'exam.oversee')
     */
    public function ExamOverseePage($id){
        $exam = $this->loader->get($id);
        $qcm = $exam->getQcm();

        $idOwner = $qcm->getUser();
        $this->jquery->exec('createTimer('.\intval(\strtotime($exam->getDatef())-\strtotime(\date("Y-m-d H:i:s"))).',"#timer-exam","'.Router::path('exam.get',[$id]).'")',true);
        $this->jquery->exec('
        var obj;var count=0;
        var ws = new WebSocket("ws:/127.0.0.1:2346");
        ws.onopen=function(){
            ws.send(\'{"exam":'.$id.',"idOwner":'.$idOwner.',"id":'.USession::get('activeUser')['id'].'}\');
            ws.send(\'{"exam":'.$id.',"id":'.USession::get('activeUser')['id'].',"action":"getuser"}\');
        };
        ws.onmessage = function(e) {
            console.log(e.data);
            obj=JSON.parse(e.data);
            if("cheat" in obj){
                var index="#OverseeUserDt-icon-"+obj.user.id;
                $(index).addClass("exclamation triangle");
                var index2="#OverseeUserDt-tr-"+obj.user.id;
                $(index2).addClass("red");
                console.log(obj);
                if($("#console").val()==1){
                
                    $("#logs").append(obj.date+" "+obj.user.firstname+" "+obj.user.lastname+" cheated "+obj.cheat+" time <br>");
                    hljs.initHighlighting.called = false;
	                hljs.initHighlighting();
                }
            }
            if(("idPQ" in obj) && ($("#idUser").val() == obj.user.id)){
             var index="#OverseeUserDt-icon-"+obj.user.id;
             $("#idNewQ").val(obj.idPQ);
             $("#idNewQ").trigger("change");
            }
            if("usersLogged" in obj){
                $( "._element" ).each(function( index ) {
                    child = index+1;
                    x = $("._element:nth-child("+child+")").attr("data-ajax");
                    y = $("._element:nth-child("+child+")").attr("id");
                    var n = obj.usersLogged.indexOf(parseInt(x));
                    var index="#OverseeUserDt-label-"+$("._element:nth-child("+child+")").attr("data-ajax");
                    if(n!==-1){
                        $("#"+y+" .status").attr("class","ui status empty green circular label");
                    }else{
                        $("#"+y+" .status").attr("class","status ui grey empty circular label");
                    }
                })
            }
        };
        $("#cancelMessage").click(function(){$(".cheat").modal("hide");});
        function sendMessage(target){

        $("#submitMessage").click(function(event){ws.send(\'{"exam":'.$id.',"id":'.USession::get('activeUser')['id'].',"target":"\'+target+\'","message":"\'+$("textarea[name=message]").val()+\'"}\');$(".cheat").modal("hide");$("textarea[name=message]").val("");event.stopPropagation();});
        
        }
		$("#logs_console").scrollTop($("#logs_console")[0].scrollHeight);
		hljs.initHighlighting.called = false;
	    hljs.initHighlighting();
	     $("#submitMessage").click(function(event){ws.send(\'{"id":'.USession::get('activeUser')['id'].',"exam":'.$id.',"target":"\'+$("#idUser").val()+\'","message":"\'+$("#message").val()+\'"}\');});

		
					
		

        
        ',true);
        $this->jquery->execOn('click','#btn_reduce','console.log($("#deck").position());$("#console_window").addClass("reduced");$("#console_window").animate({ left:$("#deck").position().left+220 ,top:$("#deck").position().top});setTimeout(function() {$("#buttonconsole").addClass("active");}, 450);');
        $upTwo = dirname(__DIR__, 2);
        $logs = fopen($upTwo.'/exam_logs/exam_'.$id.'.log', "r");
        $txtlogs='';
        if ($logs) {
            while (($line = fgets($logs)) !== false) {
                $line = explode('`',$line);
                $txtlogs = $txtlogs.$line[1].'<br>';
            }

            fclose($logs);
        } else {
            // error opening the file.
        }
        $qcm = DAO::getById(Qcm::class,$qcm->getId(),true);
        $countq=count($qcm->getQuestions());
        $this->uiService->usersDataTable($exam);
        $group=DAO::getOne(Group::class,'keyCode=?',false,[$exam->getGroup()]);
        $this->jquery->execOn('click','#buttonconsole','rightmenu = document.getElementById("right_menu");$("#console_window").removeClass("reduced");$("#console_window").animate({ left: (window.innerWidth / 2)-($("#console_window").width()/2)-rightmenu.offsetWidth ,top:(window.innerHeight / 2)-$("#console_window").height()/2});$("#buttonconsole").removeClass("active");');
        $this->jquery->ajaxOn('click','._element',Router::path('exam.overseeuser',[$exam->getId()]),'#response-overseeuser',['hasLoader'=>false,'attr'=>'data-ajax']);
        $this->jquery->renderView('ExamController/oversee.html',['group'=>$group->getName(),'logs'=>$txtlogs,'countQ'=>$countq]);

    }

    /**
     * @get('overseeuser/{idExam}/{idUser}','name'=>'exam.overseeuser')
     */
    public function ExamOverseeUser($idExam,$idUser){
        $exam = $this->loader->get($idExam);
        $countAnswer = DAO::count(Useranswer::class,'idExam = ? AND idUser = ?',[$exam->getId(),$idUser]);
        $this->jquery->ajax('get',Router::path('liveresult.exam',[$idExam,$idUser]),'#answers_accordion');
        $this->jquery->ajaxOn('change','#idNewQ',Router::path('liveresult.correctq',[$idExam,$idUser]),'',[
            'jsCallback'=>'$("#accordion3").append(data);$("#countUA").val(parseInt($("#countUA").val())+1);
                           per =parseFloat(parseInt($("#countUA").val(), 10) * 100)/ parseInt($("#countQ").val(), 10);
                           $("#Progression").progress({percent: per});
                           if(per == 100){
                                $("#lbl-Progression").html(" ");
                               $("#msgcompleted").removeClass("hidden");
                            }else{
                                $("#lbl-Progression").html(per+"% of Completion");
                             };',
            'attr'=>'value'
        ]);
        $messages = DAO::getAll(Message::class,'(idUser=? and idTarget=?) or (idUser=? and idTarget=?) and idExam=?',false,[USession::get('activeUser')['id'],$idUser,$idUser,USession::get('activeUser')['id'],$idExam]);
        $this->jquery->postOnClick('#post_message',Router::path('message.exam.post'),'{ message: $("#message").val(), target:'.$idUser.',exam:'.$idExam.' }','',[
            'jsCallback'=>'$( "#submitMessage" ).trigger( "click" );$(\'#messages_box\').append(\'<div class="ui segment">\'+$("#message").val()+\'</div>\');
                            $("#message").val("");'
        ]);
        $this->jquery->ajaxOnClick('#cheat_tab',Router::path('exam.overseecheatuser',[$idExam,$idUser]),'#response-cheat');
        $this->jquery->renderView('ExamController/overseeuser.html',['idUser'=>$idUser,'countUA'=>$countAnswer,'messages'=>$messages]);
    }

    /**
     * @get('overseecheatuser/{idExam}/{idUser}','name'=>'exam.overseecheatuser')
     */
    public function ExamOverseeCheatUser($idExam,$idUser){
        $exam = $this->loader->get($idExam);
        $upTwo = dirname(__DIR__, 2);
        $logs = fopen($upTwo.'/exam_logs/exam_'.$idExam.'.log', "r");
        $txtlogs='';
        if ($logs) {
            while (($line = fgets($logs)) !== false) {
                $line = explode('`',$line);
                if($line[0]==$idUser){
                    $txtlogs = $txtlogs.$line[1].'<br>';
                }
            }
            fclose($logs);
        } else {
            // error opening the file.
        }
        $this->jquery->renderView('ExamController/cheatuser.html',['idUser'=>$idUser,'logs'=>$txtlogs]);
    }

    /**
     * @get('console/{idExam}','name'=>'exam.console')
     */
    public function ExamConsole($idExam){
        $upTwo = dirname(__DIR__, 2);
        $logs = fopen($upTwo.'/exam_logs/exam_'.$idExam.'.log', "r");
        $txtlogs='';
        if ($logs) {
            while (($line = fgets($logs)) !== false) {
                $line = explode('`',$line);
                $txtlogs = $txtlogs.$line[1].'<br>';
            }

            fclose($logs);
        } else {
            // error opening the file.
        }
        $this->jquery->renderView('ExamController/console.html',['logs'=>$txtlogs]);
    }

    /**
     * @get('group/{idGroup}','name'=>'exam.group')
     */
    public function ExamGroup($idGroup){
        $examsinp = $this->loader->allMyExamGroupInProgress($idGroup);
        $examscom = $this->loader->allMyGroupComingExam($idGroup);
        $examspast = $this->loader->allMyGroupPastExam($idGroup);
        $dt = $this->uiService->displayMyExamsInProgress($examsinp);
        $dt2 = $this->uiService->displayMyComingExams($examscom);
        $dt3 = $this->uiService->displayMyPastExams($examspast);
        $this->jquery->ajaxOnClick('#cancelitem',Router::path('group'),'#response',[
            'hasLoader'=>false
        ]);
        $this->jquery->renderView('ExamController/examgroup.html',[]);
    }

}