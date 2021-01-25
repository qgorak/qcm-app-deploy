<?php
use Workerman\Worker;
require_once './vendor/autoload.php';

// Create A Worker and Listens 2346 port, use Websocket protocol
$ws_worker = new Worker("websocket://0.0.0.0:2346");

// 4 processes
$ws_worker->count = 1;


$ws_worker->exams=[];

$ws_worker->onClose = function($connection) use ($ws_worker){
    $exams = array_keys($ws_worker->exams);
    foreach($exams as &$exam) {
        if (($key = array_search($connection->id, $ws_worker->exams[$exam]['users'])) !== false) {
            unset($ws_worker->exams[$exam]['users'][$key]);
            if (isset($ws_worker->exams[$exam]['users'][$ws_worker->exams[$exam]['Owner']])) {
                $id = $ws_worker->exams[$exam]['users'][$ws_worker->exams[$exam]['Owner']];
                $connection = $ws_worker->connections[$id];
                $users['usersLogged'] = array_keys($ws_worker->exams[$exam]['users']);
                $users['Owner']= $ws_worker->exams[$exam]['Owner'];
                $connection->send(json_encode($users));
            }
        }

    }
};

$ws_worker->onMessage = function($connection, $data) use ($ws_worker){
    global $ws_worker;
    $dataArray=json_decode($data);
    if(isset($dataArray->exam,$dataArray->id,$dataArray->idOwner)){
        $ws_worker->exams[$dataArray->exam]['users'][$dataArray->id]=$connection->id;
        $ws_worker->exams[$dataArray->exam]['Owner']=$dataArray->idOwner;
        if($dataArray->id!=$dataArray->idOwner) {
            if (isset($ws_worker->exams[$dataArray->exam]['users'][$dataArray->idOwner])) {
                $id = $ws_worker->exams[$dataArray->exam]['users'][$dataArray->idOwner];
                $connection = $ws_worker->connections[$id];
                $users['usersLogged'] = array_keys($ws_worker->exams[$dataArray->exam]['users']);
                $users['Owner']= $ws_worker->exams[$dataArray->exam]['Owner'];
                $connection->send(json_encode($users));
            }
        }
    }
    if(isset($dataArray->target,$dataArray->cheat)){
        file_put_contents('../exam_logs/exam_'.$dataArray->exam.'.log', $dataArray->user->id.'`'.date('Y-m-d H:i:s').' '.$dataArray->user->firstname.' '.$dataArray->user->lastname.' cheated '.$dataArray->cheat.'time'.PHP_EOL, FILE_APPEND);
        if(isset($ws_worker->exams[$dataArray->exam]['users'][$dataArray->target])){
            $id=$ws_worker->exams[$dataArray->exam]['users'][$dataArray->target];
            $connection=$ws_worker->connections[$id];
            $dataArray->date=date('Y-m-d H:i:s');
            $data=json_encode($dataArray);
            $connection->send($data);
        }
    }
    if(isset($dataArray->target,$dataArray->idPQ)){
        if(isset($ws_worker->exams[$dataArray->exam]['users'][$dataArray->target])){
            $id=$ws_worker->exams[$dataArray->exam]['users'][$dataArray->target];
            $connection=$ws_worker->connections[$id];
            $connection->send($data);
        }
    }
    if(isset($dataArray->target,$dataArray->message,$dataArray->exam)){
        if(isset($ws_worker->exams[$dataArray->exam]['users'][$dataArray->target])){
            $id=$ws_worker->exams[$dataArray->exam]['users'][$dataArray->target];
            $connection=$ws_worker->connections[$id];
            $connection->send($data);
        }
    }
    if(isset($dataArray->action,$dataArray->id)){
        if(isset($ws_worker->exams[$dataArray->exam]['users'][$dataArray->id])){
            $id=$ws_worker->exams[$dataArray->exam]['users'][$dataArray->id];
            $connection=$ws_worker->connections[$id];
            $users['usersLogged'] = array_keys($ws_worker->exams[$dataArray->exam]['users']);
            $users['Owner']= $ws_worker->exams[$dataArray->exam]['Owner'];
            $connection->send(json_encode($users));
        }
    }



/*$ws_worker->onMessage = function($connection, $data) use ($ws_worker){
    global $ws_worker;
    $dataArray=json_decode($data);
    if(isset($dataArray->id)){
        $ws_worker->users[$dataArray->id]=$connection->id;
    }
    if(isset($dataArray->target,$dataArray->cheat)){
        if(isset($ws_worker->users[$dataArray->target])){
            $id=$ws_worker->users[$dataArray->target];
            $connection=$ws_worker->connections[$id];
            $connection->send($data);
        }
    }
    if(isset($dataArray->target,$dataArray->message)){
        if(isset($ws_worker->users[$dataArray->target])){
            $id=$ws_worker->users[$dataArray->target];
            $connection=$ws_worker->connections[$id];
            $connection->send($data);
        }
    }
    $connection->send($data);**/
};

Worker::runAll();