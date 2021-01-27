<?php

namespace services\DAO;

use models\Message;
use models\User;
use Ubiquity\orm\DAO;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\http\USession;

class MessageDAOLoader {

	public function get($id): ?Message {
		return DAO::getById ( Message::class, $id );
	}

	public function add(Message $message) {
        $creator = new User();
        $creator->setId(USession::get('activeUser')['id']);
        $message->setIdUser($creator);
        $message->setCdate(date('Y-m-d H:i:s'));
		DAO::insert ( $message );
		return \json_encode($message);
	}

}