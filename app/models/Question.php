<?php
namespace models;
/**
 * @table('question')
*/
class Question{
	/**
	 * @id
	 * @column("name"=>"id","nullable"=>false,"dbType"=>"int(11)")
	 * @validator("id","constraints"=>array("autoinc"=>true))
	*/
	private $id;

	/**
	 * @column("name"=>"caption","nullable"=>true,"dbType"=>"varchar(42)")
	 * @validator("length","constraints"=>array("max"=>42))
	*/
	private $caption;

	/**
	 * @column("name"=>"points","nullable"=>true,"dbType"=>"int(11)")
	*/
	private $points;

	/**
	 * @column("name"=>"tags","nullable"=>true,"dbType"=>"text")
	*/
	private $tags;

	/**
	 * @oneToMany("mappedBy"=>"question","className"=>"models\\Answer")
	*/
	private $answers;

	/**
	 * @manyToOne
	 * @joinColumn("className"=>"models\\User","name"=>"idUser","nullable"=>false)
	*/
	private $user;

	/**
	 * @manyToMany("targetEntity"=>"models\\Qcm","inversedBy"=>"questions")
	 * @joinTable("name"=>"qcmquestion")
	*/
	private $qcms;

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

	 public function getPoints(){
		return $this->points;
	}

	 public function setPoints($points){
		$this->points=$points;
	}

	 public function getTags(){
		return $this->tags;
	}

	 public function setTags($tags){
		$this->tags=$tags;
	}

	 public function getAnswers(){
		return $this->answers;
	}

	 public function setAnswers($answers){
		$this->answers=$answers;
	}

	 public function addAnswer($answer){
		$this->answers[]=$answer;
	}

	 public function getUser(){
		return $this->user;
	}

	 public function setUser($user){
		$this->user=$user;
	}

	 public function getQcms(){
		return $this->qcms;
	}

	 public function setQcms($qcms){
		$this->qcms=$qcms;
	}

	 public function addQcm($qcm){
		$this->qcms[]=$qcm;
	}

	 public function __toString(){
		return $this->id.'';
	}

}