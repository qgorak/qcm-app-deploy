<?php
namespace models;
/**
 * @table("name"=>"qcm")
 */
class Qcm{
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
	 * @column("name"=>"description","nullable"=>true,"dbType"=>"varchar(42)")
	 * @validator("type"=>"length","constraints"=>["max"=>42])
	 */
	private $description;

	/**
	 * @column("name"=>"cdate","nullable"=>true,"dbType"=>"datetime")
	 * @validator("type"=>"type","constraints"=>["ref"=>"dateTime"])
	 * @transformer("name"=>"datetime")
	 */
	private $cdate;

	/**
	 * @oneToMany("mappedBy"=>"qcm","className"=>"models\\Exam")
	 */
	private $exams;

	/**
	 * @manyToOne()
	 * @joinColumn("className"=>"models\\User","name"=>"idUser")
	 */
	private $user;

	/**
	 * @manyToMany("targetEntity"=>"models\\Question","inversedBy"=>"qcms")
	 * @joinTable("name"=>"qcmquestion")
	 */
	private $questions;

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

	public function getCdate(){
		return $this->cdate;
	}

	public function setCdate($cdate){
		$this->cdate=$cdate;
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

	public function getUser(){
		return $this->user;
	}

	public function setUser($user){
		$this->user=$user;
	}

	public function getQuestions(){
		return $this->questions;
	}

	public function setQuestions($questions){
		$this->questions=$questions;
	}

	 public function addQuestion($question){
		$this->questions[]=$question;
	}

	 public function __toString(){
		return $this->id.'';
	}

}