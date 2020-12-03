<?php
namespace models;
/**
 * @table('answer')
*/
class Answer{
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
	 * @column("name"=>"score","nullable"=>true,"dbType"=>"float")
	*/
	private $score;

	/**
	 * @oneToMany("mappedBy"=>"answer","className"=>"models\\Useranswer")
	*/
	private $useranswers;

	/**
	 * @manyToOne
	 * @joinColumn("className"=>"models\\Question","name"=>"idQuestion","nullable"=>false)
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

	 public function getUseranswers(){
		return $this->useranswers;
	}

	 public function setUseranswers($useranswers){
		$this->useranswers=$useranswers;
	}

	 public function addUseranswer($useranswer){
		$this->useranswers[]=$useranswer;
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