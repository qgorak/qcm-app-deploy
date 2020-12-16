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
			"options"=>array(),
			"cache"=>false,
			"wrapper"=>"Ubiquity\\db\\providers\\pdo\\PDOWrapper"
			),
	"sessionName"=>"s5fc15c2d88353",
	"namespaces"=>array(),
	"templateEngine"=>"Ubiquity\\views\\engine\\Twig",
	"templateEngineOptions"=>array(
			"cache"=>false
			),
	"test"=>false,
	"debug"=>true,
	"logger"=>function (){return new \Ubiquity\log\libraries\UMonolog(array (
  'host' => '127.0.0.1',
  'port' => 8090,
  'sessionName' => 's5fc15c2d88353',
)['sessionName'],\Monolog\Logger::INFO);},
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
			),
	"isRest"=>function (){
			return \Ubiquity\utils\http\URequest::getUrlParts()[0]==="rest";
		}
	);
