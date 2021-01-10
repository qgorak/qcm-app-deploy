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
        $this->jquery->attr('#trainermode','class','item active',true);
        if (! URequest::isAjax ()) {
            $this->loadView('/main/UI/trainerNavbar.html');
            $this->jquery->getHref ( '.trainermenu', '#response', [
                'hasLoader' => 'internal'
            ] );
        }
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
            }
            
            if("usersLogged" in obj){
                $( "._element" ).each(function( index ) {
                    child = index+1;
                    x = $("._element:nth-child("+child+")").attr("data-ajax");
                    var n = obj.usersLogged.indexOf(parseInt(x));
                    var index="#OverseeUserDt-label-"+$("._element:nth-child("+child+")").attr("data-ajax");
                    if(n!==-1){
                        $(index).attr("class","ui green empty circular label");
                    }else{
                        $(index).attr("class","ui empty grey circular label disabled");
                    }
                })
            }
        };
        $("#cancelMessage").click(function(){$(".cheat").modal("hide");});
        function sendMessage(target){
        $( "#submitMessage" ).unbind( "click" );
        $("#submitMessage").click(function(event){ws.send(\'{"exam":'.$id.',"id":'.USession::get('activeUser')['id'].',"target":"\'+target+\'","message":"\'+$("textarea[name=message]").val()+\'"}\');$(".cheat").modal("hide");$("textarea[name=message]").val("");event.stopPropagation();});
        
        }
        ',true);
        $this->uiService->OverseeUsersDataTable($exam);
        $this->jquery->renderView('ExamController/oversee.html');
    }

    /**
     * @get('overseeuser/{idExam}/{idUser}','name'=>'exam.overseeuser')
     */
    public function ExamOverseeUser($idExam,$idUser){
        $exam = $this->loader->get($idExam);
        $countAnswer = DAO::count(Useranswer::class,'idExam = ? AND idUser = ?',[$exam->getId(),$idUser]);
        $this->jquery->ajax('get',Router::path('Correction.myExam',[$idExam,$idUser]),'#answers_accordion');
        $this->jquery->renderView('ExamController/overseeUser.html',[]);
    }

}