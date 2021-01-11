<?php
namespace controllers;

use models\User;
use Ubiquity\orm\DAO;
use Ubiquity\utils\http\USession;
use Ubiquity\security\acl\controllers\AclControllerTrait;

/**
 * Controller ImageController
 * @allow('role'=>'@USER')
 * @route("image","inherited"=>true,"automated"=>true)
 */
class ImageController extends ControllerBase{
	use AclControllerTrait;
	
	public function index() {}
	public function initialize(){}
	public function finalize(){}
	
	/**
	 * @post('add')
	 */
	public function add(){
		$user=USession::get('activeUser')['id'];
		$uploads_dir = "upload/".$user."/";
		$path=$this->uploadImage($uploads_dir,$_FILES['upload'],true);
		if($path){
		    echo \json_encode(['url'=>$path]);
		}
	}

    /**
     * @post("avatar","name"=>"avatar")
     */
    public function avatar(){
        $user=USession::get('activeUser')['id'];
        $uploads_dir = "upload/".$user."/";
        $path=$this->uploadImage($uploads_dir,$_FILES['file'],'avatar');
        if($path){
            $user=DAO::getById(User::class,$user);
            $user->setAvatar($path);
            USession::set('activeUser',["id"=>$user->getId(),"email"=>$user->getEmail(),"firstname"=>$user->getFirstname(),"lastname"=>$user->getLastname(),'language'=>$user->getLanguage(),'avatar'=>$user->getAvatar()]);
            DAO::update($user);
            echo $path;
        }
        else{
            echo $path;
        }
    }
    
    private function uploadImage(string $imagePath,$file,$autoName){
        $maxsize=2097152;
        $availableType=["image/bmp","image/gif","image/jpeg","image/png"];
        if(isset($file)){
            if(\in_array($file['type'],$availableType)){
                if(($file['size'] < $maxsize) && ($file["size"] != 0)) {
                    $tmp_name = $file["tmp_name"];
                    $ext=\explode("/",$file["type"]);
                    $ext=\end($ext);
                    $files = \glob($imagePath. "*");
                    if ($files){
                        $filecount =1+\count($files);
                    }
                    if(!\is_dir($imagePath)){
                        \mkdir($imagePath);
                    }
                    if(!\is_string($autoName)){
                        $path=$imagePath.$filecount.".".$ext;
                    }
                    else{
                        $path=$imagePath.$autoName.".".$ext;
                    }
                    \move_uploaded_file($tmp_name,$path);
                    return $path;
                }
            }
        }
        return null;
    }
}
