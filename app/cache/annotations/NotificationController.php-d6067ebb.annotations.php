<?php

return array(
  '#namespace' => 'controllers',
  '#uses' => array (
  'Router' => 'Ubiquity\\controllers\\Router',
  'NotificationDAOLoader' => 'services\\NotificationDAOLoader',
  'AclControllerTrait' => 'Ubiquity\\security\\acl\\controllers\\AclControllerTrait',
  'USession' => 'Ubiquity\\utils\\http\\USession',
),
  '#traitMethodOverrides' => array (
  'controllers\\NotificationController' => 
  array (
  ),
),
  'controllers\\NotificationController' => array(
    array('#name' => 'allow', '#type' => 'Ubiquity\\annotations\\acl\\AllowAnnotation', 'role'=>'@USER'),
    array('#name' => 'route', '#type' => 'Ubiquity\\annotations\\router\\RouteAnnotation', 'notification','inherited'=>true,'automated'=>true),
    array('#name' => 'property', '#type' => 'mindplay\\annotations\\standard\\PropertyAnnotation', 'type' => '\\Ajax\\php\\ubiquity\\JsUtils', 'name' => 'jquery')
  ),
  'controllers\\NotificationController::$loader' => array(
    array('#name' => 'autowired', '#type' => 'Ubiquity\\annotations\\di\\AutowiredAnnotation'),
    array('#name' => 'var', '#type' => 'mindplay\\annotations\\standard\\VarAnnotation', 'type' => 'NotificationDAOLoader')
  ),
  'controllers\\NotificationController::setLoader' => array(
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => '\\services\\GroupDAOLoader', 'name' => 'loader')
  ),
  'controllers\\NotificationController::index' => array(
    array('#name' => 'route', '#type' => 'Ubiquity\\annotations\\router\\RouteAnnotation', '/','name'=>'notification')
  ),
  'controllers\\NotificationController::refresh' => array(
    array('#name' => 'get', '#type' => 'Ubiquity\\annotations\\router\\GetAnnotation', 'refresh','name'=>'refresh')
  ),
  'controllers\\NotificationController::json' => array(
    array('#name' => 'get', '#type' => 'Ubiquity\\annotations\\router\\GetAnnotation', 'json','name'=>'notification.json')
  ),
);

