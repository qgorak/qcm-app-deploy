<?php
namespace models;
/**
 * @table('exam')
*/
class Exam{
	/**
	 * @id
	 * @column("name"=>"id","nullable"=>false,"dbType"=>"int(11)")
	 * @validator("id","constraints"=>array("autoinc"=>true))
	**/
	private $id;

	/**
	 * @column("name"=>"dated","nullable"=>true,"dbType"=>"datetime")
	 * @validator("type","dateTime")
	 * @transformer("name"=>"datetime")
	**/
	private $dated;

	/**
	 * @column("name"=>"datef","nullable"=>true,"dbType"=>"datetime")
	 * @validator("type","dateTime")
	 * @transformer("name"=>"datetime")
	**/
	private $datef;

	/**
	 * @column("name"=>"status","nullable"=>true,"dbType"=>"varchar(42)")
	 * @validator("length","constraints"=>array("max"=>42))
	**/
	private $status;

	/**
	 * @oneToMany("mappedBy"=>"exam","className"=>"models\\Examoption")
	**/
	private $examoptions;

	/**
	 * @oneToMany("mappedBy"=>"exam","className"=>"models\\Qcm")
	**/
	private $qcms;

	/**
	 * @manyToOne
	 * @joinColumn("className"=>"models\\Group","name"=>"idGroup","nullable"=>false)
	**/
	private $group;

	 public function getId(){
		return $this->id;
	}

	 public function setId($id){
		$this->id=$id;
	}

	 public function getDated(){
		return $this->dated;
	}

	 public function setDated($dated){
		$this->dated=$dated;
	}

	 public function getDatef(){
		return $this->datef;
	}

	 public function setDatef($datef){
		$this->datef=$datef;
	}

	 public function getStatus(){
		return $this->status;
	}

	 public function setStatus($status){
		$this->status=$status;
	}

	 public function getExamoptions(){
		return $this->examoptions;
	}

	 public function setExamoptions($examoptions){
		$this->examoptions=$examoptions;
	}

	 public function getQcms(){
		return $this->qcms;
	}

	 public function setQcms($qcms){
		$this->qcms=$qcms;
	}

	 public function getGroup(){
		return $this->group;
	}

	 public function setGroup($group){
		$this->group=$group;
	}

	 public function __toString(){
		return $this->id.'';
	}

}