<?php

return array(
  '#namespace' => 'controllers',
  '#uses' => array (
  'DAO' => 'Ubiquity\\orm\\DAO',
  'URequest' => 'Ubiquity\\utils\\http\\URequest',
  'USession' => 'Ubiquity\\utils\\http\\USession',
  'User' => 'models\\User',
  'AuthUIService' => 'services\\UI\\AuthUIService',
  'Crypt' => 'Ubiquity\\contents\\transformation\\transformers\\Crypt',
),
  '#traitMethodOverrides' => array (
  'controllers\\BaseAuthController' => 
  array (
  ),
),
  'controllers\\BaseAuthController::loginform' => array(
    array('#name' => 'get', '#type' => 'Ubiquity\\annotations\\router\\GetAnnotation', "/loginForm",'name'=>'loginform')
  ),
  'controllers\\BaseAuthController::registerform' => array(
    array('#name' => 'get', '#type' => 'Ubiquity\\annotations\\router\\GetAnnotation', "/registerForm",'name'=>'registerform')
  ),
  'controllers\\BaseAuthController::loginPost' => array(
    array('#name' => 'post', '#type' => 'Ubiquity\\annotations\\router\\PostAnnotation', "/login",'name'=>'loginPost')
  ),
  'controllers\\BaseAuthController::terminate' => array(
    array('#name' => 'get', '#type' => 'Ubiquity\\annotations\\router\\GetAnnotation', "/terminate","name"=>"terminate")
  ),
  'controllers\\BaseAuthController::registerPost' => array(
    array('#name' => 'post', '#type' => 'Ubiquity\\annotations\\router\\PostAnnotation', "/register",'name'=>'registerPost')
  ),
);

