<?php

namespace services\UI;

use Ajax\bootstrap\html\HtmlDropdown;
use Ajax\php\ubiquity\JsUtils;
use Ajax\semantic\html\collections\form\HtmlFormDropdown;
use Ajax\semantic\html\collections\form\HtmlFormInput;
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
        $dd= $this->jquery->semantic()->htmlDropdown('tags',"",array())->asSelect('tags',true);
        $dd->addHeaderItem('Your tags','tags');
            foreach ($mytags as $tag) {
                $label = $this->jquery->semantic()->htmlLabel('', '');
                $label->setClass('ui '.$tag->getColor().' empty circular label');
                $dd->addItem($label . $tag->getName(), $tag->getId());
            }
        $dd->addItem('hiddentag')->setStyle('display:none;');
        $dd->setDefaultText('Assign Tag');
        $taginput=$this->jquery->semantic()->htmlInput('addTag','text','','Enter Tag');
        $taginput->addIcon('tag');
        $taginput->setClass('ui right labeled left icon input');
        $taginput->addLabel('',false,'plus')->setClass('ui green icon button');
        $this->jquery->postOnClick('#label-div-addTag', Router::path('tag.submit'), '{tag:$("#addTag").val()}','',[
            'hasLoader'=>'internal',
            'jsCallback'=>'$("input[name=addTag]").val(null);var tag = $.parseJSON(data);var mySelect = $("#tags .item:last");
            mySelect.after(
        $("<a></a>").addClass("item").attr("data-value",tag._rest.id).html("<div id=\'\' class=\'ui "+ tag._rest.color+ " empty circular label\'></div>"+ tag._rest.name)
    );'
        ]);
        $dd->addHeaderItem()->setContent($taginput)->setClass('');
        return $dd;
    }
	
	public function modal(){
	    $modal = $this->jquery->semantic()->htmlModal('modal');
	    $modal ->addContent('<div id="response-modal"></div>');
	}	
	
	public function questionForm($question,$types) {
	    $frm = $this->jquery->semantic ()->htmlForm( 'questionForm');
	    $frm->addErrorMessage();
        $frm->addDivider();
        $dd = $this->questionFormTags()->setStyle('width:300px;');
        $field=$frm->addField('tags');
        $field->setContent($dd);
        $field->addContent($this->jquery->semantic()->htmlButton('submit','submit','green right floated '));
        $frm->addDivider();
        $ddType= $this->jquery->semantic()->htmlDropdown('typeq',"",array())->asSelect('typeq');
        for ($i=0;$i<count($types);$i++){
            $ddType->addItem($types[$i][1],$types[$i][0])->addIcon($types[$i][2]);
        }
        $ddType->setDefaultText('Select type');
        $fields=$frm->addFields();
        $fields->addInput('caption',null,'text','New question')->addRule("empty")->setWidth(12);
        $fields->addItem($ddType);
        $frm->addCheckbox('addbody','Add Body','','toggle');
        $this->jquery->execOn('change','#addbody','$("#questionBody").toggle()');
	    $frm->setValidationParams ( [
	        "on" => "blur",
	        "inline" => true
	    ] );
	    $this->jquery->getOnClick ( '#typeq .menu .item', 'question/getform', '#response-form', [
	        'stopPropagation'=>false,
	        'attr' => 'data-value',
	        'hasLoader' => false,
	        'jsCallback' =>'$("#dropdown-typeq").attr("name","typeq");
                                $("#dropdown-typeq").val($(self).attr("data-value"))'
	    ] );
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
	    $dt->setIdentifierFunction ( 'getId' );
	    $dt->setColWidths([0=>9,1=>2,2=>1,3=>2]);
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