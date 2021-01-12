<?php
namespace models;
/**
 * @table("name"=>"useranswer")
 */
class Useranswer{
	/**
	 * @id()
	 * @column("name"=>"idUser","dbType"=>"int(11)")
	 * @validator("type"=>"id","constraints"=>["autoinc"=>true])
	 */
	private $idUser;

	/**
	 * @id()
	 * @column("name"=>"idExam","dbType"=>"int(11)")
	 * @validator("type"=>"id","constraints"=>["autoinc"=>true])
	 */
	private $idExam;

	/**
	 * @id()
	 * @column("name"=>"idQuestion","dbType"=>"int(11)")
	 * @validator("type"=>"id","constraints"=>["autoinc"=>true])
	 */
	private $idQuestion;

	/**
	 * @column("name"=>"value","dbType"=>"text")
	 * @validator("type"=>"notNull")
	 */
	private $value;

	/**
	 * @manyToOne()
	 * @joinColumn("className"=>"models\\Exam","name"=>"idExam")
	 */
	private $exam;

	/**
	 * @manyToOne()
	 * @joinColumn("className"=>"models\\Question","name"=>"idQuestion")
	 */
	private $question;

	/**
	 * @manyToOne()
	 * @joinColumn("className"=>"models\\User","name"=>"idUser")
	 */
	private $user;

	public function getIdUser(){
		return $this->idUser;
	}

	public function setIdUser($idUser){
		$this->idUser=$idUser;
	}

	public function getIdExam(){
		return $this->idExam;
	}

	public function setIdExam($idExam){
		$this->idExam=$idExam;
	}

	public function getIdQuestion(){
		return $this->idQuestion;
	}

	public function setIdQuestion($idQuestion){
		$this->idQuestion=$idQuestion;
	}

	public function getValue(){
		return $this->value;
	}

	public function setValue($value){
		$this->value=$value;
	}

	public function getExam(){
		return $this->exam;
	}

	public function setExam($exam){
		$this->exam=$exam;
	}

	public function getQuestion(){
		return $this->question;
	}

	public function setQuestion($question){
		$this->question=$question;
	}

	public function getUser(){
		return $this->user;
	}

	public function setUser($user){
		$this->user=$user;
	}

	 public function __toString(){
		return ($this->value??'no value').'';
	}

}