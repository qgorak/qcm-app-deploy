<?php

namespace services;

use Ubiquity\orm\DAO;
use models\Exam;
use models\Group;
use models\Qcm;
use Ubiquity\utils\http\USession;
use models\Option;

class ExamDAOLoader {

	public function get($id): ?Exam {
		return DAO::getById ( Exam::class, $id );
	}

	public function add(Exam $exam): void {
	    $exam = new Exam();
		DAO::insert ( $exam );
	}
	
	public function all(): array {
		return DAO::getAll ( Exam::class );
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
	    return DAO::uGetAll(Qcm::class,'idUser=?',false,[USession::get('activeUser')['id']]);
	}
	
	public function allMyGroup(){
	    return DAO::uGetAll(Group::class,"idUser=?",false,[USession::get('activeUser')['id']]);
	}
	
	public function getOptions(){
		return DAO::getAll(Option::class);
	}
}

