<?php

return array(
  '#namespace' => 'Ubiquity\\controllers\\auth',
  '#uses' => array (
  'FlashMessage' => 'Ubiquity\\utils\\flash\\FlashMessage',
),
  '#traitMethodOverrides' => array (
  'Ubiquity\\controllers\\auth\\AuthControllerVariablesTrait' => 
  array (
  ),
),
  'Ubiquity\\controllers\\auth\\AuthControllerVariablesTrait::noAccessMessage' => array(
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'FlashMessage', 'name' => 'fMessage')
  ),
  'Ubiquity\\controllers\\auth\\AuthControllerVariablesTrait::attemptsNumberMessage' => array(
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'FlashMessage', 'name' => 'fMessage'),
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'int', 'name' => 'attempsCount')
  ),
  'Ubiquity\\controllers\\auth\\AuthControllerVariablesTrait::badLoginMessage' => array(
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'FlashMessage', 'name' => 'fMessage')
  ),
  'Ubiquity\\controllers\\auth\\AuthControllerVariablesTrait::terminateMessage' => array(
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'FlashMessage', 'name' => 'fMessage')
  ),
  'Ubiquity\\controllers\\auth\\AuthControllerVariablesTrait::disconnectedMessage' => array(
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'FlashMessage', 'name' => 'fMessage')
  ),
  'Ubiquity\\controllers\\auth\\AuthControllerVariablesTrait::attemptsTimeout' => array(
    array('#name' => 'return', '#type' => 'mindplay\\annotations\\standard\\ReturnAnnotation', 'type' => 'number')
  ),
  'Ubiquity\\controllers\\auth\\AuthControllerVariablesTrait::_getBodySelector' => array(
    array('#name' => 'return', '#type' => 'mindplay\\annotations\\standard\\ReturnAnnotation', 'type' => 'string')
  ),
);

