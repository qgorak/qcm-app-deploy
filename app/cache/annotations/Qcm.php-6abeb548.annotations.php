<?php

return array(
  '#namespace' => 'models',
  '#uses' => array (
),
  '#traitMethodOverrides' => array (
  'models\\Qcm' => 
  array (
  ),
),
  'models\\Qcm' => array(
    array('#name' => 'table', '#type' => 'Ubiquity\\annotations\\TableAnnotation', 'qcm')
  ),
  'models\\Qcm::$id' => array(
    array('#name' => 'id', '#type' => 'Ubiquity\\annotations\\IdAnnotation'),
    array('#name' => 'column', '#type' => 'Ubiquity\\annotations\\ColumnAnnotation', "name"=>"id","nullable"=>false,"dbType"=>"int(11)"),
    array('#name' => 'validator', '#type' => 'Ubiquity\\annotations\\ValidatorAnnotation', "id","constraints"=>array("autoinc"=>true))
  ),
  'models\\Qcm::$name' => array(
    array('#name' => 'column', '#type' => 'Ubiquity\\annotations\\ColumnAnnotation', "name"=>"name","nullable"=>true,"dbType"=>"varchar(42)"),
    array('#name' => 'validator', '#type' => 'Ubiquity\\annotations\\ValidatorAnnotation', "length","constraints"=>array("max"=>42))
  ),
  'models\\Qcm::$description' => array(
    array('#name' => 'column', '#type' => 'Ubiquity\\annotations\\ColumnAnnotation', "name"=>"description","nullable"=>true,"dbType"=>"varchar(42)"),
    array('#name' => 'validator', '#type' => 'Ubiquity\\annotations\\ValidatorAnnotation', "length","constraints"=>array("max"=>42))
  ),
  'models\\Qcm::$cdate' => array(
    array('#name' => 'column', '#type' => 'Ubiquity\\annotations\\ColumnAnnotation', "name"=>"cdate","nullable"=>true,"dbType"=>"datetime"),
    array('#name' => 'validator', '#type' => 'Ubiquity\\annotations\\ValidatorAnnotation', "type","dateTime"),
    array('#name' => 'transformer', '#type' => 'Ubiquity\\annotations\\TransformerAnnotation', "name"=>"datetime")
  ),
  'models\\Qcm::$exams' => array(
    array('#name' => 'oneToMany', '#type' => 'Ubiquity\\annotations\\OneToManyAnnotation', "mappedBy"=>"qcm","className"=>"models\\Exam")
  ),
  'models\\Qcm::$user' => array(
    array('#name' => 'manyToOne', '#type' => 'Ubiquity\\annotations\\ManyToOneAnnotation'),
    array('#name' => 'joinColumn', '#type' => 'Ubiquity\\annotations\\JoinColumnAnnotation', "className"=>"models\\User","name"=>"idUser","nullable"=>false)
  ),
  'models\\Qcm::$questions' => array(
    array('#name' => 'manyToMany', '#type' => 'Ubiquity\\annotations\\ManyToManyAnnotation', "targetEntity"=>"models\\Question","inversedBy"=>"qcms"),
    array('#name' => 'joinTable', '#type' => 'Ubiquity\\annotations\\JoinTableAnnotation', "name"=>"qcmquestion")
  ),
);

