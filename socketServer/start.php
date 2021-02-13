<?php
use Workerman\Worker;
require_once './vendor/autoload.php';

$context = array(
    'ssl' => array(
        'local_cert'  => '/etc/ssl/websocketssl.pem',
        'local_pk'    => '/etc/ssl/websocketss.key',
        'verify_peer' => false,
    )
);
// Create A Worker and Listens 2346 port, use Websocket protocol
$ws_worker = new Worker("websocket://0.0.0.0:2346",$context);

$ws_worker->transport = 'ssl';
// 4 processes
$ws_worker->count = 1;


$ws_worker->exams=[];

$ws_worker->onClose = function($connection) use ($ws_worker){
    $exams = array_keys($ws_worker->exams);
    foreach($exams as &$exam) {
            foreach ($ws_worker->exams[$exam]['users'] as $key => $value){
                if ($ws_worker->exams[$exam]['users'][$key]['connection'] == $connection->id){
                    if ($ws_worker->exams[$exam]['users'][$key]['user']->id != $ws_worker->exams[$exam]['Owner']){
                        $users['left']=$ws_worker->exams[$exam]['users'][$key]['user'];
                        file_put_contents('../exam_logs/exam_'.$exam.'.log', $ws_worker->exams[$exam]['users'][$key]['user']->id.'`'.date('Y-m-d H:i:s').' '.$ws_worker->exams[$exam]['users'][$key]['user']->firstname.' '.$ws_worker->exams[$exam]['users'][$key]['user']->lastname.' left the exam'.PHP_EOL, FILE_APPEND);
                    }
                    unset($ws_worker->exams[$exam]['users'][$key]);
                }
            }
            if (isset($ws_worker->exams[$exam]['users'][$ws_worker->exams[$exam]['Owner']])) {
                $id = $ws_worker->exams[$exam]['users'][$ws_worker->exams[$exam]['Owner']]['connection'];
                $connection = $ws_worker->connections[$id];
                $users['usersLogged'] = array_keys($ws_worker->exams[$exam]['users']);
                $users['Owner']= $ws_worker->exams[$exam]['Owner'];
                $users['date']=date('Y-m-d H:i:s');
                $connection->send(json_encode($users));
            }
        }

};

$ws_worker->onMessage = function($connection, $data) use ($ws_worker){
    global $ws_worker;
    $dataArray=json_decode($data);
    if(isset($dataArray->exam,$dataArray->user,$dataArray->idOwner)){
        if($dataArray->user->id != $dataArray->idOwner){
            file_put_contents('../exam_logs/exam_'.$dataArray->exam.'.log', $dataArray->user->id.'`'.date('Y-m-d H:i:s').' '.$dataArray->user->firstname.' '.$dataArray->user->lastname.' joined the exam'.PHP_EOL, FILE_APPEND);
        }
        $ws_worker->exams[$dataArray->exam]['users'][$dataArray->user->id]['connection']=$connection->id;
        $ws_worker->exams[$dataArray->exam]['users'][$dataArray->user->id]['user']=$dataArray->user;
        $ws_worker->exams[$dataArray->exam]['Owner']=$dataArray->idOwner;
        if (isset($ws_worker->exams[$dataArray->exam]['users'][$dataArray->idOwner]['connection']) and $dataArray->user->id != $dataArray->idOwner) {
                $id = $ws_worker->exams[$dataArray->exam]['users'][$dataArray->idOwner]['connection'];
                $connection = $ws_worker->connections[$id];
                $users['usersLogged'] = array_keys($ws_worker->exams[$dataArray->exam]['users']);
                $users['Owner']= $ws_worker->exams[$dataArray->exam]['Owner'];
                $users['logged']=$dataArray->user;
                $users['date']=date('Y-m-d H:i:s');
                $connection->send(json_encode($users));
        }

    }
    if(isset($dataArray->target,$dataArray->cheat)){
        file_put_contents('../exam_logs/exam_'.$dataArray->exam.'.log', $dataArray->user->id.'`'.date('Y-m-d H:i:s').' '.$dataArray->user->firstname.' '.$dataArray->user->lastname.' cheated '.$dataArray->cheat.'time'.PHP_EOL, FILE_APPEND);
        if(isset($ws_worker->exams[$dataArray->exam]['users'][$dataArray->target]['connection'])){
            $id=$ws_worker->exams[$dataArray->exam]['users'][$dataArray->target]['connection'];
            $connection=$ws_worker->connections[$id];
            $dataArray->date=date('Y-m-d H:i:s');
            $data=json_encode($dataArray);
            $connection->send($data);
        }
    }
    if(isset($dataArray->target,$dataArray->idPQ)){
        if(isset($ws_worker->exams[$dataArray->exam]['users'][$dataArray->target]['connection'])){
            $id=$ws_worker->exams[$dataArray->exam]['users'][$dataArray->target]['connection'];
            $connection=$ws_worker->connections[$id];
            $connection->send($data);
        }
    }
    if(isset($dataArray->target,$dataArray->message,$dataArray->exam)){
        if(isset($ws_worker->exams[$dataArray->exam]['users'][$dataArray->target]['connection'])){
            $id=$ws_worker->exams[$dataArray->exam]['users'][$dataArray->target]['connection'];
            $connection=$ws_worker->connections[$id];
            $dataArray->cdate=date('Y-m-d H:i:s');
            $connection->send(json_encode($dataArray));
        }
    }
    if(isset($dataArray->action,$dataArray->id)){
        if(isset($ws_worker->exams[$dataArray->exam]['users'][$dataArray->id]['connection'])){
            $id=$ws_worker->exams[$dataArray->exam]['users'][$dataArray->id]['connection'];
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