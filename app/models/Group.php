<?php
namespace models;
/**
 * @table("name"=>"group")
 */
class Group{
	/**
	 * @id()
	 * @column("name"=>"id","dbType"=>"int(11)")
	 * @validator("type"=>"id","constraints"=>["autoinc"=>true])
	 */
	private $id;

	/**
	 * @column("name"=>"name","nullable"=>true,"dbType"=>"varchar(42)")
	 * @validator("type"=>"length","constraints"=>["max"=>42])
	 */
	private $name;

	/**
	 * @column("name"=>"description","nullable"=>true,"dbType"=>"text")
	 */
	private $description;

	/**
	 * @column("name"=>"keyCode","dbType"=>"varchar(255)")
	 * @validator("type"=>"length","constraints"=>["max"=>255,"notNull"=>true])
	 */
	private $keyCode;

	/**
	 * @oneToMany("mappedBy"=>"group","className"=>"models\\Exam")
	 */
	private $exams;

	/**
	 * @oneToMany("mappedBy"=>"group","className"=>"models\\Usergroup")
	 */
	private $usergroups;

	/**
	 * @manyToOne()
	 * @joinColumn("className"=>"models\\User","name"=>"idUser")
	 */
	private $user;

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id=$id;
	}

	public function getName(){
		return $this->name;
	}

	public function setName($name){
		$this->name=$name;
	}

	public function getDescription(){
		return $this->description;
	}

	public function setDescription($description){
		$this->description=$description;
	}

	public function getKeyCode(){
		return $this->keyCode;
	}

	public function setKeyCode($keyCode){
		$this->keyCode=$keyCode;
	}

	public function getExams(){
		return $this->exams;
	}

	public function setExams($exams){
		$this->exams=$exams;
	}

	 public function addExam($exam){
		$this->exams[]=$exam;
	}

	public function getUsergroups(){
		return $this->usergroups;
	}

	public function setUsergroups($usergroups){
		$this->usergroups=$usergroups;
	}

	 public function addUsergroup($usergroup){
		$this->usergroups[]=$usergroup;
	}

	public function getUser(){
		return $this->user;
	}

	public function setUser($user){
		$this->user=$user;
	}

	 public function __toString(){
		return ($this->keyCode??'no value').'';
	}

}