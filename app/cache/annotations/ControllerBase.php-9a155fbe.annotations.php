<?php

return array(
  '#namespace' => 'controllers',
  '#uses' => array (
  'Controller' => 'Ubiquity\\controllers\\Controller',
  'TranslatorManager' => 'Ubiquity\\translation\\TranslatorManager',
  'URequest' => 'Ubiquity\\utils\\http\\URequest',
  'USession' => 'Ubiquity\\utils\\http\\USession',
),
  '#traitMethodOverrides' => array (
  'controllers\\ControllerBase' => 
  array (
  ),
),
);

