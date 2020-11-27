<?php
namespace models;
/**
 * @table('useranswer')
*/
class Useranswer{
	/**
	 * @id
	 * @column("name"=>"idUser","nullable"=>false,"dbType"=>"int(11)")
	 * @validator("id","constraints"=>array("autoinc"=>true))
	**/
	private $idUser;

	/**
	 * @id
	 * @column("name"=>"idAnswer","nullable"=>false,"dbType"=>"int(11)")
	 * @validator("id","constraints"=>array("autoinc"=>true))
	**/
	private $idAnswer;

	/**
	 * @id
	 * @column("name"=>"idQcm","nullable"=>false,"dbType"=>"int(11)")
	 * @validator("id","constraints"=>array("autoinc"=>true))
	**/
	private $idQcm;

	/**
	 * @manyToOne
	 * @joinColumn("className"=>"models\\Answer","name"=>"idAnswer","nullable"=>false)
	**/
	private $answer;

	/**
	 * @manyToOne
	 * @joinColumn("className"=>"models\\Qcm","name"=>"idQcm","nullable"=>false)
	**/
	private $qcm;

	/**
	 * @manyToOne
	 * @joinColumn("className"=>"models\\User","name"=>"idUser","nullable"=>false)
	**/
	private $user;

	 public function getIdUser(){
		return $this->idUser;
	}

	 public function setIdUser($idUser){
		$this->idUser=$idUser;
	}

	 public function getIdAnswer(){
		return $this->idAnswer;
	}

	 public function setIdAnswer($idAnswer){
		$this->idAnswer=$idAnswer;
	}

	 public function getIdQcm(){
		return $this->idQcm;
	}

	 public function setIdQcm($idQcm){
		$this->idQcm=$idQcm;
	}

	 public function getAnswer(){
		return $this->answer;
	}

	 public function setAnswer($answer){
		$this->answer=$answer;
	}

	 public function getQcm(){
		return $this->qcm;
	}

	 public function setQcm($qcm){
		$this->qcm=$qcm;
	}

	 public function getUser(){
		return $this->user;
	}

	 public function setUser($user){
		$this->user=$user;
	}

	 public function __toString(){
		return $this->idUser.'';
	}

}