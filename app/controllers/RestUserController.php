<?php
namespace controllers;

use Ubiquity\orm\DAO;
use models\User;
use Ubiquity\utils\http\UResponse;

/**
 * Rest Controller RestUserController
 * @route("/rest/user","inherited"=>true,"automated"=>true)
 * @rest("resource"=>"models\\User")
 */
class RestUserController extends \Ubiquity\controllers\rest\RestController {

    /**
     * @get('exist/{id}',"name"=>"user.exist")
     */
    public function isUserExisting(string $id){
        if ($user=DAO::getOne(User::class,'id=?',true,[$id])){
            echo json_encode($user->getEmail());;
        }else{
            echo json_encode('false');
        }
    }

}
