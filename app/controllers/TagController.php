<?php
namespace controllers;

use Ubiquity\orm\DAO;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\http\USession;
use models\Tag;
use models\User;
use services\UIService;
use Ubiquity\security\acl\controllers\AclControllerTrait;
 /**
 * Controller TagController
 * @route('tag','inherited'=>true, 'automated'=>true)
 * @property \Ajax\php\ubiquity\JsUtils $jquery
 */
class TagController extends ControllerBase{
    use AclControllerTrait;
    
    private $uiService;
    
    public function initialize() {
        parent::initialize ();
        $this->uiService = new UIService ( $this->jquery );
    }
    
	public function index(){
		$this->loadView("TagController/index.html");
	}
	
	/**
	 * @allow('role'=>'@USER')
	 * @get("my","name"=>'tag.my')
	 */
	public function my(){
	    $userid = USession::get('activeUser')['id'];
	    $tags = DAO::getAll( Tag::class, 'idUser='.$userid,false);
	    $this->jquery->renderView ( 'TagController/my.html', [
	        'tags'=>$tags
	    ]);
	}
	
	/**
	 * @allow('role'=>'@USER')
	 * @post("submit","name"=>'tag.submit')
	 */
	public function submit(){
	    if(DAO::getOne(Tag::class,"idUser=? AND name=?",false,[USession::get('activeUser')['id'],URequest::post('nametag')])==null){
	        $tag = new Tag();
	        $creator= new User();
	        $creator->setId(USession::get('activeUser')['id']);
	        $tag->setName(URequest::getPost()['nametag']);
	        $tag->setUser($creator);
	        $colors = ['red','orange','yellow','olive','green','teal','blue','violet','purple','pink','brown','grey','black'];
	        $color = $colors[array_rand($colors)];
	        $tag->setColor($color);
	        DAO::insert($tag);
	    }
        $this->my();
	}
	
	public function _getRole(){
	    if(isset(USession::get('activeUser')['id'])){
	        return '@USER';
	    }
	    return '@GUEST';
	}
}
