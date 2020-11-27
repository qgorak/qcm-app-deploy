<?php
namespace models;
/**
 * @table('option')
*/
class Option{
	/**
	 * @id
	 * @column("name"=>"id","nullable"=>false,"dbType"=>"int(11)")
	 * @validator("id","constraints"=>array("autoinc"=>true))
	**/
	private $id;

	/**
	 * @column("name"=>"key","nullable"=>true,"dbType"=>"varchar(42)")
	 * @validator("length","constraints"=>array("max"=>42))
	**/
	private $key;

	/**
	 * @column("name"=>"description","nullable"=>true,"dbType"=>"text")
	**/
	private $description;

	/**
	 * @oneToMany("mappedBy"=>"option","className"=>"models\\Examoption")
	**/
	private $examoptions;

	 public function getId(){
		return $this->id;
	}

	 public function setId($id){
		$this->id=$id;
	}

	 public function getKey(){
		return $this->key;
	}

	 public function setKey($key){
		$this->key=$key;
	}

	 public function getDescription(){
		return $this->description;
	}

	 public function setDescription($description){
		$this->description=$description;
	}

	 public function getExamoptions(){
		return $this->examoptions;
	}

	 public function setExamoptions($examoptions){
		$this->examoptions=$examoptions;
	}

	 public function __toString(){
		return $this->id.'';
	}

}