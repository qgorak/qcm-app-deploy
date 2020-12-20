<?php
namespace mail;

use Ubiquity\mailer\MailerManager;

 /**
  * Mailer MailManager
  */
class MailManager extends \Ubiquity\mailer\AbstractMail {

    
    private $newPassword;
    
    public function getNewPassword(){
        return $this->newPassword;
    }

    public function setNewPassword($newPassword){
        $this->newPassword = $newPassword;
    }

    /**
	 *
	 * {@inheritdoc}
	 * @see \Ubiquity\mailer\AbstractMail::initialize()
	 */
	protected function initialize(){
		$this->subject = 'Reset password';
		$this->from(MailerManager::loadConfig()['user']??'from@organization');
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Ubiquity\mailer\AbstractMail::body()
	 */
	public function body() {
		return "Your new password is ".$this->getNewPassword();
	}
}