<?php

return array(
  '#namespace' => 'Ubiquity\\security\\acl\\controllers',
  '#uses' => array (
  'AclManager' => 'Ubiquity\\security\\acl\\AclManager',
),
  '#traitMethodOverrides' => array (
  'Ubiquity\\security\\acl\\controllers\\AclControllerTrait' => 
  array (
  ),
),
  'Ubiquity\\security\\acl\\controllers\\AclControllerTrait::isValid' => array(
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'string', 'name' => 'action'),
    array('#name' => 'return', '#type' => 'mindplay\\annotations\\standard\\ReturnAnnotation', 'type' => 'boolean')
  ),
);

