<?php

namespace services\DAO;

use Ubiquity\orm\DAO;
use models\User;
use Ubiquity\utils\http\USession;
use models\Usergroup;
use models\Exam;

class UserDAOLoader {
    
    public function get($id): ?User{
        return DAO::getById ( User::class , $id );
    }
    
    public function all(): array {
        return DAO::getAll ( User::class,false );
    }
    
    public function clear(): void {
        DAO::deleteAll ( User::class, '1=1' );
    }
    
    public function remove(string $id): bool {
        return DAO::delete ( User::class, $id );
    }
    
    public function update(User $user): bool {
        return DAO::update ( $user );
    }
    
    public function getByEmail($email){
        return DAO::getOne(User::class,'email=?',false,[$email]);
    }
   
}

