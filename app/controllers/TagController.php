<?php
namespace controllers;

use Ubiquity\orm\DAO;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\http\UResponse;
use Ubiquity\utils\http\USession;
use models\Tag;
use models\User;
use Ubiquity\security\acl\controllers\AclControllerTrait;
 /**
 * Controller TagController
 * @allow('role'=>'@USER')
 * @route('tag','inherited'=>true, 'automated'=>true)
 * @property \Ajax\php\ubiquity\JsUtils $jquery
 */
class TagController extends ControllerBase{
    use AclControllerTrait;
    
    private $uiService;
    
    public function initialize() {
        parent::initialize ();
    }
    
	public function index(){
		$this->loadView("TagController/index.html");
	}
	
	/**
	 * @get("my","name"=>'tag.my')
	 */
	public function my(){
	    $tags = DAO::getAll( Tag::class, 'idUser=?',false,[USession::get('activeUser')['id']]);
	    $this->jquery->renderView ( 'TagController/my.html', [
	        'tags'=>$tags
	    ]);
	}
	
	/**
	 * @post("submit","name"=>'tag.submit')
	 */
	public function submit(){
	    if(DAO::getOne(Tag::class,"idUser=? AND name=?",false,[USession::get('activeUser')['id'],URequest::getPost()['tag']])==null && (URequest::getPost()['tag']!='')){
	        $tag = new Tag();
	        $creator= new User();
	        $creator->setId(USession::get('activeUser')['id']);
	        $tag->setName(URequest::getPost()['tag']);
	        $tag->setUser($creator);
	        $colors = ['red','orange','yellow','olive','green','teal','blue','violet','purple','pink','brown','grey','black'];
	        $color = $colors[array_rand($colors)];
	        $tag->setColor($color);
	        DAO::insert($tag);
            echo json_encode($tag);
	    }

	}
}
