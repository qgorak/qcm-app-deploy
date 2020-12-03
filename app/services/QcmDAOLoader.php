<?php

namespace services;

use Ubiquity\orm\DAO;
use Ubiquity\utils\http\USession;
use models\Exam;
use models\Qcm;
use models\User;

class QcmDAOLoader {

	public function get($id): ?Qcm {
		return DAO::getById ( Qcm::class, $id );
	}

	public function add(Qcm $qcm): void {
        $creator = new User();
        $creator->setId(USession::get('activeUser')['id']);
        $qcm->setUser($creator);
        $exam = new Exam();
        $exam -> setId(1);
        $questions = USession::get('questions');
        $qcm->setQuestions($questions['checked']);
        $qcm->setExam($exam);
		DAO::insert($qcm,true);
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

