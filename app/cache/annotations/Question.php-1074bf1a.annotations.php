<?php

return array(
  '#namespace' => 'models',
  '#uses' => array (
),
  '#traitMethodOverrides' => array (
  'models\\Question' => 
  array (
  ),
),
  'models\\Question' => array(
    array('#name' => 'table', '#type' => 'Ubiquity\\annotations\\TableAnnotation', 'question')
  ),
  'models\\Question::$id' => array(
    array('#name' => 'id', '#type' => 'Ubiquity\\annotations\\IdAnnotation'),
    array('#name' => 'column', '#type' => 'Ubiquity\\annotations\\ColumnAnnotation', "name"=>"id","nullable"=>false,"dbType"=>"int(11)"),
    array('#name' => 'validator', '#type' => 'Ubiquity\\annotations\\ValidatorAnnotation', "id","constraints"=>array("autoinc"=>true))
  ),
  'models\\Question::$caption' => array(
    array('#name' => 'column', '#type' => 'Ubiquity\\annotations\\ColumnAnnotation', "name"=>"caption","nullable"=>true,"dbType"=>"varchar(42)"),
    array('#name' => 'validator', '#type' => 'Ubiquity\\annotations\\ValidatorAnnotation', "length","constraints"=>array("max"=>42))
  ),
  'models\\Question::$ckcontent' => array(
    array('#name' => 'column', '#type' => 'Ubiquity\\annotations\\ColumnAnnotation', "name"=>"ckcontent","nullable"=>true,"dbType"=>"text")
  ),
  'models\\Question::$points' => array(
    array('#name' => 'column', '#type' => 'Ubiquity\\annotations\\ColumnAnnotation', "name"=>"points","nullable"=>true,"dbType"=>"int(11)")
  ),
  'models\\Question::$idTypeq' => array(
    array('#name' => 'column', '#type' => 'Ubiquity\\annotations\\ColumnAnnotation', "name"=>"idTypeq","nullable"=>true,"dbType"=>"int(11)")
  ),
  'models\\Question::$answers' => array(
    array('#name' => 'oneToMany', '#type' => 'Ubiquity\\annotations\\OneToManyAnnotation', "mappedBy"=>"question","className"=>"models\\Answer")
  ),
  'models\\Question::$useranswers' => array(
    array('#name' => 'oneToMany', '#type' => 'Ubiquity\\annotations\\OneToManyAnnotation', "mappedBy"=>"question","className"=>"models\\Useranswer")
  ),
  'models\\Question::$user' => array(
    array('#name' => 'manyToOne', '#type' => 'Ubiquity\\annotations\\ManyToOneAnnotation'),
    array('#name' => 'joinColumn', '#type' => 'Ubiquity\\annotations\\JoinColumnAnnotation', "className"=>"models\\User","name"=>"idUser","nullable"=>false)
  ),
  'models\\Question::$qcms' => array(
    array('#name' => 'manyToMany', '#type' => 'Ubiquity\\annotations\\ManyToManyAnnotation', "targetEntity"=>"models\\Qcm","inversedBy"=>"questions"),
    array('#name' => 'joinTable', '#type' => 'Ubiquity\\annotations\\JoinTableAnnotation', "name"=>"qcmquestion")
  ),
  'models\\Question::$tags' => array(
    array('#name' => 'manyToMany', '#type' => 'Ubiquity\\annotations\\ManyToManyAnnotation', "targetEntity"=>"models\\Tag","inversedBy"=>"questions"),
    array('#name' => 'joinTable', '#type' => 'Ubiquity\\annotations\\JoinTableAnnotation', "name"=>"questiontag")
  ),
);

