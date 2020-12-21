<?php
namespace controllers;

use models\Group;
use models\Question;
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

    public function initialize() {
        parent::initialize ();
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
        $this->jquery->renderView("DashboardController/index.html",[
            'nbQuestion'=>$countQuestion,
            'nbGroup'=>$countGroup
        ]);
    }


}
