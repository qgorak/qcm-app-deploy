<?php

namespace services\UI;

use Ajax\php\ubiquity\JsUtils;
use Ubiquity\controllers\Router;
use Ubiquity\translation\TranslatorManager;
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
    
    public function displayMyGroups($myGroups,$inGroups,$waitInGroups){
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
	    $groupForm->fieldAsSubmit('submit','green',Router::path('GroupAddSubmit'),"window",[
	        'value'=>TranslatorManager::trans('addSubmit',[],'main'),
	        'ajax'=>['jsCallback'=>'$("#addForm-name-0").val("");$("#addForm-description-0").val("");if($("#myGroups tbody").children("tr:first").attr("id")=="htmltablecontent--0"){$("#myGroups tbody tr:first-child").remove();};$("#myGroups tbody").append("<tr id=\'myGroups-tr-"+JSON.parse(data)._rest.id+"\' class=\'_element\' data-ajax="+JSON.parse(data)._rest.id+">
	<td id=\'htmltr-myGroups-tr-"+JSON.parse(data)._rest.id+"-0\' data-field=\'id\'>"+JSON.parse(data)._rest.id+"</td>
	<td id=\'htmltr-myGroups-tr-"+JSON.parse(data)._rest.id+"-1\' data-field=\'name\'>"+JSON.parse(data)._rest.name+"</td>
	<td id=\'htmltr-myGroups-tr-"+JSON.parse(data)._rest.id+"-2\' data-field=\'description\'>"+JSON.parse(data)._rest.description+"</td>
	<td id=\'htmltr-myGroups-tr-"+JSON.parse(data)._rest.id+"-3\' data-field=\'keyCode\'>"+JSON.parse(data)._rest.keyCode+"</td>
	<td id=\'htmltr-myGroups-tr-"+JSON.parse(data)._rest.id+"-4\' >
		<button class=\'ui button icon _display basic\' data-ajax="+JSON.parse(data)._rest.id+"><i id=\'icon-\' class=\'icon eye\'></i></button>
		<button class=\'ui button icon _edit basic\' data-ajax="+JSON.parse(data)._rest.id+"><i id=\'icon-\' class=\'icon edit\'></i></button>
		<button class=\'ui button icon _delete red basic\' data-ajax="+JSON.parse(data)._rest.id+"><i id=\'icon-\' class=\'icon remove\'></i></button>
	</td>
</tr>");']
	    ]);
	    $groupForm->onSuccess("$('#addModal').modal('hide');");
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
		$this->jquery->getOnClick ( '._delete', Router::path ('groupDelete',[""]), '', [
			'hasLoader' => 'internal',
			'attr' => 'data-ajax',
		    'listenerOn'=>'body',
		    'jsCallback'=>'$(".ui.accordion").accordion("open",0);$(self).closest("tr").remove()'
		] );
		$this->jquery->getOnClick ( '._edit', Router::path ('groupDemand',[""]), '#response', [
			'hasLoader' => 'internal',
		    'listenerOn'=>'body',
			'attr' => 'data-ajax'
		] );
        $this->jquery->getOnClick('._display', Router::path ('groupView',[""]),'#response',[
            'hasLoader'=>'internal',
            'listenerOn'=>'body',
            'attr'=>'data-ajax'
        ]);
        
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
		
		$dtWait=$this->jquery->semantic()->dataTable('waitInGroups', Group::class, $waitInGroups);
		$dtWait->setFields ( [
		    'id',
		    'name',
		    'description',
		    'wait'
		] );
		$dtWait->setCaptions([
		    'id',
		    TranslatorManager::trans('name',[],'main'),
		    TranslatorManager::trans('description',[],'main')
		]);
		$dtWait->fieldAsElement('wait','i','hourglass icon');
		$dtWait->setIdentifierFunction ( 'getId' );
		
		
		
		$this->jquery->execAtLast("$('#addGroup').click(function() {
        	$('#addModal').modal('show');
        });
        $('#joinGroup').click(function() {
        	$('#joinModal').modal('show');
        });
            $('.ui.accordion').accordion();");
	}
	
	public function groupJoinDemand($users,$groupId){
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
	    $usersDt->insertDefaultButtonIn('accept','user plus','accept',false);
	    $usersDt->insertDefaultButtonIn('refuse','user times','refuse',false);
	    $usersDt->setProperty('group', $groupId);
	    $this->jquery->postOnClick('.accept',Router::path('groupDemandAccept'),'{"valid":true,"group":$("#usersDemand").attr("group"),"user":$(this).attr("data-ajax")}',"#response");
	    $this->jquery->postOnClick('.refuse',Router::path('groupDemandAccept'),'{"valide":false,"group":$("#usersDemand").attr("group"),"user":$(this).attr("data-ajax")}',"#response");
	}
	
	public function viewGroup($users,$id){
	    $usersDt=$this->jquery->semantic()->dataTable('dtUsers',User::class,$users);
	    $usersDt->setFields([
	        'firstname',
	        'lastname',
	        'email',
	        'delete'
	    ]);
	    $usersDt->setCaptions([
	        TranslatorManager::trans('firstname',[],'main'),
	        TranslatorManager::trans('lastname',[],'main'),
	        TranslatorManager::trans('email',[],'main')
	    ]);
	    $usersDt->setIdentifierFunction ( 'getId' );
	    $usersDt->setProperty('group', $id);
	    $usersDt->insertDefaultButtonIn('delete','user times','delete',false);
	    $this->jquery->postOnClick('.delete',Router::path('banUser'),'{"group":$("#dtUsers").attr("group"),"user":$(this).attr("data-ajax")}',"#response");
	}
	public function joinform(){
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
        $joinForm->fieldAsSubmit('submit','green',Router::path('joinSubmit'),'#response-joinform',[
            'value'=>TranslatorManager::trans('joinSubmit',[],'main')
        ]);

    }
}