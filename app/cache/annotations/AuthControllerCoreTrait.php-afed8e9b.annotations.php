<?php

return array(
  '#namespace' => 'Ubiquity\\controllers\\auth',
  '#uses' => array (
  'URequest' => 'Ubiquity\\utils\\http\\URequest',
  'USession' => 'Ubiquity\\utils\\http\\USession',
  'FlashMessage' => 'Ubiquity\\utils\\flash\\FlashMessage',
),
  '#traitMethodOverrides' => array (
  'Ubiquity\\controllers\\auth\\AuthControllerCoreTrait' => 
  array (
  ),
),
  'Ubiquity\\controllers\\auth\\AuthControllerCoreTrait' => array(
    array('#name' => 'property', '#type' => 'mindplay\\annotations\\standard\\PropertyAnnotation', 'type' => 'AuthFiles', 'name' => 'authFiles'),
    array('#name' => 'property', '#type' => 'mindplay\\annotations\\standard\\PropertyAnnotation', 'type' => 'string', 'name' => '_loginCaption'),
    array('#name' => 'property', '#type' => 'mindplay\\annotations\\standard\\PropertyAnnotation', 'type' => '\\Ajax\\php\\ubiquity\\JsUtils', 'name' => 'jquery')
  ),
);

