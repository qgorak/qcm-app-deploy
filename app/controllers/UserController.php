<?php
namespace controllers;

use services\UserDAOLoader;
use Ubiquity\controllers\Router;
use Ubiquity\translation\TranslatorManager;
use Ubiquity\utils\http\USession;
use Ubiquity\utils\http\URequest;


/**
 * Controller UserController
 * @route('user','inherited'=>true,'automated'=>true)
 * @property \Ajax\php\ubiquity\JsUtils $jquery
 */
class UserController extends ControllerBase{
    
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
        $info->fieldAsDropDown('language',['en_EN'=>'en_EN','fr_FR'=>'fr_FR']);
        $info->fieldAsSubmit('submitLang',null, Router::path('langSubmit'),'#response',[
            'value'=>TranslatorManager::trans('submitLang',[],'main')
        ]);
    }
    
    /**
     *
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
        $this->jquery->renderView('UserController/display.html');
    }
}
