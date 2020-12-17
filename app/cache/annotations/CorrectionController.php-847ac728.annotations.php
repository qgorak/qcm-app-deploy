<?php

return array(
  '#namespace' => 'controllers',
  '#uses' => array (
  'FormLogin' => 'Ajax\\semantic\\widgets\\business\\user\\FormLogin',
  'DAO' => 'Ubiquity\\orm\\DAO',
  'AclControllerTrait' => 'Ubiquity\\security\\acl\\controllers\\AclControllerTrait',
  'Useranswer' => 'models\\Useranswer',
  'CorrectionUIService' => 'services\\CorrectionUIService',
  'ExamDAOLoader' => 'services\\ExamDAOLoader',
  'Answer' => 'models\\Answer',
  'URequest' => 'Ubiquity\\utils\\http\\URequest',
),
  '#traitMethodOverrides' => array (
  'controllers\\CorrectionController' => 
  array (
  ),
),
  'controllers\\CorrectionController' => array(
    array('#name' => 'allow', '#type' => 'Ubiquity\\annotations\\acl\\AllowAnnotation', 'role'=>'@USER'),
    array('#name' => 'route', '#type' => 'Ubiquity\\annotations\\router\\RouteAnnotation', 'Correction','inherited'=>true,'automated'=>true),
    array('#name' => 'property', '#type' => 'mindplay\\annotations\\standard\\PropertyAnnotation', 'type' => '\\Ajax\\php\\ubiquity\\JsUtils', 'name' => 'jquery')
  ),
  'controllers\\CorrectionController::$loader' => array(
    array('#name' => 'autowired', '#type' => 'Ubiquity\\annotations\\di\\AutowiredAnnotation'),
    array('#name' => 'var', '#type' => 'mindplay\\annotations\\standard\\VarAnnotation', 'type' => 'ExamDAOLoader')
  ),
  'controllers\\CorrectionController::setLoader' => array(
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => '\\services\\ExamDAOLoader', 'name' => 'loader')
  ),
  'controllers\\CorrectionController::result' => array(
    array('#name' => 'route', '#type' => 'Ubiquity\\annotations\\router\\RouteAnnotation', 'myresult/{idExam}/{idUser}','name'=>'Correction.myExam')
  ),
  'controllers\\CorrectionController::correctAnswer' => array(
    array('#name' => 'post', '#type' => 'Ubiquity\\annotations\\router\\PostAnnotation', 'correctAnswer','name'=>'correct.answer')
  ),
);

