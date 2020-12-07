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
		$availableType=["image/bmp","image/gif","image/jpeg","image/png"];
		if(isset($_FILES['upload'])){
			if(\in_array($_FILES['upload']['type'],$availableType)){
				$tmp_name = $_FILES["upload"]["tmp_name"];
				$ext=\end(explode("/",$_FILES["upload"]["type"]));
				$user=USession::get('activeUser')['id'];
				$uploads_dir = "upload/".$user."/";
				$files = \glob($uploads_dir. "*");
				if ($files){
					$filecount =1+\count($files);
				}
				if(!\is_dir($uploads_dir)){
					\mkdir($uploads_dir);
				}
				$path=$uploads_dir.$filecount.".".$ext;
				\move_uploaded_file($tmp_name,$path);
				echo \json_encode(['url'=>$path]);
			}
		}
	}
}
