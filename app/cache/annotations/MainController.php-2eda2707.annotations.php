<?php

return array(
  '#namespace' => 'controllers',
  '#uses' => array (
  'AclControllerTrait' => 'Ubiquity\\security\\acl\\controllers\\AclControllerTrait',
  'USession' => 'Ubiquity\\utils\\http\\USession',
),
  '#traitMethodOverrides' => array (
  'controllers\\MainController' => 
  array (
  ),
),
  'controllers\\MainController' => array(
    array('#name' => 'allow', '#type' => 'Ubiquity\\annotations\\acl\\AllowAnnotation', 'role'=>['@GUEST','@USER']),
    array('#name' => 'property', '#type' => 'mindplay\\annotations\\standard\\PropertyAnnotation', 'type' => '\\Ajax\\php\\ubiquity\\JsUtils', 'name' => 'jquery')
  ),
  'controllers\\MainController::index' => array(
    array('#name' => 'route', '#type' => 'Ubiquity\\annotations\\router\\RouteAnnotation', '_default')
  ),
  'controllers\\MainController::changeLanguage' => array(
    array('#name' => 'route', '#type' => 'Ubiquity\\annotations\\router\\RouteAnnotation', 'change/{lang}','name'=>'changeLanguage'),
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'mixed', 'name' => 'lang')
  ),
);

