<?php
namespace controllers;
use Ubiquity\translation\TranslatorManager;
use Ubiquity\utils\http\USession;
use Ubiquity\utils\http\URequest;
use Ubiquity\orm\DAO;
use models\User;
use Ubiquity\translation\Translator;

/**
 * Auth Controller BaseAuthController
 */
class BaseAuthController extends \Ubiquity\controllers\auth\AuthController{   

    
    public function getSession()
    {
        return $this->session;
    }

    public function setSession($session)
    {
        $this->session = $session;
    }
    
    /**
     * @post("/login")
     */
    public function loginPost(){
        if(gettype($this->_connect())!=="string"){
            $this->onConnect($this->_connect());
            $info="You are logged";
            $process="success";
        }
        else{
            $info=$this->_connect();
            $process="error";
        }
        header('location:/');
        exit();
    }
    
    /**
     * @get("/terminate","name"=>"terminate")
     */
    public function terminate(){
        USession::terminate ();
        header('location:/');
        exit();
    }
    
    /**
     * @post("/register")
     */
    public function registerPost(){
        if(URequest::isPost()){
            if(DAO::getOne(User::class,"email = ?",true,[URequest::post("email")])===null){
                $instance=new User();
                URequest::setValuesToObject($instance);
                $instance->setPassword(URequest::post('password'));
                DAO::insert($instance);
                header('location:/');
                exit();
            }
        }
    }
    
    protected function onConnect($connected) {
        $urlParts=$this->getOriginalURL();
        USession::set($this->_getUserSessionKey(), $connected);
        if(isset($urlParts)){
            $this->_forward(implode("/",$urlParts));
        }
    }
    
    protected function _connect() {
        if(URequest::isPost()){
            $user=DAO::getOne(User::class,"email = ?",true,[URequest::post('email')]);
            if($user!==null){
                if(URequest::post('password')==$user->getPassword()){
                    return ["id"=>$user->getId(),"email"=>$user->getEmail(),"firstname"=>$user->getFirstname(),"lastname"=>$user->getLastname(),'language'=>$user->getLanguage()];
                }
                else{
                    return "Wrong password !";
                }
            }
            return "Wrong email !";
        }
        return "Error !";
    }
    
    /**
     * {@inheritDoc}
     * @see \Ubiquity\controllers\auth\AuthController::isValidUser()
     */
    public function _isValidUser($action=null) {
        return USession::exists($this->_getUserSessionKey());
    }
    
    public function _getBaseRoute() {
        return 'BaseAuthController';
    }
}