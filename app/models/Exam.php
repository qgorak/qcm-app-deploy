<?php
namespace models;
/**
 * @table("name"=>"exam")
 */
class Exam{
	/**
	 * @id()
	 * @column("name"=>"id","dbType"=>"int(11)")
	 * @validator("type"=>"id","constraints"=>["autoinc"=>true])
	 */
	private $id;

	/**
	 * @column("name"=>"dated","nullable"=>true,"dbType"=>"datetime")
	 * @validator("type"=>"type","constraints"=>["ref"=>"dateTime"])
	 * @transformer("name"=>"datetime")
	 */
	private $dated;

	/**
	 * @column("name"=>"datef","nullable"=>true,"dbType"=>"datetime")
	 * @validator("type"=>"type","constraints"=>["ref"=>"dateTime"])
	 * @transformer("name"=>"datetime")
	 */
	private $datef;

	/**
	 * @column("name"=>"status","nullable"=>true,"dbType"=>"varchar(42)")
	 * @validator("type"=>"length","constraints"=>["max"=>42])
	 */
	private $status;

	/**
	 * @column("name"=>"options","dbType"=>"text")
	 * @validator("type"=>"notNull")
	 */
	private $options;

	/**
	 * @oneToMany("mappedBy"=>"exam","className"=>"models\\Useranswer")
	 */
	private $useranswers;

	/**
	 * @manyToOne()
	 * @joinColumn("className"=>"models\\Group","name"=>"idGroup")
	 */
	private $group;

	/**
	 * @manyToOne()
	 * @joinColumn("className"=>"models\\Qcm","name"=>"idQcm")
	 */
	private $qcm;

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

	public function getOptions(){
		return $this->options;
	}

	public function setOptions($options){
		$this->options=$options;
	}

	public function getUseranswers(){
		return $this->useranswers;
	}

	public function setUseranswers($useranswers){
		$this->useranswers=$useranswers;
	}

	 public function addUseranswer($useranswer){
		$this->useranswers[]=$useranswer;
	}

	public function getGroup(){
		return $this->group;
	}

	public function setGroup($group){
		$this->group=$group;
	}

	public function getQcm(){
		return $this->qcm;
	}

	public function setQcm($qcm){
		$this->qcm=$qcm;
	}

	 public function __toString(){
		return ($this->options??'no value').'';
	}

}