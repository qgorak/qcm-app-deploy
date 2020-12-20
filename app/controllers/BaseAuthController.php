<?php
namespace controllers;
use Ubiquity\controllers\Router;
use Ubiquity\mailer\MailerManager;
use Ubiquity\orm\DAO;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\http\USession;
use models\User;
use services\UI\AuthUIService;
use mail\MailManager;
use services\DAO\UserDAOLoader;

/**
 * Auth Controller BaseAuthController
 */
class BaseAuthController extends \Ubiquity\controllers\auth\AuthController{
    
    /**
     *
     * @autowired
     * @var UserDAOLoader
     */
    private $loader;
    private $uiService;
    
    /**
     *
     * @param \services\DAO\UserDAOLoader $loader
     */
    public function setLoader($loader) {
        $this->loader = $loader;
    }
    
    public function initialize(){
        $this->uiService = new AuthUIService ( $this->jquery );
        MailerManager::start();
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
        if(\gettype($this->_connect())!=="string"){
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
        \header('location:/');
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
                if(URequest::password_hash('password')){
                    $instance->setPassword(URequest::post('password'));
                }
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
            $this->_forward(\implode("/",$urlParts));
        }
    }
    
    protected function _connect() {
        if(URequest::isPost()){
            $user=DAO::getOne(User::class,"email = ?",false,[URequest::post('email')]);
            if($user!==null){
                if (password_verify(URequest::post('password'),$user->getPassword())){
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
     * @get("/resetForm",'name'=>'resetForm')
     */
    public function resetPasswordForm(){
        $this->uiService->resetPasswordForm();
        $this->jquery->renderView('BaseAuthController/reset.html',[]);
    }
    
    /**
     * @post('/resetPassword','name'=>'resetPassword')
     */
    public function resetPassword() {
        $user=$this->loader->getByEmail(URequest::post('email'));
        if($user!=null){
            $mail = new MailManager();
            $mail->to(URequest::post('email'));
            if (MailerManager::send($mail)) {
                $newPassword=$this->randomPassword();
                $user->setPassword(password_hash($newPassword,PASSWORD_DEFAULT));
                $this->loader->update($user);
                $mail->body();
                echo 'Your new password has been sent';
            } else {
                echo 'Error sending the message';
            }
        }
        else{
            echo 'This email doesn\t exist';
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
    
    public function _insertJquerySemantic(){
        return false;
    }
    public function _displayInfoAsString(){
        return true;
    }
    
    private function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890*//^&;:!';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 10; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
}