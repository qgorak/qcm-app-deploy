<?php
namespace models;
/**
 * @table('questiontag')
*/
class Questiontag{
	/**
	 * @id
	 * @column("name"=>"idQuestion","nullable"=>false,"dbType"=>"int(11)")
	 * @validator("id","constraints"=>array("autoinc"=>true))
	**/
	private $idQuestion;

	/**
	 * @id
	 * @column("name"=>"idTag","nullable"=>false,"dbType"=>"int(11)")
	 * @validator("id","constraints"=>array("autoinc"=>true))
	**/
	private $idTag;

	 public function getIdQuestion(){
		return $this->idQuestion;
	}

	 public function setIdQuestion($idQuestion){
		$this->idQuestion=$idQuestion;
	}

	 public function getIdTag(){
		return $this->idTag;
	}

	 public function setIdTag($idTag){
		$this->idTag=$idTag;
	}

	 public function __toString(){
		return $this->idQuestion.'';
	}

}