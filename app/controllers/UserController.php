<?php
namespace controllers;

use services\UserDAOLoader;
use Ubiquity\controllers\Router;
use Ubiquity\translation\TranslatorManager;
use Ubiquity\utils\http\USession;
use Ubiquity\utils\http\URequest;
use Ubiquity\security\acl\controllers\AclControllerTrait;


/**
 * Controller UserController
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
    
    /**
     *
     * @param \services\UserDAOLoader $loader
     */
    public function setLoader($loader) {
        $this->loader = $loader;
    }

    private function displayMyInfo($id){
        $user=$this->loader->get($id);
        $info=$this->jquery->semantic()->dataForm('myInfo',$user);
        $info->setFields([
            'language',
            'submitLang'
        ]);
        $info->setCaptions([
            TranslatorManager::trans('language',[],'main')
        ]);
        $info->setIdentifierFunction ( 'getId' );
        $info->fieldAsDropDown('language',['en_EN'=>'English','fr_FR'=>'Français']);
        $info->fieldAsSubmit('submitLang',null, Router::path('langSubmit'),'#response',[
            'value'=>TranslatorManager::trans('submitLang',[],'main')
        ]);
    }
    
    /**
     * @allow('role'=>'@USER')
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
     * @allow('role'=>'@USER')
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
    
    public function _getRole(){
        if(isset(USession::get('activeUser')['id'])){
            return '@USER';
        }
        return '@GUEST';
    }
}

