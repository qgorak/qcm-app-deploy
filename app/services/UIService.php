<?php

namespace services;

use Ajax\php\ubiquity\JsUtils;
use Ajax\semantic\html\collections\HtmlMessage;
use Ajax\service\JArray;
use Ubiquity\controllers\Router;
use Ubiquity\orm\DAO;
use Ubiquity\translation\TranslatorManager;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\http\USession;
use models\Group;
use models\Qcm;
use models\Question;
use models\Tag;
use models\Typeq;
use models\User;
use Ajax\semantic\html\elements\HtmlLabel;

class UIService {
	protected $jquery;
	protected $semantic;
	public function __construct(JsUtils $jq) {
		$this->jquery = $jq;
		$this->semantic = $jq->semantic ();
	}
	
	public function questionDataTable($name,$questions,$checked) {
		$q = new Question ();
		$dt = $this->jquery->semantic ()->dataTable($name, $q,$questions);
		$dt->setFields ( [
		    'caption',
		    'point'
		] );
		$dt->setcaptions([
		    TranslatorManager::trans('caption',[],'main'),
		    TranslatorManager::trans('point',[],'main')
		]);
		$dt->setVariation('compact');
		$dt->setIdentifierFunction ( 'getId');
		$dt->setClass(['ui very basic table']);
		$msg = $this->jquery->semantic()->htmlMessage('');
		if($checked == true){
		    $dt->setCaption(0,'');
		    $msg->setContent(TranslatorManager::trans('selectInBank',[],'main'));
		    $msg->setIcon('arrow down');
		    $dt->insertDefaultButtonIn(4, 'x','_remove circular red',false,null,'remove');
		    $dt->setEmptyMessage($msg);
		    
		}else{
		    $dt->setCaption(0,'');
		    $msg->setContent(TranslatorManager::trans('empty',[],'main'));
		    $msg->setVariation('negative');
		    $msg->setIcon('exclamation triangle');
		    $dt->insertDefaultButtonIn(4, 'plus','_add circular green ',false,null,'add');
		    $dt->setEmptyMessage($msg);
		    $dt->setStyle('margin-top:0;padding-inline: 10px 20px;');
		    $toolbar = $this->jquery->semantic()->htmlMenu('Question Bank');
		    $toolbar->addPopupAsItem('Sort', 'sort','<div id="response-tag"></div>');
		    $toolbar->addHeader(TranslatorManager::trans('questionBank',[],'main'));
		    $toolbar->setClass('ui top attached menu');
		    $dt->setToolbar($toolbar);
		}
		$dt->setColWidths([0=>8,1=>1,2=>1]);
		$dt->setIdentifierFunction ( 'getId' );
		$this->jquery->ajax('get',Router::path('tag.my'),'#response-tag',[
				'hasLoader'=>'internal',
				'historize'=>false,
				'jsCallback'=>'$(".ui.menu .item:first-child").popup({
   								 popup : $(".ui.popup"),
    						     on : "click"
							});;'
		]);
		return $dt;
	}
	
	public function questionForm() {
	    $q = new Question ();
	    $frm = $this->jquery->semantic ()->dataForm ( 'questionForm', $q );
	    $frm->setFields ( [
	        'caption',
	        'addbody',
	        'ckcontent',
	        'typeq',
	        'submit'
	    ] );
	    $frm->fieldAsButton('addbody','ui green button',[
	        'content'=>'Add body',
	        'click'=>'$("#field-questionForm-ckcontent").show();$(this).hide();'
	    ]);
	    $frm->fieldAsInput ( 'caption', [
	        'rules' => [
	            'empty',
	            'length[5]'
	        ]
	    ] );
	    $types = DAO::getAll ( Typeq::class );
	    $ddtypes= array();
	    foreach ($types as &$value) {
	        array_push($ddtypes,$value->getCaption());
	    }
	    $frm->fieldAsDropDown ( 'typeq', JArray::modelArray ( $types, 'getId','getCaption' ),false,[
	        'rules' => [
	            'empty',
	        ]
	    ]);
	    $q->setTypeq ( current ( $types ) );
	    $frm->setValidationParams ( [
	        "on" => "blur",
	        "inline" => true
	    ] );
	    return $frm;
	}
	
	public function qcmForm() {
	    $q = new Qcm();
	    $frm = $this->jquery->semantic ()->dataForm ( 'qcmForm', $q );
	    $frm->setFields ( [
	        'name',
	        'description'
	    ] );
	    $frm->setCaptions([
	        TranslatorManager::trans('name',[],'main'),
	        TranslatorManager::trans('description',[],'main')
	    ]);
	    return $frm;
	}
	
	public function getQuestionDataTable($questions){
	    $dt = $this->jquery->semantic ()->dataTable ( 'dtItems', Question::class, $questions );
	    $msg = new HtmlMessage ( '', TranslatorManager::trans('noDisplay',[],'main') );
	    $msg->addIcon ( "x" );
	    $dt->setEmptyMessage ( $msg );
	    $dt->setFields ( [
	        'caption',
	    	'tags',
	    	'typeq',
	    	'action'
	    ] );
		$dt->insertDeleteButtonIn(3,true);
		$dt->insertEditButtonIn(3,true);
		$dt->insertDisplayButtonIn(3,true);
	    $dt->setClass(['ui very basic table']);
	    $dt->setCaptions([
	        TranslatorManager::trans('caption',[],'main')
	    ]);
		$dt->setValueFunction('tags',function($tags){
		    if($tags!=null){
			$res=[];
			foreach ($tags as $tag){
				$label=new HtmlLabel($tag->getId(),$tag->getName());
				$res[]=$label->setClass('ui '.$tag->getColor().' label');
			}
			return $res;
		    }
			});
	    $dt->setIdentifierFunction ( 'getId' );
	    $dt->setColWidths([0=>9,1=>2,2=>1,3=>2]);
	    $dt->setEdition ();
	    $this->jquery->getOnClick ( '._delete', Router::path ('question.delete',[""]), 'body', [
	    		'hasLoader' => 'internal',
	    		'attr' => 'data-ajax'
	    ] );
	}
	
	public function displayMyGroups($myGroups,$inGroups){
		$dtMyGroups = $this->jquery->semantic ()->dataTable ( 'myGroups', Group::class, $myGroups );
		$dtMyGroups->setFields ( [
			'id',
			'name',
			'description',
			'keyCode'
		] );
		$dtMyGroups->setCaptions([
			'id',
			TranslatorManager::trans('name',[],'main'),
			TranslatorManager::trans('description',[],'main'),
			TranslatorManager::trans('groupKey',[],'main')
		]);
		$dtMyGroups->setIdentifierFunction ( 'getId' );
		$dtMyGroups->addAllButtons(false);
		$this->jquery->getOnClick('._display', Router::path ('groupView',[""]),'#response',[
			'hasLoader'=>'internal',
			'attr'=>'data-ajax'
		]);
		$this->jquery->getOnClick ( '._delete', Router::path ('groupDelete',[""]), '#response', [
			'hasLoader' => 'internal',
			'attr' => 'data-ajax'
		] );
		$this->jquery->getOnClick ( '._edit', Router::path ('groupDemand',[""]), '#response', [
			'hasLoader' => 'internal',
			'attr' => 'data-ajax'
		] );
		$dtInGroups = $this->jquery->semantic ()->dataTable ( 'inGroups', Group::class, $inGroups );
		$dtInGroups->setFields ( [
			'id',
			'name',
			'description'
		] );
		$dtInGroups->setCaptions([
			'id',
			TranslatorManager::trans('name',[],'main'),
			TranslatorManager::trans('description',[],'main')
		]);
		$dtInGroups->setIdentifierFunction ( 'getId' );
	}
	
	public function groupJoinDemand($users){
		$usersDt=$this->jquery->semantic()->dataTable('usersDemand',User::class,$users);
		$usersDt->setFields([
			'firstname',
			'lastname',
			'email',
			'accept',
			'refuse'
		]);
		$usersDt->setCaptions([
			TranslatorManager::trans('firstname',[],'main'),
			TranslatorManager::trans('lastname',[],'main'),
			TranslatorManager::trans('email',[],'main')
		]);
		$usersDt->setIdentifierFunction ( 'getId' );
		$usersDt->insertDefaultButtonIn('accept','check','accept',false);
		$usersDt->insertDefaultButtonIn('refuse','remove','refuse',false);
		$this->jquery->ajaxOnClick('.accept',Router::path('groupDemandAccept',['true',URequest::getUrlParts()[2]]),'#response',[
			'attr'=>'data-ajax'
		]);
		$this->jquery->ajaxOnClick('.refuse',Router::path('groupDemandAccept',['false',URequest::getUrlParts()[2]]),'#response',[
			'attr'=>'data-ajax'
		]);
	}
	
	public function questionBankToolbar(){
		$mytags = DAO::getAll( Tag::class, 'idUser='.USession::get('activeUser')['id'],false);
		$dd = $this->jquery->semantic()->htmlDropdown('Filter','',JArray::modelArray ( $mytags, 'getId','getName' ));
		$dd->asSearch('tags',true);
		$toolbar = $this->jquery->semantic()->htmlMenu('QuestionBank');
		$toolbar->addDropdownAsItem($dd);
		$toolbar->addHeader(TranslatorManager::trans('questionBank',[],'main'));
		$toolbar->setClass('ui top attached menu');	
	}

}