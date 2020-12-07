<?php
namespace controllers;

use Ubiquity\utils\http\USession;

/**
 * Rest Controller ImageController
 * @route("/rest/","inherited"=>true,"automated"=>true)
 */
class ImageController extends \Ubiquity\controllers\rest\RestController {
	
	/**
	 * 
	 * @post('image/add')
	 */
	public function add(){
		$tmp_name = $_FILES["upload"]["tmp_name"];
		$name = basename($_FILES["upload"]["name"]);
		$user=USession::get('activeUser')['id'];
		$uploads_dir = "upload/$user";
		mkdir($uploads_dir);
		move_uploaded_file($tmp_name,$uploads_dir."/".$name);
		echo json_encode(['url'=>"$uploads_dir"."/".$name]);
	}
	
}
