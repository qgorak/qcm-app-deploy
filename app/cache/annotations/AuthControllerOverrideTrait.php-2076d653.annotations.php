<?php

return array(
  '#namespace' => 'Ubiquity\\controllers\\auth',
  '#uses' => array (
  'ClassUtils' => 'Ubiquity\\cache\\ClassUtils',
  'USession' => 'Ubiquity\\utils\\http\\USession',
  'UCookie' => 'Ubiquity\\utils\\http\\UCookie',
),
  '#traitMethodOverrides' => array (
  'Ubiquity\\controllers\\auth\\AuthControllerOverrideTrait' => 
  array (
  ),
),
  'Ubiquity\\controllers\\auth\\AuthControllerOverrideTrait::_getBaseRoute' => array(
    array('#name' => 'return', '#type' => 'mindplay\\annotations\\standard\\ReturnAnnotation', 'type' => 'string')
  ),
  'Ubiquity\\controllers\\auth\\AuthControllerOverrideTrait::onConnect' => array(
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'object', 'name' => 'connected')
  ),
  'Ubiquity\\controllers\\auth\\AuthControllerOverrideTrait::_getUserSessionKey' => array(
    array('#name' => 'return', '#type' => 'mindplay\\annotations\\standard\\ReturnAnnotation', 'type' => 'string')
  ),
  'Ubiquity\\controllers\\auth\\AuthControllerOverrideTrait::_getActiveUser' => array(
    array('#name' => 'return', '#type' => 'mindplay\\annotations\\standard\\ReturnAnnotation', 'type' => 'string')
  ),
  'Ubiquity\\controllers\\auth\\AuthControllerOverrideTrait::_isValidUser' => array(
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'string', 'name' => 'action')
  ),
  'Ubiquity\\controllers\\auth\\AuthControllerOverrideTrait::toCookie' => array(
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'object', 'name' => 'connected')
  ),
  'Ubiquity\\controllers\\auth\\AuthControllerOverrideTrait::fromCookie' => array(
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'string', 'name' => 'cookie')
  ),
  'Ubiquity\\controllers\\auth\\AuthControllerOverrideTrait::rememberMe' => array(
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'object', 'name' => 'connected')
  ),
  'Ubiquity\\controllers\\auth\\AuthControllerOverrideTrait::getCookieUser' => array(
    array('#name' => 'return', '#type' => 'mindplay\\annotations\\standard\\ReturnAnnotation', 'type' => 'NULL|string')
  ),
  'Ubiquity\\controllers\\auth\\AuthControllerOverrideTrait::getFiles' => array(
    array('#name' => 'return', '#type' => 'mindplay\\annotations\\standard\\ReturnAnnotation', 'type' => 'AuthFiles')
  ),
);

