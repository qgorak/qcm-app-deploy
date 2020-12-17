<?php
return array(
	"siteUrl"=>"https://qcm-app-ubiquity.herokuapp.com/",
	"database"=>array(
			"type"=>"mysql",
			"dbName"=>"qcm-app",
			"serverName"=>"163.172.212.32",
			"port"=>3306,
			"user"=>"qcm-app",
			"password"=>"lIwM8pFuUd7mA6Rv",
			"options"=>array(PDO::ATTR_PERSISTENT=>true,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC),
			"cache"=>false,
			"wrapper"=>"Ubiquity\\db\\providers\\pdo\\PDOWrapper"
			),
	"sessionName"=>"s5fc15c2d88353",
	"namespaces"=>array(),
	"templateEngine"=>"Ubiquity\\views\\engine\\Twig",
	"templateEngineOptions"=>array(
			"cache"=>true
			),
	"test"=>false,
	"debug"=>false,
	"di"=>array(
			"@exec"=>array("jquery"=>function ($controller){
						return \Ubiquity\core\Framework::diSemantic($controller);
					})
			),
	"cache"=>array(
			"directory"=>"cache/",
			"system"=>"Ubiquity\\cache\\system\\ArrayCache",
			"params"=>array()
			),
	"mvcNS"=>array(
			"models"=>"models",
			"controllers"=>"controllers",
			"rest"=>""
			)
	);