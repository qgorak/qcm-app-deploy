<?php
return array(
	"siteUrl"=>"https://lesbleus.sts-sio-caen.info/",
	"database"=>[
			"type"=>"mysql",
			"dbName"=>"qcm",
			"serverName"=>"127.0.0.1",
			"port"=>3306,
			"user"=>"qcm",
			"password"=>"qcm",
			"options"=>[
					true,
					2
					],
			"cache"=>false,
			"wrapper"=>"Ubiquity\\db\\providers\\pdo\\PDOWrapper"
			],
	"sessionName"=>"s5fc15c2d88353",
	"namespaces"=>[],
	"templateEngine"=>"Ubiquity\\views\\engine\\Twig",
	"templateEngineOptions"=>[
			"cache"=>false
			],
	"test"=>false,
	"debug"=>true,
	"di"=>[
			"@exec"=>[
					"jquery"=>function ($controller){
						return \Ubiquity\core\Framework::diSemantic($controller);
					}
					]
			],
	"cache"=>[
			"directory"=>"cache/",
			"system"=>"Ubiquity\\cache\\system\\ArrayCache",
			"params"=>[]
			],
	"mvcNS"=>[
			"models"=>"models",
			"controllers"=>"controllers",
			"rest"=>""
			],
	"encryption-key"=>"78d9337edb02184dd4e0361fd517e32a"
	);