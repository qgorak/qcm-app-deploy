<?php

namespace services\UI;

use Ajax\php\ubiquity\JsUtils;
use Ubiquity\controllers\Router;
use Ubiquity\orm\DAO;
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
    
    public function displayMyGroups($myGroups){
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
	    $groupForm->fieldAsSubmit('submit','green',Router::path('GroupAddSubmit'),"#response",[
	        'value'=>TranslatorManager::trans('addSubmit',[],'main'),
	        'ajax'=>['before'=>'$("#addModal").modal("hide");','hasLoader'=>false,'historize'=>false,'jsCallback'=>'$(".dimmer").remove();']
	    ]);
	    $groupForm->onSuccess("");
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
		$this->jquery->getOnClick('._delete',Router::path ('groupDelete',[""]),'#response',[
            'historize'=>false,
            'hasLoader' => 'internal',
            'attr' => 'data-ajax'
        ]);
        $this->jquery->ajaxOnClick('._demand',Router::path ('groupDemand',[""]),'#response-demand',['attr'=>'data-ajax','jsCallback'=>'$("#demandModal").modal("show");']);
		$this->jquery->postOnClick('.delete',Router::path('banUser'),'{"group":$("#dtUsers").attr("group"),"user":$(this).attr("data-ajax")}',"#response");
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
	    $this->jquery->postOnClick('.accept',Router::path('groupDemandAccept'),'{"valid":true,"group":$("#usersDemand").attr("group"),"user":$(this).attr("data-ajax")}',"#response-demand");
	    $this->jquery->postOnClick('.refuse',Router::path('groupDemandAccept'),'{"valide":false,"group":$("#usersDemand").attr("group"),"user":$(this).attr("data-ajax")}',"#response-demand");
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
	    $usersDt->setClass(['ui single line very basic table']);
	    $usersDt->setIdentifierFunction ( 'getId' );
	    $usersDt->setProperty('group', $id);
	    $usersDt->insertDefaultButtonIn('delete','ban','delete');
	    return $usersDt;
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

    public function groupAccordion($groups){
        $acc = $this->jquery->semantic()->htmlAccordion('mygroupsacc');
        $acc->setStyled();
        return $acc;
    }
    public function groupTitleGrid($group){
        $grid=$this->jquery->semantic()->htmlGrid('grid',1,3);
        $grid->getItem(0)->setWidth(3)->setContent($group->getName());
        $grid->getItem(1)->setWidth(5)->setContent($group->getDescription());
        $grid->getItem(2)->setWidth(2)->setContent($group->getKeyCode());
        $grid->addItem($this->groupOptionButton($group));
        $grid->addItem($this->groupDemandButton($group));
        $grid->setStyle('display:inline-block;width:100%');
        return $grid;
    }

    private function groupOptionButton($group){
        $dd=$this->jquery->semantic()->htmlDropdown('dd-'.$group->getId())->addIcon('ellipsis vertical');
        $dd->addItems(["see Exams","Delete"]);
        $dd->getItem(1)->setProperty('data-ajax',$group->getId())->addClass('_delete');
        $dd->setClass('dropdown ui button');
        $dd->setStyle("background:none;margin-top:4px;");
        $dd->setFloated();
        return $dd;
    }
    private function groupDemandButton($group){
        $bt=$this->jquery->semantic()->htmlButton('btDemand');
        $icons=$this->jquery->semantic()->htmlIconGroups("icons3",["user","add"],"large");
        $bt->addContent($icons->toCorner());
        $bt->addClass('_demand');
        $bt->setFloated();
        $label = $this->jquery->semantic()->htmlLabel('labelDemand',DAO::count(Usergroup::class,'idGroup=? AND status=0',[$group->getId()]));
        $label->setClass('ui tiny label floating red');
        $label->setStyle('padding-top:5px;');
        $bt->addContent($label);
        $bt->setProperty('data-ajax',$group->getId());
        return $bt;
    }
}