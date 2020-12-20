<?php
namespace mail;

use Ubiquity\mailer\MailerManager;

 /**
  * Mailer MailManager
  */
class MailManager extends \Ubiquity\mailer\AbstractMail {

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
		return "Reset password";
	}
}