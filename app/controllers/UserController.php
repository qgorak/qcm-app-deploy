<?php
namespace controllers;

use models\Group;
use models\Usergroup;
use Ubiquity\orm\DAO;
use Ubiquity\security\acl\controllers\AclControllerTrait;
use Ubiquity\translation\TranslatorManager;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\http\USession;
use services\DAO\UserDAOLoader;
use services\UI\UserUIService;


/**
 * Controller UserController
 * @allow('role'=>'@USER')
 * @route('user','inherited'=>true,'automated'=>true)
 * @property \Ajax\php\ubiquity\JsUtils $jquery
 */
class UserController extends ControllerBase{
    use AclControllerTrait;
    
    /**
     *
     * @autowired
     * @var UserDAOLoader
     */
    private $loader;
    private $uiService;
    
    public function initialize(){
        parent::initialize();
        $this->uiService = new UserUIService( $this->jquery );
    }
    
    /**
     *
     * @param \services\DAO\UserDAOLoader $loader
     */
    public function setLoader($loader) {
        $this->loader = $loader;
    }

    private function displayMyInfo($id){
        $user=$this->loader->get($id);
        $user->code_style='default';
        $this->uiService->displayInfos($user);
    }
    private function displayMyDashboard($id){
        $waitGroups =$this->loader->waitGroups();
        $groups =$this->loader->inGroups();
        $exams =$this->loader->getPastExam();
        $this->uiService->displayDashboard($groups,$waitGroups,$exams);
    }
    
    /**
     * @route('/','name'=>'user')
     */
    public function index(){
        $this->displayMyInfo($userId = USession::get('activeUser')['id']);
        $this->jquery->renderView('UserController/settings.html',[]);
    }

    /**
     * @route('dashboard','name'=>'studentDashboard')
     */
    public function studentDashboard(){
        $this->displayMyDashboard($userId = USession::get('activeUser')['id']);
        $this->jquery->renderView('UserController/studentDashboard.html',[]);
    }

    /**
     * @post('lang','name'=>'langSubmit')
     */
    public function langSubmit(){
        $user=$this->loader->get(USession::get('activeUser')['id']);
        $user->setLanguage(URequest::post('language'));
        $this->loader->update($user);
        $this->displayMyInfo(USession::get('activeUser')['id']);
        TranslatorManager::setLocale($user->getLanguage());
        USession::set('activeUser',["id"=>$user->getId(),"email"=>$user->getEmail(),"firstname"=>$user->getFirstname(),"lastname"=>$user->getLastname(),'language'=>$user->getLanguage()]);
        $this->jquery->renderView('UserController/settings.html');
    }
}