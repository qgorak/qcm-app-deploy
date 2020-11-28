<?php
namespace controllers;
use Ubiquity\utils\http\USession;
use Ubiquity\utils\http\URequest;
use Ubiquity\orm\DAO;
use models\User;
use Ubiquity\controllers\Startup;

/**
 * Auth Controller BaseAuthController
 */
class BaseAuthController extends \Ubiquity\controllers\auth\AuthController{   
    protected $headerView = "@activeTheme/main/vHeader.html";
    protected $footerView = "@activeTheme/main/vFooter.html";
    
    public function initialize() {
        if (! URequest::isAjax ()) {
            $this->loadView ( $this->headerView );
        }
    }
    
    public function finalize() {
        if (! URequest::isAjax ()) {
            $this->loadView ( $this->footerView );
        }
    }
    
    /**
     * @route("/login","name"=>"login")
     */
    public function index(){
      $this->loadDefaultView();
    }
    
    /**
     * @route("/register","name"=>"register")
     */
    public function register(){
        $this->loadDefaultView();
    }
    
    /**
     * @post("/new")
     */
    public function registerPost(){
        if(URequest::isPost()){
            if(DAO::getOne(User::class,"login = ? OR email = ?",true,[URequest::post("login"),URequest::post("email")])===null){
                $instance=new User();
                //$instance->set
                DAO::insert($instance);
                Startup::forward("_default",false,false);
            }
        }
    }
    
    protected function onConnect($connected) {
        $urlParts=$this->getOriginalURL();
        USession::set($this->_getUserSessionKey(), $connected);
        if(isset($urlParts)){
            $this->_forward(implode("/",$urlParts));
        }else{
            Startup::forward("_default");
        }
    }
    
    protected function _connect() {
        if(URequest::isPost()){
            $email=URequest::post($this->_getLoginInputName());
            $password=URequest::post($this->_getPasswordInputName());
            $user=DAO::getOne(User::class,"email = ?",true,[URequest::post('email')]);
            if($user!==null){
                if(URequest::post('password')==$user->getPassword()){
                    return $user;
                }
            }
        }
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