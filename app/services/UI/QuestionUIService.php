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
	
	public function questionTagsFilterDd(){
	    $mytags = DAO::getAll( Tag::class, 'idUser='.USession::get('activeUser')['id'],false);
        $res = [];
        foreach ($mytags as $tag) {
            $label = $this->jquery->semantic()->htmlLabel('', $tag->getName());
            $label->setClass('ui '.$tag->getColor().' label');
            $res[$tag->getId()]=$label;
        }
	    $dd = $this->jquery->semantic()->htmlDropdown('filterTags','',$res);
	    $dd->asSelect('tags',true);
	    $dd->setClass('ui multiple search dropdown item');
	    $dd->setStyle('min-width:180px;padding-right:0');
	    $dd->setDefaultText('<div style="margin-top:-3px;"class="ui basic button"><i class="tag icon"></i>Filter by tags</div>');
	    $this->jquery->jsonArrayOn('change','#filterTags','#dtItems-tr-__id__','question/getByTags/','post',[
	        'params'=>'{tags:$("#input-filterTags").val(),types:$("#input-filterType").val()}',
            'jsCallback'=>'onLoad();$(".ui.dropdown.selection").dropdown({"action": "activate","on": "hover","showOnFocus": true});'
        ]);
        $this->jquery->jsonArrayOn('change','#filterType','#dtItems-tr-__id__','question/getByTags/','post',[
            'params'=>'{tags:$("#input-filterTags").val(),types:$("#input-filterType").val()}',
            'jsCallback'=>'onLoad();$(".ui.dropdown.selection").dropdown({"action": "activate","on": "hover","showOnFocus": true});'
        ]);
	    return $dd;
	}

    public function questionTypeFilterDd(){
        $types = ['1'=>'<i class="check square icon"></i>QCM','2'=>'<i class="bars icon"></i>courte','3'=>'<i class="align left icon"></i>longue','4'=>'<i class="code icon"></i>Code'];
        $dd = $this->jquery->semantic()->htmlDropdown('filterType','',$types);
        $dd->asSelect('type',true);
        $dd->setClass('ui multiple dropdown item');
        $dd->setStyle('min-width:180px;padding-right:0');
        $dd->setDefaultText('<div style="margin-top:-3px;"class="ui basic button"><i class="filter icon"></i>Filter by type</div>');
        return $dd;
    }

    public function questionFormTags(){
        $mytags = DAO::getAll( Tag::class, 'idUser='.USession::get('activeUser')['id'],false);
        $res = [];
            foreach ($mytags as $tag) {
                $label = $this->jquery->semantic()->htmlLabel('', $tag->getName());
                $label->setClass('ui '.$tag->getColor().' label');
                $res[$tag->getId()]=$label;
            }
        $taginput=$this->jquery->semantic()->htmlInput('addTag','text','','Enter Tag');
        $taginput->addIcon('tag');
        $taginput->setClass('ui right labeled left icon input');
        $taginput->addLabel('Add Tag',false,'')->setTagName('a')->setClass('ui small tag label');
        $this->jquery->execOn('click','#dropdown-questionForm-mytags-0','$("#dropdown-questionForm-mytags-0 .menu").addClass("transition visible");$("#dropdown-questionForm-mytags-0").addClass("active visible");');
        $this->jquery->exec('$( "#dropdown-questionForm-mytags-0 .menu:last" ).wrapInner( "<div id=\'tagMenuScrolling\' class=\'scrolling menu\' />");',true);
        $this->jquery->prepend('#dropdown-questionForm-mytags-0 .menu:first','<div style="margin-top:0;" class="divider"></div><div class="header"><i class="tag icon"></i>Create tag</div>'.$taginput.'<div class="header"><i class="tags icon"></i>Your tags</div><div style="margin-bottom:0;" class="divider"></div>',true);
        $this->jquery->append('#dropdown-questionForm-mytags-0 .menu:first','<div id="notagstxt">no tags</div>',true);
        $this->jquery->postOnClick('#label-div-addTag', Router::path('tag.submit'), '{tag:$("#addTag").val()}','',[
            'jsCallback'=>'$("input[name=addTag]").val(null);var tag = $.parseJSON(data);var mySelect = $("#tagMenuScrolling");
            mySelect.append(
        $("<a></a>").addClass("item").attr("data-value",tag._rest.id).html("<div id=\'\' class=\'ui "+ tag._rest.color+ " label\'>" + tag._rest.name +" </div>")
    );$("body").toast({position: "center top", message: "Tag created",class: "success", });'
        ]);
        $this->jquery->execOn('change','#input-dropdown-questionForm-mytags-0','if(!$(this).val())
    $("#field-questionForm-mytags-0 label").css("display", "none");
else
    $("#field-questionForm-mytags-0 label").css("display", "block");
');
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
            'Tags assigned:',
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
            'style'=>'width:132px;margin-top:0!important',
        ]);
        $frm->fieldAsSubmit('submit','green',Router::path('question.submit'),"#response",[
            'style'=>'display:block;margin-right:100%;width:150px;',
            'class'=>'ui green button',
            'value'=>'Create question',
            'ajax'=>['hasLoader'=>true,'params'=>'{"answers":$("#frmAnswer").serialize(),"ckcontent":window.editor.getData()}','historize'=>false,'jsCallback'=>'$("body").toast({position: "center top", message: "Sucess",class: "success", });']
        ]);
        $this->jquery->addClass('#text-dropdown-questionForm-mytags-0','ui button tagsbutton',true);
        $this->jquery->addClass('#text-dropdown-questionForm-type-0','default',true);
        $this->jquery->html('#text-dropdown-questionForm-mytags-0','<i class="tags icon"></i><span class="text">Assign tags</span>',true);
        $this->jquery->html('#text-dropdown-questionForm-type-0','Select Type',true);
        $this->jquery->exec('$("#mainsegment").fadeIn()',true);
        $this->jquery->getOnClick ( '#dropdown-questionForm-type-0 .menu .item', 'question/getform', '#response-form', [
            "stopPropagation"=>false,
	        'attr' => 'data-value',
	        'hasLoader' => false,
	        'jsCallback' =>'$("#dropdown-typeq")'
	    ] );
	    return $frm;
	}

	public function getQuestionDataTable($questions){
	    $dt = $this->jquery->semantic ()->JsonDataTable ( 'dtItems', Question::class, $questions );
	    $msg = new HtmlMessage ( '', TranslatorManager::trans('noDisplay',[],'main') );
	    $msg->addIcon ( "x" );
	    $dt->setEmptyMessage ( $msg );
	    $dt->setFields ( [
	        'caption',
	        'tags',
	        'idTypeq',
            'answers',
            'action2'
	    ] );
	    $dt->setStyle('margin-top:2em;');

	    $dt->setCaptions([
	        TranslatorManager::trans('caption',[],'main'),
            'Tags',
            'Type'
	    ]);
	    $dt->setValueFunction('answers', function ($answers) {
            if ($answers != null and $answers != '__answers__') {
                $score = 0;
                foreach ($answers as $answer) {
                    $score+=$answer->getScore();
                }
                return $score.'pts';
            }
        });
        $dt->fieldAsDropDown('action2',[1=>'<i class="eye icon"></i>Preview',2=>'<i class="edit icon"></i>Edit',3=>'<i class="delete icon"></i>Delete']);
	    $dt->setStyle('border-radius: .5em;margin-top:1em');
	    $dt->setIdentifierFunction ( 'getId' );
	    $dt->setColWidths([0=>8,1=>4,2=>2,3=>1,4=>1]);
	    $dt->setEdition ();
        $dt->setValueFunction('tags', function ($tags) {
            if ($tags != null and $tags != '__tags__') {
                $res = [];
                foreach ($tags as $tag) {
                    $label = new HtmlLabel($tag->getId(), $tag->getName());
                    $res[] = $label->setClass('ui ' . $tag->getColor() . ' label');
                }
                return $res;
            }
        });
        $dt->setValueFunction('idTypeq', function ($type) {
            if($type!='__idTypeq__'){
                $typeq = [1=>['name'=>'QCM','icon'=>'check square'],2=>['name'=>'courte','icon'=>'bars'],3=>['name'=>'longue','icon'=>'align left'],4=>['name'=>'code','icon'=>'code']];
                $label = new HtmlLabel('', $typeq[$type]['name'],$typeq[$type]['icon']);
                $label->setStyle('display:inline-flex;');
                return $label;
            }
            return $type;
        });
        $this->jquery->html('#htmltr-dtItems-tr-__id__-1','__tags__',true);
        $dt->paginate(1,DAO::count(Question::class,'idUser=?',[USession::get('activeUser')['id']]),30);
        $this->jquery->attr('.field .dropdown.icon','class','ellipsis vertical icon',true);
        $dt->setUrls(["question/jsonPagination"]);
        $this->jquery->exec('function onLoad(){',true);
	    $this->jquery->getOnClick ( '.field .ui.selection.dropdown .menu .item', Router::path ('question.delete',[""]), '', [
	        'hasLoader' => 'internal',
	        'jsCondition'=>'$(this).attr("data-value")==3',
	        'jsCallback'=>'$(self).closest("tr").remove();$("body").toast({position: "center top", message: "Sucess",class: "success", });',
            'before'=>'url = "'.Router::path('question.delete',['']).'"+$(this).closest("tr").attr("data-ajax");',
	    ] );
        $this->jquery->getOnClick ( '.field .ui.selection.dropdown .menu .item', Router::path ('question.patch',[""]), '#response', [
            'hasLoader' => 'internal',
            'jsCondition'=>'$(this).attr("data-value")==2',
            'before'=>'url = "'.Router::path ('question.patch',[""]).'"+$(this).closest("tr").attr("data-ajax");',
        ] );
	    $this->jquery->ajaxOnClick ( '.field .ui.selection.dropdown .menu .item', Router::path('question.preview',['']) , '#response-modal', [
	        'hasLoader' => 'internal',
	        'method' => 'get',
            'jsCondition'=>'$(this).attr("data-value")==1',
	        'before'=>'url = "'.Router::path('question.preview',['']).'"+$(this).closest("tr").attr("data-ajax");',
	        'jsCallback'=>'$("#modal").modal("show");'
	    ] );
        $this->jquery->exec('}',true);
        $dt->onPageChange('onLoad();$(".ui.dropdown.selection").dropdown({"action": "activate","on": "hover","showOnFocus": true});',true);
	}
}