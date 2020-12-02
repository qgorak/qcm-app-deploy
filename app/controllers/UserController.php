<?php
namespace controllers;

use Ubiquity\orm\DAO;
use models\User;

/**
 * Controller UserController
 * @route('user','inherited'=>true,'automated'=>true)
 * @property \Ajax\php\ubiquity\JsUtils $jquery
 */
class UserController extends ControllerBase{

	public function index(){
		
	}
	
	/**
	 * @get('exist/{id}',name=>"user.exist")
	 */
	public function isUserExisting(string $id){
	    $user=DAO::getOne(User::class,'id=?',true,[$id]);
	    echo $user->getEmail();
	}
}
