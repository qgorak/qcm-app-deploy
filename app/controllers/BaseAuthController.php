<?php
namespace controllers;
use Ubiquity\orm\DAO;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\http\USession;
use models\User;
use services\UI\AuthUIService;

/**
 * Auth Controller BaseAuthController
 */
class BaseAuthController extends \Ubiquity\controllers\auth\AuthController{
    
    private $uiService;
    
    public function initialize(){
        $this->uiService = new AuthUIService ( $this->jquery );
    }
    
    public function getSession()
    {
        return $this->session;
    }

    public function setSession($session)
    {
        $this->session = $session;
    }
    
    /**
     * @get("/loginForm",'name'=>'loginform')
     */
    public function loginform(){
        $this->uiService->loginForm();
        $this->jquery->renderView('BaseAuthController/login.html',[]);

    }
    
    /**
     * @get("/registerForm",'name'=>'registerform')
     */
    public function registerform(){
        $this->uiService->registerForm();
        $this->jquery->renderView('BaseAuthController/register.html',[]);
        
    }
    
    /**
     * @post("/login",'name'=>'loginPost')
     */
    public function loginPost(){
        $this->uiService->loginForm();
        $info=$this->_connect();
        $this->uiService->loginErrorMessage($info);
        if(gettype($this->_connect())!=="string"){
            $this->onConnect($this->_connect());
            $this->jquery->clear_compile();
            echo 'logged';
        }else{
            $this->jquery->renderView('BaseAuthController/login.html',[]);
        }
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
     * @post("/register",'name'=>'registerPost')
     */
    public function registerPost(){
        if(URequest::isPost()){
            if(DAO::getOne(User::class,"email = ?",true,[URequest::post("email")])===null){
                $instance=new User();
                URequest::setValuesToObject($instance);
                $instance->setPassword(URequest::post('password'));
                DAO::insert($instance);
                $this->uiService->loginErrorMessage('Success');
                $this->jquery->renderView('BaseAuthController/register.html',[]);
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
            $user=DAO::getOne(User::class,"email = ?",false,[URequest::post('email')]);
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
    
    public function _insertJquerySemantic(){
        return false;
    }
    public function _displayInfoAsString(){
        return true;
    }
}