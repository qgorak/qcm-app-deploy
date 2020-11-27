<?php
namespace models;
/**
 * @table('group')
*/
class Group{
	/**
	 * @id
	 * @column("name"=>"id","nullable"=>false,"dbType"=>"int(11)")
	 * @validator("id","constraints"=>array("autoinc"=>true))
	**/
	private $id;

	/**
	 * @column("name"=>"name","nullable"=>true,"dbType"=>"varchar(42)")
	 * @validator("length","constraints"=>array("max"=>42))
	**/
	private $name;

	/**
	 * @column("name"=>"description","nullable"=>true,"dbType"=>"text")
	**/
	private $description;

	/**
	 * @oneToMany("mappedBy"=>"group","className"=>"models\\Exam")
	**/
	private $exams;

	/**
	 * @oneToMany("mappedBy"=>"group","className"=>"models\\Usergroup")
	**/
	private $usergroups;

	/**
	 * @manyToOne
	 * @joinColumn("className"=>"models\\User","name"=>"idUser","nullable"=>false)
	**/
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

	 public function getExams(){
		return $this->exams;
	}

	 public function setExams($exams){
		$this->exams=$exams;
	}

	 public function getUsergroups(){
		return $this->usergroups;
	}

	 public function setUsergroups($usergroups){
		$this->usergroups=$usergroups;
	}

	 public function getUser(){
		return $this->user;
	}

	 public function setUser($user){
		$this->user=$user;
	}

	 public function __toString(){
		return $this->id.'';
	}

}