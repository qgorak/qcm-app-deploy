<?php

return array(
  '#namespace' => 'controllers',
  '#uses' => array (
  'HtmlMessage' => 'Ajax\\semantic\\html\\collections\\HtmlMessage',
  'Router' => 'Ubiquity\\controllers\\Router',
  'URequest' => 'Ubiquity\\utils\\http\\URequest',
  'USession' => 'Ubiquity\\utils\\http\\USession',
  'Qcm' => 'models\\Qcm',
  'QcmDAOLoader' => 'services\\DAO\\QcmDAOLoader',
  'QuestionDAOLoader' => 'services\\DAO\\QuestionDAOLoader',
  'AclControllerTrait' => 'Ubiquity\\security\\acl\\controllers\\AclControllerTrait',
  'QcmUIService' => 'services\\UI\\QcmUIService',
),
  '#traitMethodOverrides' => array (
  'controllers\\QcmController' => 
  array (
  ),
),
  'controllers\\QcmController' => array(
    array('#name' => 'allow', '#type' => 'Ubiquity\\annotations\\acl\\AllowAnnotation', 'role'=>'@USER'),
    array('#name' => 'route', '#type' => 'Ubiquity\\annotations\\router\\RouteAnnotation', 'qcm','inherited'=>true, 'automated'=>true),
    array('#name' => 'property', '#type' => 'mindplay\\annotations\\standard\\PropertyAnnotation', 'type' => '\\Ajax\\php\\ubiquity\\JsUtils', 'name' => 'jquery')
  ),
  'controllers\\QcmController::$loader' => array(
    array('#name' => 'autowired', '#type' => 'Ubiquity\\annotations\\di\\AutowiredAnnotation'),
    array('#name' => 'var', '#type' => 'mindplay\\annotations\\standard\\VarAnnotation', 'type' => 'QcmDAOLoader')
  ),
  'controllers\\QcmController::setLoader' => array(
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => '\\services\\DAO\\QcmDAOLoader', 'name' => 'loader')
  ),
  'controllers\\QcmController::index' => array(
    array('#name' => 'route', '#type' => 'Ubiquity\\annotations\\router\\RouteAnnotation', '/','name'=>'qcm')
  ),
  'controllers\\QcmController::add' => array(
    array('#name' => 'get', '#type' => 'Ubiquity\\annotations\\router\\GetAnnotation', "add","name"=>'qcm.add')
  ),
  'controllers\\QcmController::addQuestionToQcm' => array(
    array('#name' => 'get', '#type' => 'Ubiquity\\annotations\\router\\GetAnnotation', "addQuestion/{id}","name"=>"qcm.add.question")
  ),
  'controllers\\QcmController::displayQuestionBankImport' => array(
    array('#name' => 'get', '#type' => 'Ubiquity\\annotations\\router\\GetAnnotation', "questionBankImport","name"=>'qcm.display.bank')
  ),
  'controllers\\QcmController::removeQuestionToQcm' => array(
    array('#name' => 'delete', '#type' => 'Ubiquity\\annotations\\router\\DeleteAnnotation', "deleteQuestion/{id}","name"=>"qcm.delete.question")
  ),
  'controllers\\QcmController::filterQuestionBank' => array(
    array('#name' => 'post', '#type' => 'Ubiquity\\annotations\\router\\PostAnnotation', "filterQuestionBank","name"=>"qcm.filter")
  ),
  'controllers\\QcmController::delete' => array(
    array('#name' => 'get', '#type' => 'Ubiquity\\annotations\\router\\GetAnnotation', "delete/{id}",'name'=>'qcm.delete')
  ),
  'controllers\\QcmController::preview' => array(
    array('#name' => 'get', '#type' => 'Ubiquity\\annotations\\router\\GetAnnotation', "preview/{id}","name"=>"qcm.preview")
  ),
  'controllers\\QcmController::submit' => array(
    array('#name' => 'post', '#type' => 'Ubiquity\\annotations\\router\\PostAnnotation', "add","name"=>"qcm.submit")
  ),
);

