<?php
namespace models;
/**
 * @table('typeq')
*/
class Typeq{
	/**
	 * @id
	 * @column("name"=>"id","nullable"=>false,"dbType"=>"int(11)")
	 * @validator("id","constraints"=>array("autoinc"=>true))
	**/
	private $id;

	/**
	 * @column("name"=>"caption","nullable"=>true,"dbType"=>"varchar(42)")
	 * @validator("length","constraints"=>array("max"=>42))
	**/
	private $caption;

	/**
	 * @oneToMany("mappedBy"=>"typeq","className"=>"models\\Question")
	**/
	private $questions;

	 public function getId(){
		return $this->id;
	}

	 public function setId($id){
		$this->id=$id;
	}

	 public function getCaption(){
		return $this->caption;
	}

	 public function setCaption($caption){
		$this->caption=$caption;
	}

	 public function getQuestions(){
		return $this->questions;
	}

	 public function setQuestions($questions){
		$this->questions=$questions;
	}

	 public function __toString(){
		return $this->id.'';
	}

}