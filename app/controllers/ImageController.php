<?php
namespace controllers;

use Ubiquity\utils\http\USession;
use Ubiquity\security\acl\controllers\AclControllerTrait;

/**
 * Controller ImageController
 * @route("image","inherited"=>true,"automated"=>true)
 */
class ImageController extends ControllerBase{
	use AclControllerTrait;
	
	public function index() {}
	public function initialize(){}
	public function finalize(){}
	
	/**
	 * @allow('role'=>'@USER')
	 * @post('add')
	 */
	public function add(){
		$maxsize=2097152;
		$availableType=["image/bmp","image/gif","image/jpeg","image/png"];
		if(isset($_FILES['upload'])){
			if(\in_array($_FILES['upload']['type'],$availableType)){
				if(($_FILES['upload']['size'] < $maxsize) && ($_FILES["upload"]["size"] != 0)) {
					$tmp_name = $_FILES["upload"]["tmp_name"];
					$ext=explode("/",$_FILES["upload"]["type"]);
					$ext=\end($ext);
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
}
