<?php

return array(
  '#namespace' => 'controllers',
  '#uses' => array (
  'Router' => 'Ubiquity\\controllers\\Router',
  'Startup' => 'Ubiquity\\controllers\\Startup',
  'DAO' => 'Ubiquity\\orm\\DAO',
  'AclControllerTrait' => 'Ubiquity\\security\\acl\\controllers\\AclControllerTrait',
  'URequest' => 'Ubiquity\\utils\\http\\URequest',
  'USession' => 'Ubiquity\\utils\\http\\USession',
  'DateTime' => 'DateTime',
  'Exam' => 'models\\Exam',
  'Group' => 'models\\Group',
  'Qcm' => 'models\\Qcm',
  'ExamDAOLoader' => 'services\\DAO\\ExamDAOLoader',
  'Useranswer' => 'models\\Useranswer',
  'datePickerTranslator' => 'services\\datePickerTranslator',
  'ExamUIService' => 'services\\UI\\ExamUIService',
),
  '#traitMethodOverrides' => array (
  'controllers\\ExamController' => 
  array (
  ),
),
  'controllers\\ExamController' => array(
    array('#name' => 'allow', '#type' => 'Ubiquity\\annotations\\acl\\AllowAnnotation', 'role'=>'@USER'),
    array('#name' => 'route', '#type' => 'Ubiquity\\annotations\\router\\RouteAnnotation', 'exam','inherited'=>true,'automated'=>true),
    array('#name' => 'property', '#type' => 'mindplay\\annotations\\standard\\PropertyAnnotation', 'type' => '\\Ajax\\php\\ubiquity\\JsUtils', 'name' => 'jquery')
  ),
  'controllers\\ExamController::$loader' => array(
    array('#name' => 'autowired', '#type' => 'Ubiquity\\annotations\\di\\AutowiredAnnotation'),
    array('#name' => 'var', '#type' => 'mindplay\\annotations\\standard\\VarAnnotation', 'type' => 'ExamDAOLoader')
  ),
  'controllers\\ExamController::setLoader' => array(
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => '\\services\\DAO\\ExamDAOLoader', 'name' => 'loader')
  ),
  'controllers\\ExamController::index' => array(
    array('#name' => 'route', '#type' => 'Ubiquity\\annotations\\router\\RouteAnnotation', '/','name'=>'exam')
  ),
  'controllers\\ExamController::add' => array(
    array('#name' => 'get', '#type' => 'Ubiquity\\annotations\\router\\GetAnnotation', 'add','name'=>'examAdd')
  ),
  'controllers\\ExamController::addSubmit' => array(
    array('#name' => 'post', '#type' => 'Ubiquity\\annotations\\router\\PostAnnotation', 'add','name'=>'examAddSubmit')
  ),
  'controllers\\ExamController::getExam' => array(
    array('#name' => 'get', '#type' => 'Ubiquity\\annotations\\router\\GetAnnotation', 'get/{id}','name'=>'exam.get')
  ),
  'controllers\\ExamController::ExamStart' => array(
    array('#name' => 'get', '#type' => 'Ubiquity\\annotations\\router\\GetAnnotation', 'start/{id}','name'=>'exam.start')
  ),
  'controllers\\ExamController::nextQuestion' => array(
    array('#name' => 'post', '#type' => 'Ubiquity\\annotations\\router\\PostAnnotation', 'next','name'=>'exam.next')
  ),
  'controllers\\ExamController::ExamOverseePage' => array(
    array('#name' => 'get', '#type' => 'Ubiquity\\annotations\\router\\GetAnnotation', 'oversee/{id}','name'=>'examStart')
  ),
);

