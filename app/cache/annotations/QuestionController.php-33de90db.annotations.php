<?php

return array(
  '#namespace' => 'controllers',
  '#uses' => array (
  'Router' => 'Ubiquity\\controllers\\Router',
  'DAO' => 'Ubiquity\\orm\\DAO',
  'AclControllerTrait' => 'Ubiquity\\security\\acl\\controllers\\AclControllerTrait',
  'URequest' => 'Ubiquity\\utils\\http\\URequest',
  'USession' => 'Ubiquity\\utils\\http\\USession',
  'Answer' => 'models\\Answer',
  'Question' => 'models\\Question',
  'Tag' => 'models\\Tag',
  'QuestionDAOLoader' => 'services\\DAO\\QuestionDAOLoader',
  'QuestionUIService' => 'services\\UI\\QuestionUIService',
),
  '#traitMethodOverrides' => array (
  'controllers\\QuestionController' => 
  array (
  ),
),
  'controllers\\QuestionController' => array(
    array('#name' => 'allow', '#type' => 'Ubiquity\\annotations\\acl\\AllowAnnotation', 'role'=>'@USER'),
    array('#name' => 'route', '#type' => 'Ubiquity\\annotations\\router\\RouteAnnotation', 'question','inherited'=>true,'automated'=>true),
    array('#name' => 'property', '#type' => 'mindplay\\annotations\\standard\\PropertyAnnotation', 'type' => '\\Ajax\\php\\ubiquity\\JsUtils', 'name' => 'jquery')
  ),
  'controllers\\QuestionController::$loader' => array(
    array('#name' => 'autowired', '#type' => 'Ubiquity\\annotations\\di\\AutowiredAnnotation'),
    array('#name' => 'var', '#type' => 'mindplay\\annotations\\standard\\VarAnnotation', 'type' => 'QuestionDAOLoader')
  ),
  'controllers\\QuestionController::setLoader' => array(
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => '\\services\\DAO\\QuestionDAOLoader', 'name' => 'loader')
  ),
  'controllers\\QuestionController::index' => array(
    array('#name' => 'route', '#type' => 'Ubiquity\\annotations\\router\\RouteAnnotation', '/','name'=>'question')
  ),
  'controllers\\QuestionController::add' => array(
    array('#name' => 'get', '#type' => 'Ubiquity\\annotations\\router\\GetAnnotation', "add",'name'=>'question.add')
  ),
  'controllers\\QuestionController::delete' => array(
    array('#name' => 'get', '#type' => 'Ubiquity\\annotations\\router\\GetAnnotation', "delete/{id}",'name'=>'question.delete')
  ),
  'controllers\\QuestionController::patch' => array(
    array('#name' => 'get', '#type' => 'Ubiquity\\annotations\\router\\GetAnnotation', "patch/{id}",'name'=>'question.patch')
  ),
  'controllers\\QuestionController::preview' => array(
    array('#name' => 'get', '#type' => 'Ubiquity\\annotations\\router\\GetAnnotation', "preview/{id}","name"=>"question.preview")
  ),
  'controllers\\QuestionController::getByTags' => array(
    array('#name' => 'post', '#type' => 'Ubiquity\\annotations\\router\\PostAnnotation', "getByTags","name"=>"question.getBy.tags")
  ),
  'controllers\\QuestionController::displayMyQuestions' => array(
    array('#name' => 'get', '#type' => 'Ubiquity\\annotations\\router\\GetAnnotation', "displayMyQuestions","name"=>"question.my")
  ),
  'controllers\\QuestionController::submit' => array(
    array('#name' => 'post', '#type' => 'Ubiquity\\annotations\\router\\PostAnnotation', "add","name"=>"question.submit")
  ),
  'controllers\\QuestionController::submitPatch' => array(
    array('#name' => 'post', '#type' => 'Ubiquity\\annotations\\router\\PostAnnotation', "submitpatch","name"=>"question.submit.patch")
  ),
);

