<?php
return array("#tableName"=>"answer","#primaryKeys"=>array("id"=>"id"),"#manyToOne"=>array("question"),"#fieldNames"=>array("id"=>"id","caption"=>"caption","score"=>"score","question"=>"idQuestion"),"#memberNames"=>array("id"=>"id","caption"=>"caption","score"=>"score","idQuestion"=>"question"),"#fieldTypes"=>array("id"=>"int(11)","caption"=>"varchar(42)","score"=>"float","question"=>false),"#nullable"=>array("caption","score"),"#notSerializable"=>array("question"),"#transformers"=>array(),"#accessors"=>array("id"=>"setId","caption"=>"setCaption","score"=>"setScore","idQuestion"=>"setQuestion"),"#joinColumn"=>array("question"=>array("className"=>"models\\Question","name"=>"idQuestion","nullable"=>false)),"#invertedJoinColumn"=>array("idQuestion"=>array("member"=>"question","className"=>"models\\Question")));
