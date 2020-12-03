<?php

namespace services;

use Ubiquity\orm\DAO;
use models\Answer;
use models\Qcm;
use Ubiquity\utils\http\USession;

class QcmDAOLoader {

	public function get($id): ?Qcm {
		return DAO::getById ( Qcm::class, $id );
	}

	public function add(Qcm $item,Answer $answer): void {
		DAO::insert ( $item );
		$answer->setQcm($item);
		DAO::insert($answer);
	}

	public function all(): array {
		return DAO::getAll ( Qcm::class );
	}
	
	public function my(): array{
	    $userid = USession::get('activeUser')['id'];
	    return DAO::getAll( Qcm::class, 'idUser='.$userid);
	}

	public function clear(): void {
		DAO::deleteAll ( Qcm::class, '1=1' );
	}

	public function remove(string $id): bool {
		return DAO::delete ( Qcm::class, $id );
	}

	public function update($item): bool {
		return DAO::update ( $item );
	}
}

