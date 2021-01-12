<?php
namespace models;
/**
 * @table("name"=>"usergroup")
 */
class Usergroup{
	/**
	 * @id()
	 * @column("name"=>"idGroup","dbType"=>"int(11)")
	 * @validator("type"=>"id","constraints"=>["autoinc"=>true])
	 */
	private $idGroup;

	/**
	 * @id()
	 * @column("name"=>"idUser","dbType"=>"int(11)")
	 * @validator("type"=>"id","constraints"=>["autoinc"=>true])
	 */
	private $idUser;

	/**
	 * @column("name"=>"status","dbType"=>"varchar(255)")
	 * @validator("type"=>"length","constraints"=>["max"=>255,"notNull"=>true])
	 */
	private $status;

	/**
	 * @manyToOne()
	 * @joinColumn("className"=>"models\\Group","name"=>"idGroup")
	 */
	private $group;

	/**
	 * @manyToOne()
	 * @joinColumn("className"=>"models\\User","name"=>"idUser")
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