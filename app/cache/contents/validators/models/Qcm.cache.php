<?php
return array("id"=>array(array("type"=>"id","constraints"=>array("autoinc"=>true))),"name"=>array(array("type"=>"length","constraints"=>array("max"=>42))),"description"=>array(array("type"=>"length","constraints"=>array("max"=>42))),"cdate"=>array(array("type"=>"type","constraints"=>array("ref"=>"dateTime"))));
