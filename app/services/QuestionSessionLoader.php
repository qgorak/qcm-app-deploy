<?php

namespace services;

use Ubiquity\utils\http\USession;
use models\Question;

class QuestionSessionLoader implements IQuestionLoader {
	const SESSION_KEY = 'todo-items';
	public function all(): array {
		return USession::get ( self::SESSION_KEY, [ ] );
	}
	public function my(): array{
	    return USession::get ( self::SESSION_KEY, [ ] );
	}
	public function update(Question $item): bool {
	}
	public function remove(string $id): bool {
		$items = $this->all ();
		foreach ( $items as $index => $item ) {
			if ($item->getId () === $id) {
				unset ( $items [$index] );
				USession::set ( self::SESSION_KEY, $items );
				return true;
			}
		}

		return false;
	}
	public function add(Question $item): void {
		$item->setId ( \uniqid () );
		USession::addOrRemoveValueFromArray ( self::SESSION_KEY, $item, true );
	}
	public function clear(): void {
		USession::delete ( self::SESSION_KEY );
	}
	/**
	 *
	 * {@inheritdoc}
	 * @see \services\IQuestionLoader::get()
	 */
	public function get($id): ?Question {
		return (USession::getArray ( self::SESSION_KEY ) [$id]) ?? null;
	}
	

}














