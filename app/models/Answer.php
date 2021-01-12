<?php
namespace models;
/**
 * @table("name"=>"answer")
 */
class Answer{
	/**
	 * @id()
	 * @column("name"=>"id","dbType"=>"int(11)")
	 * @validator("type"=>"id","constraints"=>["autoinc"=>true])
	 */
	private $id;

	/**
	 * @column("name"=>"caption","nullable"=>true,"dbType"=>"varchar(42)")
	 * @validator("type"=>"length","constraints"=>["max"=>42])
	 */
	private $caption;

	/**
	 * @column("name"=>"score","nullable"=>true,"dbType"=>"float")
	 */
	private $score;

	/**
	 * @manyToOne()
	 * @joinColumn("className"=>"models\\Question","name"=>"idQuestion")
	 */
	private $question;

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

	public function getScore(){
		return $this->score;
	}

	public function setScore($score){
		$this->score=$score;
	}

	public function getQuestion(){
		return $this->question;
	}

	public function setQuestion($question){
		$this->question=$question;
	}

	 public function __toString(){
		return $this->id.'';
	}

}