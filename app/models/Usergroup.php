<?php
namespace models;
/**
 * @table('usergroup')
*/
class Usergroup{
	/**
	 * @id
	 * @column("name"=>"idGroup","nullable"=>false,"dbType"=>"int(11)")
	 * @validator("id","constraints"=>array("autoinc"=>true))
	*/
	private $idGroup;

	/**
	 * @id
	 * @column("name"=>"idUser","nullable"=>false,"dbType"=>"int(11)")
	 * @validator("id","constraints"=>array("autoinc"=>true))
	*/
	private $idUser;

	/**
	 * @column("name"=>"status","nullable"=>false,"dbType"=>"varchar(255)")
	 * @validator("length","constraints"=>array("max"=>255,"notNull"=>true))
	*/
	private $status;

	/**
	 * @manyToOne
	 * @joinColumn("className"=>"models\\Group","name"=>"idGroup","nullable"=>false)
	*/
	private $group;

	/**
	 * @manyToOne
	 * @joinColumn("className"=>"models\\User","name"=>"idUser","nullable"=>false)
	*/
	private $user;

	 public function getIdGroup(){
		return $this->idGroup;
	}

	 public function setIdGroup($idGroup){
		$this->idGroup=$idGroup;
	}

	 public function getIdUser(){
		return $this->idUser;
	}

	 public function setIdUser($idUser){
		$this->idUser=$idUser;
	}

	 public function getStatus(){
		return $this->status;
	}

	 public function setStatus($status){
		$this->status=$status;
	}

	 public function getGroup(){
		return $this->group;
	}

	 public function setGroup($group){
		$this->group=$group;
	}

	 public function getUser(){
		return $this->user;
	}

	 public function setUser($user){
		$this->user=$user;
	}

	 public function __toString(){
		return ($this->status??'no value').'';
	}
}