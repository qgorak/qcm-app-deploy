<?php

return array(
  '#namespace' => 'controllers',
  '#uses' => array (
  'USession' => 'Ubiquity\\utils\\http\\USession',
  'AclControllerTrait' => 'Ubiquity\\security\\acl\\controllers\\AclControllerTrait',
),
  '#traitMethodOverrides' => array (
  'controllers\\ImageController' => 
  array (
  ),
),
  'controllers\\ImageController' => array(
    array('#name' => 'allow', '#type' => 'Ubiquity\\annotations\\acl\\AllowAnnotation', 'role'=>'@USER'),
    array('#name' => 'route', '#type' => 'Ubiquity\\annotations\\router\\RouteAnnotation', "image","inherited"=>true,"automated"=>true)
  ),
  'controllers\\ImageController::add' => array(
    array('#name' => 'post', '#type' => 'Ubiquity\\annotations\\router\\PostAnnotation', 'add')
  ),
);

