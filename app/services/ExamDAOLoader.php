<?php

namespace services;

use Ubiquity\orm\DAO;
use models\Exam;
use models\Group;
use models\Qcm;
use Ubiquity\utils\http\USession;

class ExamDAOLoader {

	public function get($id): ?Exam {
		return DAO::getById ( Exam::class, $id, true );
	}

	public function add(Exam $exam): void {
	    $exam = new Exam();
		DAO::insert ( $exam );
	}
	
	public function all(): array {
		return DAO::getAll ( Exam::class );
	}

    public function my(): array {
        return DAO::uGetAll(Exam::class,'qcm.idUser = ?',true,[USession::get('activeUser')['id']]);;
    }
	
	public function clear(): void {
		DAO::deleteAll ( Exam::class, '1=1' );
	}

	public function remove(string $id): bool {
	    DAO::deleteAll(Exam::class,"id=?",[$id]);
		return DAO::delete ( Exam::class, $id );
	}


	public function update(Exam $exam): bool {
		return DAO::update ( $exam );
	}
	
	public function allMyQCM(){
	    return DAO::getAll(Qcm::class,'idUser=?',false,[USession::get('activeUser')['id']]);
	}
	
	public function allMyGroup(){
	    return DAO::getAll(Group::class,"idUser=?",false,[USession::get('activeUser')['id']]);
	}
}

