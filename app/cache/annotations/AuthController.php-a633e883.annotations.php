<?php

return array(
  '#namespace' => 'Ubiquity\\controllers\\auth',
  '#uses' => array (
  'USession' => 'Ubiquity\\utils\\http\\USession',
  'URequest' => 'Ubiquity\\utils\\http\\URequest',
  'FlashMessage' => 'Ubiquity\\utils\\flash\\FlashMessage',
  'Controller' => 'Ubiquity\\controllers\\Controller',
  'UResponse' => 'Ubiquity\\utils\\http\\UResponse',
  'UString' => 'Ubiquity\\utils\\base\\UString',
  'Startup' => 'Ubiquity\\controllers\\Startup',
  'Javascript' => 'Ajax\\service\\Javascript',
  'UCookie' => 'Ubiquity\\utils\\http\\UCookie',
  'InsertJqueryTrait' => 'Ubiquity\\controllers\\semantic\\InsertJqueryTrait',
),
  '#traitMethodOverrides' => array (
  'Ubiquity\\controllers\\auth\\AuthController' => 
  array (
  ),
),
  'Ubiquity\\controllers\\auth\\AuthController' => array(
    array('#name' => 'property', '#type' => 'mindplay\\annotations\\standard\\PropertyAnnotation', 'type' => '\\Ajax\\php\\ubiquity\\JsUtils', 'name' => 'jquery')
  ),
  'Ubiquity\\controllers\\auth\\AuthController::$authFiles' => array(
    array('#name' => 'var', '#type' => 'mindplay\\annotations\\standard\\VarAnnotation', 'type' => 'AuthFiles')
  ),
  'Ubiquity\\controllers\\auth\\AuthController::noAccess' => array(
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'array|string', 'name' => 'urlParts')
  ),
  'Ubiquity\\controllers\\auth\\AuthController::info' => array(
    array('#name' => 'return', '#type' => 'mindplay\\annotations\\standard\\ReturnAnnotation', 'type' => 'string|null')
  ),
  'Ubiquity\\controllers\\auth\\AuthController::_setNoAccessMsg' => array(
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'string', 'name' => 'content'),
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'string', 'name' => 'title'),
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'string', 'name' => 'type'),
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'string', 'name' => 'icon')
  ),
  'Ubiquity\\controllers\\auth\\AuthController::_setLoginCaption' => array(
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'string', 'name' => '_loginCaption')
  ),
  'Ubiquity\\controllers\\auth\\AuthController::_forward' => array(
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'string', 'name' => 'url')
  ),
);

