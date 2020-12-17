<?php

return array(
  '#namespace' => 'controllers',
  '#uses' => array (
  'Router' => 'Ubiquity\\controllers\\Router',
  'AclControllerTrait' => 'Ubiquity\\security\\acl\\controllers\\AclControllerTrait',
  'TranslatorManager' => 'Ubiquity\\translation\\TranslatorManager',
  'URequest' => 'Ubiquity\\utils\\http\\URequest',
  'USession' => 'Ubiquity\\utils\\http\\USession',
  'UserDAOLoader' => 'services\\UserDAOLoader',
),
  '#traitMethodOverrides' => array (
  'controllers\\UserController' => 
  array (
  ),
),
  'controllers\\UserController' => array(
    array('#name' => 'allow', '#type' => 'Ubiquity\\annotations\\acl\\AllowAnnotation', 'role'=>'@USER'),
    array('#name' => 'route', '#type' => 'Ubiquity\\annotations\\router\\RouteAnnotation', 'user','inherited'=>true,'automated'=>true),
    array('#name' => 'property', '#type' => 'mindplay\\annotations\\standard\\PropertyAnnotation', 'type' => '\\Ajax\\php\\ubiquity\\JsUtils', 'name' => 'jquery')
  ),
  'controllers\\UserController::$loader' => array(
    array('#name' => 'autowired', '#type' => 'Ubiquity\\annotations\\di\\AutowiredAnnotation'),
    array('#name' => 'var', '#type' => 'mindplay\\annotations\\standard\\VarAnnotation', 'type' => 'UserDAOLoader')
  ),
  'controllers\\UserController::setLoader' => array(
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => '\\services\\UserDAOLoader', 'name' => 'loader')
  ),
  'controllers\\UserController::index' => array(
    array('#name' => 'route', '#type' => 'Ubiquity\\annotations\\router\\RouteAnnotation', '/','name'=>'user')
  ),
  'controllers\\UserController::langSubmit' => array(
    array('#name' => 'post', '#type' => 'Ubiquity\\annotations\\router\\PostAnnotation', 'lang','name'=>'langSubmit')
  ),
);

