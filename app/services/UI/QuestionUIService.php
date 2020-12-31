<?php

namespace services\UI;

use Ajax\php\ubiquity\JsUtils;
use Ajax\semantic\html\elements\HtmlLabel;
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

    public function questionFormTags(){
        $mytags = DAO::getAll( Tag::class, 'idUser='.USession::get('activeUser')['id'],false);
        $res = [];
            foreach ($mytags as $tag) {
                $label = $this->jquery->semantic()->htmlLabel('', '');
                $label->setClass('ui '.$tag->getColor().' empty circular label');
                $res[$tag->getId()]=$label.$tag->getName();
            }

        $taginput=$this->jquery->semantic()->htmlInput('addTag','text','','Enter Tag');
        $taginput->addIcon('tag');
        $taginput->setClass('ui right labeled left icon input')->setStyle('margin:5px 0 0 0;');
        $taginput->addLabel('',false,'plus')->setClass('ui green icon button');
        $this->jquery->execOn('click','#dropdown-questionForm-mytags-0','$("#dropdown-questionForm-mytags-0 .menu").addClass("transition visible");$("#dropdown-questionForm-mytags-0").addClass("active visible");');
        $this->jquery->prepend('#dropdown-questionForm-mytags-0 .menu',$taginput,true);
        $this->jquery->postOnClick('#label-div-addTag', Router::path('tag.submit'), '{tag:$("#addTag").val()}','',[
            'hasLoader'=>'internal',
            'jsCallback'=>'$("input[name=addTag]").val(null);var tag = $.parseJSON(data);var mySelect = $("#div-addTag");
            mySelect.after(
        $("<a></a>").addClass("item").attr("data-value",tag._rest.id).html("<div id=\'\' class=\'ui "+ tag._rest.color+ " empty circular label\'></div>"+ tag._rest.name)
    );'
        ]);
        return $res;
    }
	
	public function modal(){
	    $modal = $this->jquery->semantic()->htmlModal('modal');
	    $modal ->addContent('<div id="response-modal"></div>');
	}	
	
	public function questionForm($question,$types) {
	    $frm = $this->jquery->semantic ()->dataForm( 'questionForm',$question);
        $tags = $this->questionFormTags();
        $frm->addErrorMessage();
        $frm->addFields(['submit','mytags','caption','type','body']);
        $frm->setCaptions([
            '',
            '',
            'Assign tags',
            'Caption',
            'Type',
            'Add Body'
        ]);
        $frm->fieldAsCheckbox('body',[
            'class'=>'ui toggle checkbox'
        ]);
        $this->jquery->execOn('change','#questionForm-body-0','$("#questionBody").toggle()');
	    $frm->setValidationParams ( [
	        "on" => "blur",
            'inline'=>true
	    ] );
	    $frm->fieldAsInput('caption',['style'=>'width:69%;display:inline-table','rules'=>'empty']);
        $frm->fieldAsDropDown('type',['1'=>'<i class="check square icon"></i>QCM','2'=>'<i class="bars icon"></i>courte','3'=>'<i class="align left icon"></i>longue','4'=>'<i class="code icon"></i>Code'],false,['style'=>'width:30%;display:inline-table','rules'=>'empty']);
        $frm->fieldAsDropDown('mytags',$tags,true,[
            'style'=>'width:300px;margin-top:15px',
        ]);
        $frm->fieldAsSubmit('submit','green',Router::path('question.submit'),"#response",[
            'style'=>'display:block;margin-right:100%;width:150px;',
            'class'=>'ui green button',
            'value'=>'Create question',
            'ajax'=>['hasLoader'=>false,'params'=>'{"answers":$("#frmAnswer").serialize(),"ckcontent":window.editor.getData()}','historize'=>false]
        ]);
        $this->jquery->getOnClick ( '#dropdown-questionForm-type-0 .menu .item', 'question/getform', '#response-form', [
            "stopPropagation"=>false,
	        'attr' => 'data-value',
	        'hasLoader' => false,
	        'jsCallback' =>'$("#dropdown-typeq")'
	    ] );
	    return $frm;
	}

	public function getQuestionDataTable($questions,$typeq){
	    $dt = $this->jquery->semantic ()->dataTable ( 'dtItems', Question::class, $questions );
	    $msg = new HtmlMessage ( '', TranslatorManager::trans('noDisplay',[],'main') );
	    $msg->addIcon ( "x" );
	    $dt->setEmptyMessage ( $msg );
	    $dt->setFields ( [
	        'caption',
	        'tags',
	        'idTypeq',
	        'action'
	    ] );
	    $dt->insertDeleteButtonIn(3,true);
	    $dt->insertEditButtonIn(3,true);
	    $dt->insertDisplayButtonIn(3,true);
	    $dt->setClass(['ui very basic table']);
	    $dt->setCaptions([
	        TranslatorManager::trans('caption',[],'main'),
            'tags',
            'type'
	    ]);
	    $dt->setIdentifierFunction ( 'getId' );
	    $dt->setColWidths([0=>7,1=>4,2=>1,3=>2]);
	    $dt->setEdition ();
        $dt->setValueFunction('tags', function ($tags) {
            if ($tags != null) {
                $res = [];
                foreach ($tags as $tag) {
                    $label = new HtmlLabel($tag->getId(), $tag->getName());
                    $res[] = $label->setClass('ui ' . $tag->getColor() . ' label');
                }
                return $res;
            }
        });
        $dt->setValueFunction('idTypeq', function ($type) {
            $typeq = [1=>['name'=>'QCM','icon'=>'check square'],2=>['name'=>'courte','icon'=>'bars'],3=>['name'=>'longue','icon'=>'align left'],4=>['name'=>'code','icon'=>'code']];
            $label = new HtmlLabel('', $typeq[$type]['name'],$typeq[$type]['icon']);
            $label->setStyle('display:inline-flex;');
            return $label;
        });
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