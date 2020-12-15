<?php
namespace services;

use Ubiquity\orm\DAO;
use models\Exam;

class EmailManager{
    
    public function sendExamStart(){
        $exams=DAO::getAll(Exam::class);
    }
}

