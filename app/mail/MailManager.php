<?php
namespace mail;

use Ubiquity\mailer\MailerManager;

 /**
  * Mailer MailManager
  */
class MailManager extends \Ubiquity\mailer\AbstractMail {

    private $newPassword;
    private $bodyContent;
    
    public function getBody(){
        return $this->bodyContent;
    }

    public function setBody($body){
        $this->bodyContent = $body;
    }

    /**
	 *
	 * {@inheritdoc}
	 * @see \Ubiquity\mailer\AbstractMail::initialize()
	 */
	protected function initialize(){
		
		$this->from(MailerManager::loadConfig()['user']??'from@organization');
	}
	/**
	 *
	 * {@inheritdoc}
	 * @see \Ubiquity\mailer\AbstractMail::body()
	 */
	public function body() {
	    return $this->bodyContent;
	}
}