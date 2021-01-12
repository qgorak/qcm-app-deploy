<?php
namespace models;
/**
 * @table("name"=>"tag")
 */
class Tag{
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
	 * @column("name"=>"color","nullable"=>true,"dbType"=>"varchar(42)")
	 * @validator("type"=>"length","constraints"=>["max"=>42])
	 */
	private $color;

	/**
	 * @manyToOne()
	 * @joinColumn("className"=>"models\\User","name"=>"idUser")
	 */
	private $user;

	/**
	 * @manyToMany("targetEntity"=>"models\\Question","inversedBy"=>"tags")
	 * @joinTable("name"=>"questiontag")
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

	public function getColor(){
		return $this->color;
	}

	public function setColor($color){
		$this->color=$color;
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