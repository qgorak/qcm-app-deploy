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
    $connection->send($data);
};

// Run worker
Worker::runAll();