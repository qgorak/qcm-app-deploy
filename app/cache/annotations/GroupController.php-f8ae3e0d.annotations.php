<?php

return array(
  '#namespace' => 'controllers',
  '#uses' => array (
  'Router' => 'Ubiquity\\controllers\\Router',
  'DAO' => 'Ubiquity\\orm\\DAO',
  'URequest' => 'Ubiquity\\utils\\http\\URequest',
  'Group' => 'models\\Group',
  'GroupDAOLoader' => 'services\\DAO\\GroupDAOLoader',
  'User' => 'models\\User',
  'USession' => 'Ubiquity\\utils\\http\\USession',
  'Usergroup' => 'models\\Usergroup',
  'TranslatorManager' => 'Ubiquity\\translation\\TranslatorManager',
  'AclControllerTrait' => 'Ubiquity\\security\\acl\\controllers\\AclControllerTrait',
  'GroupUIService' => 'services\\UI\\GroupUIService',
),
  '#traitMethodOverrides' => array (
  'controllers\\GroupController' => 
  array (
  ),
),
  'controllers\\GroupController' => array(
    array('#name' => 'allow', '#type' => 'Ubiquity\\annotations\\acl\\AllowAnnotation', 'role'=>'@USER'),
    array('#name' => 'route', '#type' => 'Ubiquity\\annotations\\router\\RouteAnnotation', 'group','inherited'=>true,'automated'=>true),
    array('#name' => 'property', '#type' => 'mindplay\\annotations\\standard\\PropertyAnnotation', 'type' => '\\Ajax\\php\\ubiquity\\JsUtils', 'name' => 'jquery')
  ),
  'controllers\\GroupController::$loader' => array(
    array('#name' => 'autowired', '#type' => 'Ubiquity\\annotations\\di\\AutowiredAnnotation'),
    array('#name' => 'var', '#type' => 'mindplay\\annotations\\standard\\VarAnnotation', 'type' => 'GroupDAOLoader')
  ),
  'controllers\\GroupController::setLoader' => array(
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => '\\services\\DAO\\GroupDAOLoader', 'name' => 'loader')
  ),
  'controllers\\GroupController::index' => array(
    array('#name' => 'route', '#type' => 'Ubiquity\\annotations\\router\\RouteAnnotation', '/','name'=>'group')
  ),
  'controllers\\GroupController::addSubmit' => array(
    array('#name' => 'post', '#type' => 'Ubiquity\\annotations\\router\\PostAnnotation', "add","name"=>"GroupAddSubmit")
  ),
  'controllers\\GroupController::joinSubmit' => array(
    array('#name' => 'post', '#type' => 'Ubiquity\\annotations\\router\\PostAnnotation', "join","name"=>"joinSubmit")
  ),
  'controllers\\GroupController::viewGroup' => array(
    array('#name' => 'get', '#type' => 'Ubiquity\\annotations\\router\\GetAnnotation', 'view/{id}','name'=>'groupView'),
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'mixed', 'name' => 'id')
  ),
  'controllers\\GroupController::groupDelete' => array(
    array('#name' => 'get', '#type' => 'Ubiquity\\annotations\\router\\GetAnnotation', 'delete/{id}','name'=>'groupDelete'),
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'string', 'name' => 'id')
  ),
  'controllers\\GroupController::getUserDemand' => array(
    array('#name' => 'get', '#type' => 'Ubiquity\\annotations\\router\\GetAnnotation', 'demand/{id}','name'=>'groupDemand'),
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'mixed', 'name' => 'id')
  ),
  'controllers\\GroupController::acceptDemand' => array(
    array('#name' => 'get', '#type' => 'Ubiquity\\annotations\\router\\GetAnnotation', 'valid/{bool}/{groupId}/{userId}','name'=>'groupDemandAccept'),
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'mixed', 'name' => 'userId'),
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'mixed', 'name' => 'groupId'),
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'mixed', 'name' => 'bool')
  ),
  'controllers\\GroupController::banUser' => array(
    array('#name' => 'post', '#type' => 'Ubiquity\\annotations\\router\\PostAnnotation', 'ban','name'=>'banUser')
  ),
);

