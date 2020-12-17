<?php

namespace services\UI;

use Ajax\php\ubiquity\JsUtils;
use Ajax\service\JArray;
use Ubiquity\controllers\Router;
use Ubiquity\orm\DAO;
use Ubiquity\translation\TranslatorManager;
use Ubiquity\utils\http\USession;
use models\Question;
use models\Tag;
use Ajax\semantic\html\collections\HtmlMessage;

class QuestionUIService {
    
	protected $jquery;
	protected $semantic;
	
	public function __construct(JsUtils $jq) {
		$this->jquery = $jq;
		$this->semantic = $jq->semantic ();
	}
	
	public function questionBankToolbar(){
	    $mytags = DAO::getAll( Tag::class, 'idUser='.USession::get('activeUser')['id'],false);
	    $dd = $this->jquery->semantic()->htmlDropdown('Filter','',JArray::modelArray ( $mytags, 'getId','getName' ));
	    $dd->asSearch('tags',true);
	    $toolbar = $this->jquery->semantic()->htmlMenu('QuestionBank');
	    $toolbar->addDropdownAsItem($dd);
	    $toolbar->addHeader(TranslatorManager::trans('questionBank',[],'main'));
	    $toolbar->setClass('ui top attached menu');
	    return $toolbar;
	}
	
	public function modal(){
	    $modal = $this->jquery->semantic()->htmlModal('modal');
	    $modal ->addContent('<div id="response-modal"></div>');
	}	
	
	public function questionForm($question='',array $types) {
	    if($question==''){
	        $q = new Question ();
	    }else{
	        $q =$question;
	    }
	    $frm = $this->jquery->semantic ()->dataForm ( 'questionForm', $q );
	    $frm->setFields ( [
	        'submit',
	        'caption',
	        'addbody',
	        'ckcontent',
	        'typeq',
	    ] );
	    $frm->setCaptions([
	        'submit',
	        'caption',
	        'addbody',
	        '',
	        'Type'
	    ]);
	    $frm->fieldAsButton('addbody','ui green button',[
	        'content'=>'Add body',
	        'click'=>'$("#field-questionForm-ckcontent").show();$(this).hide();'
	    ]);
	    $frm->fieldAsInput ( 'caption', [
	        'rules' => [
	            'empty',
	            'length[5]'
	        ]
	    ] )->setValue(1000);
	    $frm->fieldAsDropDown ( 'typeq', $types,false,[
	        'rules' => [
	            'empty',
	        ]
	        
	    ]);
	    $q->typeq=$q->getIdTypeq();
	    
	    $frm->setValidationParams ( [
	        "on" => "blur",
	        "inline" => true
	    ] );
	    
	    $this->jquery->getOnClick ( '#dropdown-questionForm-typeq-0 .menu .item', 'question/getform', '#response-form', [
	        'stopPropagation'=>false,
	        'attr' => 'data-value',
	        'hasLoader' => false,
	        'jsCallback' =>'$("#input-dropdown-questionForm-typeq-0").attr("name","typeq");
                                $("#input-dropdown-questionForm-typeq-0").val($(self).attr("data-value"))'
	    ] );
	    return $frm;
	}
	
	public function tagManagerJquery(){
	    $this->jquery->ajax('get',Router::path('tag.my'),'#tagManager',[
	        'hasLoader'=>'internal',
	        'historize'=>false,
	        'jsCallback'=>'$("#tagMenu").popup({
   								 popup : $("#tagPopup"),
    						     on : "click"
							});;'
	    ]);
	    $this->jquery->postFormOnClick('#addTag', Router::path('tag.submit'), 'tagForm','#tagManager',[
	        'hasLoader'=>'internal',
	        'jsCallback'=>"$('#nametag').val('');"
	    ]);
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
	    
	    $dt->setIdentifierFunction ( 'getId' );
	    $dt->setColWidths([0=>9,1=>2,2=>1,3=>2]);
	    $dt->setEdition ();
	    $this->jquery->getOnClick ( '._delete', Router::path ('question.delete',[""]), '', [
	        'hasLoader' => 'internal',
	        'jsCallback'=>'$(self).closest("tr").remove();',
	        'attr' => 'data-ajax'
	    ] );
	    $this->jquery->ajaxOnClick ( '._display', Router::path('question.preview',['']) , '#response-modal', [
	        'hasLoader' => 'internal',
	        'method' => 'get',
	        'attr' => 'data-ajax',
	        'jsCallback'=>'$("#modal").modal("show");'
	    ] );
	    $this->jquery->getOnClick ( '._edit', Router::path ('question.patch',[""]), '#response', [
	        'hasLoader' => 'internal',
	        'attr' => 'data-ajax'
	    ] );
	}
	
}