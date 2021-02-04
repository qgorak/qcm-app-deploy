<?php
namespace controllers;
use Google_Client;
use Google_Service_Oauth2;
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
        $this->jquery->attr('#google','href','/logingoogle',true);
        $this->jquery->getHref('#reset','#responseauth');
        $this->jquery->renderView('BaseAuthController/login.html');
    }

    /**
     * @get("/logingoogle",'name'=>'logingoogle')
     */
    public function logingoogle(){
        $client = new Google_Client();
        $client->setAuthConfig(__DIR__ . '/googleapikey/client_secret.json');
        $client->setRedirectUri('http://127.0.0.1:8090/logingoogle');
        $client->addScope("email");
        $client->addScope("profile");
        if (isset($_GET['code'])) {
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
            $client->setAccessToken($token['access_token']);
            $google_oauth = new Google_Service_Oauth2($client);
            $google_account_info = $google_oauth->userinfo->get();
            $this->checkGoogleAccount($google_account_info);
        }else{
            header('location: '.$client->createAuthUrl());
        }
    }


    /**
     * @post("/login",'name'=>'loginPost')
     */
    public function loginPost(){
        $info=$this->_connect();
        if(!\array_key_exists('error',$info)){
            $this->onConnect($this->_connect());
            $this->jquery->clear_compile();
            echo 'logged';
            if(Usession::get('redirect')){
                USession::delete('redirect');
            }
        }
        else{
            $this->uiService->loginForm();
            $this->uiService->loginErrorMessage($info['error'],'x');
            $this->jquery->renderView('BaseAuthController/login.html');
        }
    }

    private function checkGoogleAccount($google_account_info){
        $user=DAO::getOne(User::class,"email = ?",false,[$google_account_info->email]);
        if($user!==null){
            USession::set($this->_getUserSessionKey(),["id"=>$user->getId(),"email"=>$user->getEmail(),"firstname"=>$user->getFirstname(),"lastname"=>$user->getLastname(),'language'=>$user->getLanguage(),'avatar'=>$user->getAvatar()]);
        }else{
            $user = new User();
            $user->setEmail($google_account_info->email);
            $user->setFirstname($google_account_info->given_name);
            $user->setLastname($google_account_info->family_name);
            $user->setLanguage(str_replace('-','_',URequest::getDefaultLanguage()));
            $user->setPassword($this->randomPassword());
            $user = DAO::insert($user);
            $user = DAO::getOne(User::class,"email = ?",false,[$user->getEmail()]);
            USession::set($this->_getUserSessionKey(),["id"=>$user->getId(),"email"=>$user->getEmail(),"firstname"=>$user->getFirstname(),"lastname"=>$user->getLastname(),'language'=>$user->getLanguage(),'avatar'=>$user->getAvatar()]);
        }
        \header('Location: /');
        exit();
    }

    protected function onConnect($connected) {
        USession::set($this->_getUserSessionKey(), $connected);
    }

    protected function _connect() {
        if(URequest::isPost()){
            $user=DAO::getOne(User::class,"email = ?",false,[URequest::post('email')]);
            if($user!==null){
                if (\password_verify(URequest::post('password'),$user->getPassword())){
                    if($user->getConfirmed()=="1"){
                        $initials = $user->getFirstname()[0].$user->getLastname()[0];
                        return ["id"=>$user->getId(),"email"=>$user->getEmail(),"firstname"=>$user->getFirstname(),"lastname"=>$user->getLastname(),'language'=>$user->getLanguage(),'avatar'=>$user->getAvatar(),'initials'=>$initials];
                    }
                    else{
                        return ['error'=>'Your account isn\'t verified, please check your email'];
                    }
                }
                else{
                    return ['error'=>'Wrong password !'];
                }
            }
            return ['error'=>'Wrong email !'];
        }
        return ['error'=>'Error !'];
    }

    /**
     * @get("/registerForm",'name'=>'registerform')
     */
    public function registerform(){
        $this->uiService->registerForm();
        $this->jquery->renderView('BaseAuthController/register.html');
    }

    /**
     * @post("/register",'name'=>'registerPost')
     */
    public function registerPost(){
        if(DAO::getOne(User::class,"email = ?",true,[URequest::post("email")])===null){
            $instance=new User();
            URequest::setValuesToObject($instance);
            if(URequest::password_hash('password')){
                $instance->setPassword(URequest::post('password'));
            }
            $confirmedId=$instance->getFirstname().\uniqid();
            $instance->setConfirmed($confirmedId);
            $colors = ["#1abc9c", "#2ecc71", "#3498db", "#9b59b6", "#34495e", "#16a085", "#27ae60", "#2980b9", "#8e44ad", "#2c3e50", "#f1c40f", "#e67e22", "#e74c3c", "#95a5a6", "#f39c12", "#d35400", "#c0392b", "#bdc3c7", "#7f8c8d"];
            $color = $colors[\array_rand($colors)];
            $instance->setAvatar($color);
            DAO::insert($instance);
            $mail = new MailManager();
            $mail->to($instance->getEmail());
            $mail->subject = 'Welcome to QCM';
            $link=Router::path('confirmUser',[$confirmedId]);
            $mail->setBody('Please follow this link to confirm you account <a href="http://127.0.0.1:8090/'.$link.'">Activez votre compte</a>');
            MailerManager::send($mail);
            $this->uiService->loginErrorMessage('You successfully registered, please check your mail to verify your account','check');
        }
        else{
            $this->uiService->registerForm();
            $this->uiService->loginErrorMessage('This email already exist !','x');
        }
        $this->jquery->renderView('BaseAuthController/register.html');
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
     * @get("/resetForm",'name'=>'resetForm')
     */
    public function resetPasswordForm(){
        $this->uiService->resetPasswordForm();
        $this->jquery->renderView('BaseAuthController/reset.html');
    }

    /**
     * @post('/resetPassword','name'=>'resetPassword')
     */
    public function resetPassword() {
        $user=$this->loader->getByEmail(URequest::post('email'));
        if($user!=null){
            $mail = new MailManager();
            $mail->to(URequest::post('email'));
            $mail->subject = 'Reset password';
            $newPassword=$this->randomPassword();
            $user->setPassword(\password_hash($newPassword,PASSWORD_DEFAULT));
            $mail->setBody("Your new password is ".$newPassword);
            if (MailerManager::send($mail)) {
                $this->loader->update($user);
                $this->uiService->loginErrorMessage('Your new password has been sent','check');
            }
            else{
                $this->uiService->loginErrorMessage('Error sending the message','x');
                $this->uiService->resetPasswordForm();
                $this->jquery->renderView('BaseAuthController/reset.html');
            }
        }
        else{
            $this->uiService->loginErrorMessage('This email doesn\'t exist','x');
            $this->uiService->resetPasswordForm();
            $this->jquery->renderView('BaseAuthController/reset.html');
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
            $n = \rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return \implode($pass); //turn the array into a string
    }
}