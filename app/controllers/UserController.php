<?php
namespace controllers;

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
        $this->uiService->displayInfos();
    }
    
    /**
     * @route('/','name'=>'user')
     */
    public function index(){
        $this->displayMyInfo(USession::get('activeUser')['id']);
        $this->_index($this->jquery->renderView('UserController/display.html',[],true));
    }
    
    private function _index($response = '') {
        $this->jquery->renderView ( 'UserController/index.html', [
            'response' => $response
        ] );
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
        $this->jquery->renderView('UserController/display.html');
    }
}

