<?php
namespace models;
/**
 * @table("name"=>"question")
 */
class Question{
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
	 * @column("name"=>"ckcontent","nullable"=>true,"dbType"=>"text")
	 */
	private $ckcontent;

	/**
	 * @column("name"=>"points","nullable"=>true,"dbType"=>"int(11)")
	 */
	private $points;

	/**
	 * @column("name"=>"idTypeq","nullable"=>true,"dbType"=>"int(11)")
	 */
	private $idTypeq;

	/**
	 * @oneToMany("mappedBy"=>"question","className"=>"models\\Answer")
	 */
	private $answers;

	/**
	 * @oneToMany("mappedBy"=>"question","className"=>"models\\Useranswer")
	 */
	private $useranswers;

	/**
	 * @manyToOne()
	 * @joinColumn("className"=>"models\\User","name"=>"idUser")
	 */
	private $user;

	/**
	 * @manyToMany("targetEntity"=>"models\\Qcm","inversedBy"=>"questions")
	 * @joinTable("name"=>"qcmquestion")
	 */
	private $qcms;

	/**
	 * @manyToMany("targetEntity"=>"models\\Tag","inversedBy"=>"questions")
	 * @joinTable("name"=>"questiontag")
	 */
	private $tags;

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

	public function getCkcontent(){
		return $this->ckcontent;
	}

	public function setCkcontent($ckcontent){
		$this->ckcontent=$ckcontent;
	}

	public function getPoints(){
		return $this->points;
	}

	public function setPoints($points){
		$this->points=$points;
	}

	public function getIdTypeq(){
		return $this->idTypeq;
	}

	public function setIdTypeq($idTypeq){
		$this->idTypeq=$idTypeq;
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

	public function getUseranswers(){
		return $this->useranswers;
	}

	public function setUseranswers($useranswers){
		$this->useranswers=$useranswers;
	}

	 public function addUseranswer($useranswer){
		$this->useranswers[]=$useranswer;
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

	public function getTags(){
		return $this->tags;
	}

	public function setTags($tags){
		$this->tags=$tags;
	}

	 public function addTag($tag){
		$this->tags[]=$tag;
	}

	 public function __toString(){
		return $this->id.'';
	}

}