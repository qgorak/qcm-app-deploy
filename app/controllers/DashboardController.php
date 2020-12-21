<?php
namespace controllers;

use models\Exam;
use models\Group;
use models\Qcm;
use models\Question;
use models\Usergroup;
use services\DAO\ExamDAOLoader;
use services\UI\ExamUIService;
use Ubiquity\orm\DAO;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\http\UResponse;
use Ubiquity\utils\http\USession;
use models\Tag;
use models\User;
use Ubiquity\security\acl\controllers\AclControllerTrait;
/**
 * Controller DashboardController
 * @allow('role'=>'@USER')
 * @route('dashboard','inherited'=>true, 'automated'=>true)
 * @property \Ajax\php\ubiquity\JsUtils $jquery
 */
class DashboardController extends ControllerBase{
    use AclControllerTrait;

    private $uiService;
    /**
     *
     * @autowired
     * @var ExamDAOLoader
     */
    private $loader;

    /**
     *
     * @param \services\DAO\ExamDAOLoader $loader
     */
    public function setLoader($loader) {
        $this->loader = $loader;
    }

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

    /**
     * @route('/','name'=>'dashboard')
     */
    public function index(){
        $countQuestion=DAO::count(Question::class,'idUser=?',[USession::get('activeUser')['id']]);
        $countGroup=DAO::count(Group::class,'idUser=?',[USession::get('activeUser')['id']]);
        $countQcm=DAO::count(Qcm::class,'idUser=?',[USession::get('activeUser')['id']]);
        $countExam=DAO::uCount(Exam::class,'qcm.idUser = ?',[USession::get('activeUser')['id']]);
        $countUserInMyGroups=DAO::uCount(Usergroup::class,'group.idUser = ? and status=1',[USession::get('activeUser')['id']]);
        $examInProgress =$this->loader->allMyExamInProgress();
        $examComing =$this->loader->allMyComingExam();
        $dti = $this->uiService->displayMyExamsInProgress($examInProgress);
        $dtc = $this->uiService->displayMyComingExams($examComing);
        $this->jquery->renderView("DashboardController/index.html",[
            'nbQuestion'=>$countQuestion,
            'nbGroup'=>$countGroup,
            'nbQcm'=>$countQcm,
            'nbExam'=>$countExam,
            'nbUsers'=>$countUserInMyGroups
        ]);
    }


}
