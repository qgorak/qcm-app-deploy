<?php

return array(
  '#namespace' => 'controllers',
  '#uses' => array (
  'DAO' => 'Ubiquity\\orm\\DAO',
  'URequest' => 'Ubiquity\\utils\\http\\URequest',
  'USession' => 'Ubiquity\\utils\\http\\USession',
  'Tag' => 'models\\Tag',
  'User' => 'models\\User',
  'UIService' => 'services\\UIService',
  'AclControllerTrait' => 'Ubiquity\\security\\acl\\controllers\\AclControllerTrait',
),
  '#traitMethodOverrides' => array (
  'controllers\\TagController' => 
  array (
  ),
),
  'controllers\\TagController' => array(
    array('#name' => 'allow', '#type' => 'Ubiquity\\annotations\\acl\\AllowAnnotation', 'role'=>'@USER'),
    array('#name' => 'route', '#type' => 'Ubiquity\\annotations\\router\\RouteAnnotation', 'tag','inherited'=>true, 'automated'=>true),
    array('#name' => 'property', '#type' => 'mindplay\\annotations\\standard\\PropertyAnnotation', 'type' => '\\Ajax\\php\\ubiquity\\JsUtils', 'name' => 'jquery')
  ),
  'controllers\\TagController::my' => array(
    array('#name' => 'get', '#type' => 'Ubiquity\\annotations\\router\\GetAnnotation', "my","name"=>'tag.my')
  ),
  'controllers\\TagController::submit' => array(
    array('#name' => 'post', '#type' => 'Ubiquity\\annotations\\router\\PostAnnotation', "submit","name"=>'tag.submit')
  ),
);

