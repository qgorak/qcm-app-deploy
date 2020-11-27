<?php
namespace models;
/**
 * @table('examoption')
*/
class Examoption{
	/**
	 * @id
	 * @column("name"=>"idExam","nullable"=>false,"dbType"=>"int(11)")
	 * @validator("id","constraints"=>array("autoinc"=>true))
	**/
	private $idExam;

	/**
	 * @id
	 * @column("name"=>"idOption","nullable"=>false,"dbType"=>"int(11)")
	 * @validator("id","constraints"=>array("autoinc"=>true))
	**/
	private $idOption;

	/**
	 * @column("name"=>"value","nullable"=>true,"dbType"=>"varchar(42)")
	 * @validator("length","constraints"=>array("max"=>42))
	**/
	private $value;

	/**
	 * @manyToOne
	 * @joinColumn("className"=>"models\\Exam","name"=>"idExam","nullable"=>false)
	**/
	private $exam;

	/**
	 * @manyToOne
	 * @joinColumn("className"=>"models\\Option","name"=>"idOption","nullable"=>false)
	**/
	private $option;

	 public function getIdExam(){
		return $this->idExam;
	}

	 public function setIdExam($idExam){
		$this->idExam=$idExam;
	}

	 public function getIdOption(){
		return $this->idOption;
	}

	 public function setIdOption($idOption){
		$this->idOption=$idOption;
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

	 public function getOption(){
		return $this->option;
	}

	 public function setOption($option){
		$this->option=$option;
	}

	 public function __toString(){
		return $this->idExam.'';
	}

}