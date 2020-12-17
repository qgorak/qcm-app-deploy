<?php

return array(
  '#namespace' => 'Ubiquity\\annotations\\acl',
  '#uses' => array (
  'BaseAnnotation' => 'Ubiquity\\annotations\\BaseAnnotation',
),
  '#traitMethodOverrides' => array (
  'Ubiquity\\annotations\\acl\\AllowAnnotation' => 
  array (
  ),
),
  'Ubiquity\\annotations\\acl\\AllowAnnotation' => array(
    array('#name' => 'usage', '#type' => 'mindplay\\annotations\\UsageAnnotation', 'method'=>true,'class'=>true,'inherited'=>true,'multiple'=>true)
  ),
);

