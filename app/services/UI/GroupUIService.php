<?php

namespace services\UI;

use Ajax\php\ubiquity\JsUtils;
use Ubiquity\controllers\Router;
use Ubiquity\translation\TranslatorManager;
use Ubiquity\utils\http\URequest;
use models\Group;
use models\User;
use models\Usergroup;

class GroupUIService {
    protected $jquery;
    protected $semantic;
    public function __construct(JsUtils $jq) {
        $this->jquery = $jq;
        $this->semantic = $jq->semantic ();
    }
    
    public function displayMyGroups($myGroups,$inGroups){
	    $groupForm=$this->jquery->semantic()->dataForm('addForm', Group::class);
	    $groupForm->setFields([
	        "name",
	        "description",
	        "submit"
	    ]);
	    $groupForm->setCaptions([
	        TranslatorManager::trans('name',[],'main'),
	        TranslatorManager::trans('description',[],'main')
	    ]);
	    $groupForm->fieldAsInput('name',[
	        'rules'=>'empty'
	    ]);
	    $groupForm->fieldAsTextarea('description',[
	        'rules'=>'empty'
	    ]);
	    $groupForm->fieldAsSubmit('submit','green',Router::path('GroupAddSubmit'),'#response',[
	        'value'=>TranslatorManager::trans('addSubmit',[],'main')
	    ]);
	    $groupForm->onSuccess("$('#addModal').modal('hide');");
	    $joinForm=$this->jquery->semantic()->dataForm('joinForm',Usergroup::class);
	    $joinForm->setFields([
	        'GroupKey',
	        'submit'
	    ]);
	    $joinForm->setCaptions([
	        TranslatorManager::trans('groupKey',[],'main')
	    ]);
	    $joinForm->fieldAsInput('GroupKey',[
	        'rules'=>'empty'
	    ]);
	    $joinForm->fieldAsSubmit('submit','green',Router::path('joinSubmit'),'#response',[
	        'value'=>TranslatorManager::trans('joinSubmit',[],'main')
	    ]);
	    $joinForm->onSuccess("$('#joinModal').modal('hide');");
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
			'attr' => 'data-ajax',
		    'jsCallback'=>'$(".ui.accordion").accordion("open",0);'
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
		$this->jquery->execAtLast("$('#addGroup').click(function() {
        	$('#addModal').modal('show');
        });
        $('#joinGroup').click(function() {
        	$('#joinModal').modal('show');
        });
            $('.ui.accordion').accordion();");
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
}