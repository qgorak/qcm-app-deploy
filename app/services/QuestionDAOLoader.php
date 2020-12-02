<?php

namespace services;

use Ubiquity\orm\DAO;
use models\Answer;
use models\Question;
use Ubiquity\utils\http\USession;

class QuestionDAOLoader {
<<<<<<< Updated upstream
	/**
	 *
	 * {@inheritdoc}
	 * @see \services\IQuestionLoader::get()
	 */
=======

>>>>>>> Stashed changes
	public function get($id): ?Question {
		return DAO::getById ( Question::class, $id );
	}

<<<<<<< Updated upstream
	/**
	 *
	 * {@inheritdoc}
	 * @see \services\IQuestionLoader::add()
	 */
=======
>>>>>>> Stashed changes
	public function add(Question $item,Answer $answer): void {
		DAO::insert ( $item );
		$answer->setQuestion($item);
		DAO::insert($answer);
	}


	public function all(): array {
		return DAO::getAll ( Question::class );
	}
	
	public function my(): array{
	    $userid = USession::get('activeUser')['id'];
	    return DAO::getAll( Question::class, 'idUser='.$userid);
	}
	

	public function clear(): void {
		DAO::deleteAll ( Question::class, '1=1' );
	}

	public function remove(string $id): bool {
		return DAO::delete ( Question::class, $id );
	}


	public function update(Question $item): bool {
		return DAO::update ( $item );
	}


}

