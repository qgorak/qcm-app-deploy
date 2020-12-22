<?php
use Workerman\Worker;
require_once './vendor/autoload.php';

// Create A Worker and Listens 2346 port, use Websocket protocol
$ws_worker = new Worker("websocket://0.0.0.0:2346");

// 4 processes
$ws_worker->count = 1;

$ws_worker->users=[];

// Emitted when data is received
$ws_worker->onMessage = function($connection, $data) use ($ws_worker)
{   
    global $ws_worker;
    $dataArray=json_decode($data);
    if(isset($dataArray->id)){
        $connection->id=$dataArray->id;
        $ws_worker->users[$connection->id]=$connection;
    }
    if(isset($dataArray->target,$dataArray->cheat)){
        $id=$connection->id;
        $connection=$ws_worker->users[$dataArray->target];
        $string='L\'utilisateur d\'id '.$id.' a triché';
        $connection->send($string);
    }
};

// Run worker
Worker::runAll();