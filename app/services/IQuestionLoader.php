<?php

namespace services;

use models\Question;

interface IQuestionLoader {
	/**
	 * Retourne une liste de todo-items
	 *
	 * @return Question[]
	 */
	public function all(): array;
	
	public function my(): array;

	/**
	 * Supprime un todo-item par son id
	 *
	 * @param string $id
	 * @return bool
	 */
	public function remove(string $id): bool;

	/**
	 *
	 * @param Question $item
	 * @return bool
	 */
	public function update(Question $item): bool;

	/**
	 * Ajoute un Todo-item
	 *
	 * @param Question $item
	 */
	public function add(Question $item): void;

	/**
	 * Supprime tous les items
	 */
	public function clear(): void;

	/**
	 *
	 * @param mixed $id
	 * @return ?Question
	 */
	public function get($id): ?Question;
}










