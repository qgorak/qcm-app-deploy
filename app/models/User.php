<?php
namespace models;

use Ubiquity\orm\DAO;
use Ubiquity\utils\http\USession;
/**
 * @table('user')
*/
class User{
	/**
	 * @id
	 * @column("name"=>"id","nullable"=>false,"dbType"=>"int(11)")
	 * @validator("id","constraints"=>array("autoinc"=>true))
	*/
	private $id;

	/**
	 * @column("name"=>"password","nullable"=>true,"dbType"=>"varchar(42)")
	 * @validator("length","constraints"=>array("max"=>42))
	 * @transformer("name"=>"password")
	*/
	private $password;

	/**
	 * @column("name"=>"firstname","nullable"=>true,"dbType"=>"varchar(42)")
	 * @validator("length","constraints"=>array("max"=>42))
	*/
	private $firstname;

	/**
	 * @column("name"=>"lastname","nullable"=>true,"dbType"=>"varchar(42)")
	 * @validator("length","constraints"=>array("max"=>42))
	*/
	private $lastname;

	/**
	 * @column("name"=>"email","nullable"=>true,"dbType"=>"varchar(255)")
	 * @validator("email")
	 * @validator("length","constraints"=>array("max"=>255))
	*/
	private $email;

	/**
	 * @oneToMany("mappedBy"=>"user","className"=>"models\\Group")
	*/
	private $groups;

	/**
	 * @oneToMany("mappedBy"=>"user","className"=>"models\\Qcm")
	*/
	private $qcms;

	/**
	 * @oneToMany("mappedBy"=>"user","className"=>"models\\Question")
	*/
	private $questions;

	/**
	 * @oneToMany("mappedBy"=>"user","className"=>"models\\Tag")
	*/
	private $tags;

	/**
	 * @oneToMany("mappedBy"=>"user","className"=>"models\\Useranswer")
	*/
	private $useranswers;

	/**
	 * @oneToMany("mappedBy"=>"user","className"=>"models\\Usergroup")
	*/
	private $usergroups;

	 public function getId(){
		return $this->id;
	}

	 public function setId($id){
		$this->id=$id;
	}

	 public function getPassword(){
		return $this->password;
	}

	 public function setPassword($password){
		$this->password=$password;
	}

	 public function getFirstname(){
		return $this->firstname;
	}

	 public function setFirstname($firstname){
		$this->firstname=$firstname;
	}

	 public function getLastname(){
		return $this->lastname;
	}

	 public function setLastname($lastname){
		$this->lastname=$lastname;
	}

	 public function getEmail(){
		return $this->email;
	}

	 public function setEmail($email){
		$this->email=$email;
	}

	 public function getGroups(){
		return $this->groups;
	}

	 public function setGroups($groups){
		$this->groups=$groups;
	}

	 public function addGroup($group){
		$this->groups[]=$group;
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

	 public function getQuestions(){
		return $this->questions;
	}

	 public function setQuestions($questions){
		$this->questions=$questions;
	}

	 public function addQuestion($question){
		$this->questions[]=$question;
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

	 public function getUseranswers(){
		return $this->useranswers;
	}

	 public function setUseranswers($useranswers){
		$this->useranswers=$useranswers;
	}

	 public function addUseranswer($useranswer){
		$this->useranswers[]=$useranswer;
	}

	 public function getUsergroups(){
		return $this->usergroups;
	}

	 public function setUsergroups($usergroups){
		$this->usergroups=$usergroups;
	}

	 public function addUsergroup($usergroup){
		$this->usergroups[]=$usergroup;
	}

	 public function __toString(){
		return $this->id.'';
	}
	
	public function isCreator(string $idGroup){
		$group=DAO::getById(Group::class, $idGroup);
		if($group->getUser()==$this->getId()){
			return true;
		}
		return false;
	}
	
	public function isInGroup(string $idGroup){
		if(DAO::exists(Usergroup::class,"idGroup=? AND idUser=? AND status='1'",[$idGroup,$this->getId()])){
			return true;
		}
		return false;
	}
	
	public function getAllGroups(){
		$retour=[];
		$userGroups=DAO::uGetAll(Usergroup::class,"idUser=? AND status='1'",false,[USession::get('activeUser')['id']]);
		foreach($userGroups as $value){
		       array_push($retour,DAO::getById(Group::class,$value->getIdGroup(),false));
		}
		return $retour;
	}

}