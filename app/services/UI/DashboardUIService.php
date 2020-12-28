<?php

namespace services\UI;

use Ajax\php\ubiquity\JsUtils;

class DashboardUIService {
    
	protected $jquery;
	protected $semantic;
	
	public function __construct(JsUtils $jq) {
		$this->jquery = $jq;
		$this->semantic = $jq->semantic ();
	}
}