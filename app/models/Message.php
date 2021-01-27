<?php
namespace models;
/**
 * @table("name"=>"message")
 */
class Message{
	/**
	 * @id()
	 * @column("name"=>"id","dbType"=>"int(11)")
	 * @validator("type"=>"id","constraints"=>["autoinc"=>true])
	 */
	private $id;

	/**
	 * @column("name"=>"idUser","dbType"=>"int(11)")
	 * @validator("type"=>"notNull")
	 * @manyToOne()
	 * @joinColumn("className"=>"models\\User","name"=>"idUser")
	 */
	private $idUser;

	/**
	 * @column("name"=>"content","dbType"=>"text")
	 * @validator("type"=>"notNull")
	 */
	private $content;

	/**
	 * @column("name"=>"seen","nullable"=>true,"dbType"=>"int(1)")
	 */
	private $seen;

	/**
	 * @column("name"=>"cdate","nullable"=>true,"dbType"=>"datetime")
	 * @validator("type"=>"type","constraints"=>["ref"=>"dateTime"])
	 * @transformer("name"=>"datetime")
	 */
	private $cdate;

	/**
	 * @manyToOne()
	 * @joinColumn("className"=>"models\\Exam","name"=>"idExam","nullable"=>true)
	 */
	private $exam;

	/**
	 * @manyToOne()
	 * @joinColumn("className"=>"models\\User","name"=>"idTarget")
	 */
	private $user;

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id=$id;
	}

	public function getIdUser(){
		return $this->idUser;
	}

	public function setIdUser($idUser){
		$this->idUser=$idUser;
	}

	public function getContent(){
		return $this->content;
	}

	public function setContent($content){
		$this->content=$content;
	}

	public function getSeen(){
		return $this->seen;
	}

	public function setSeen($seen){
		$this->seen=$seen;
	}

	public function getCdate(){
		return $this->cdate;
	}

	public function setCdate($cdate){
		$this->cdate=$cdate;
	}

	public function getExam(){
		return $this->exam;
	}

	public function setExam($exam){
		$this->exam=$exam;
	}

	public function getUser(){
		return $this->user;
	}

	public function setUser($user){
		$this->user=$user;
	}

	 public function __toString(){
		return ($this->content??'no value').'';
	}

}