<?php

namespace services\UI;

use Ajax\php\ubiquity\JsUtils;
use models\Qcm;
use models\Tag;
use Ajax\semantic\html\collections\HtmlMessage;
use models\Question;
use Ubiquity\orm\DAO;
use Ubiquity\translation\TranslatorManager;
use Ubiquity\utils\http\USession;
use Ubiquity\controllers\Router;
use Ajax\semantic\html\elements\HtmlLabel;
use Ajax\service\JArray;

class QcmUIService {
    
    protected $jquery;
    protected $semantic;
    
    public function __construct(JsUtils $jq) {
        $this->jquery = $jq;
        $this->semantic = $jq->semantic ();
    }
 
    public function modal(){
        $modal = $this->jquery->semantic()->htmlModal('modal');
        $modal ->addContent('<div id="response-modal"></div>');
    }	
    
    public function questionBankToolbar(){
        $mytags = DAO::getAll( Tag::class, 'idUser=?',false,[USession::get('activeUser')['id']]);
        $dd = $this->jquery->semantic()->htmlDropdown('Filter','',JArray::modelArray ( $mytags, 'getId','getName' ));
        $dd->asSearch('tags',true);
        $toolbar = $this->jquery->semantic()->htmlMenu('QuestionBank');
        $toolbar->addDropdownAsItem($dd);
        $toolbar->addHeader(TranslatorManager::trans('questionBank',[],'main'));
        $toolbar->setClass('ui top attached menu');
        return $toolbar;
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
    public function getQcmCards($qcms){
        $cards=$this->jquery->semantic()->htmlCardGroups("cardsqcm");
        $cards->setWide(3);
        $cards->fromDatabaseObjects($qcms,function($qcm) use ($cards){
            $card=$cards->newItem("card-".$qcm->getId());
            $card->setProperty('data-ajax',$qcm->getId());
            $card->addItemHeaderContent($qcm->getName(),'',$qcm->getDescription());
            $extra=$card->addExtraContent();
            $totalscore=0;
            foreach ($qcm->getQuestions() as $question){
                foreach ($question->getAnswers() as $answer){
                    if($answer->getScore()>0){
                        $totalscore+=$answer->getScore();
                    }
                }
            }
            $bts=$this->jquery->semantic()->htmlButtonGroups("bts1",["Edit","Delete"]);
            $bts->addClasses(["basic _edit","basic red _delete"]);
            $bts->getItem(0)->setProperty('data-ajax',$qcm->getId());
            $bts->getItem(1)->setProperty('data-ajax',$qcm->getId());
            $extra->addContent('Grading scale: '.$totalscore.'pts');
            $extra->addMeta('<i class="icon question"></i>'.count($qcm->getQuestions()).' Questions');
            $extra->addContentText($qcm->getCdate(),"right");
            $extra->addContentText($bts);

            return $card;
        });
        $this->jquery->getOnClick ( '._delete', Router::path ('qcm.delete',[""]), '#response', [
            'hasLoader' => 'internal',
            'attr' => 'data-ajax'
        ] );
        $this->jquery->getOnClick ( '._edit', Router::path ('qcm.patch',[""]), '#response', [
            'hasLoader' => 'internal',
            'attr' => 'data-ajax'
        ] );
        return $cards;
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
        $this->jquery->jsonArrayOn('change','#filterTags','#dtBankImport-tr-__id__',Router::path('qcm.filter.bank'),'post',[
            'params'=>'{tags:$("#input-filterTags").val(),types:$("#input-filterType").val()}',
            'jsCallback'=>'$(".ui.dropdown.selection").dropdown({"action": "activate","on": "hover","showOnFocus": true});'
        ]);
        $this->jquery->jsonArrayOn('change','#filterType','#dtBankImport-tr-__id__',Router::path('qcm.filter.bank'),'post',[
            'params'=>'{tags:$("#input-filterTags").val(),types:$("#input-filterType").val()}',
            'jsCallback'=>'$(".ui.dropdown.selection").dropdown({"action": "activate","on": "hover","showOnFocus": true});'
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
    
    public function questionDataTable($name,$questions,$checked) {
        $q = new Question ();
        $dt = $this->jquery->semantic ()->dataTable($name, $q,$questions);
        $dt->setFields ( [
            'caption',
            'tags',
            'typeq',
            'action'
        ] );
        $dt->setcaptions([
            TranslatorManager::trans('caption',[],'main'),
            'tags',
            'type',
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
            $toolbar = $this->questionBankToolbar();
            $dt->setToolbar($toolbar);
        }
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
        $dt->setValueFunction('typeq', function ($typeq) {
            if ($typeq != null) {
                $label = new HtmlLabel('', $typeq->getCaption());
                $label->setClass('ui circular label');
                return $label;
            }
        });
        $dt->setIdentifierFunction('getId');
        $dt->setColWidths([
            0 => 9,
            1 => 2,
            2 => 1,
            3 => 2
        ]);
        return $dt;
    }
    public function questionBankImportDataTable($questions){
        $dt = $this->jquery->semantic ()->JsonDataTable ( 'dtBankImport', Question::class, $questions );
        $msg = new HtmlMessage ( '', TranslatorManager::trans('noDisplay',[],'main') );
        $msg->addIcon ( "x" );
        $dt->setEmptyMessage ( $msg );
        $dt->setFields ( [
            'caption',
            'tags',
            'idTypeq',
            'answers',
            'action'
        ] );
        $dt->setStyle('margin-top:2em;');
        $dt->setCaptions([
            TranslatorManager::trans('caption',[],'main'),
            'Tags',
            'Type'
        ]);
        $dt->setStyle('border-radius: .5em;margin-top:1em');
        $dt->setIdentifierFunction ( 'getId' );
        $dt->setColWidths([0=>8,1=>3,2=>2,3=>1,4=>1]);
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

        $dt->setValueFunction('answers', function ($answers) {
            if ($answers != null and $answers != '__answers__') {
                $score = 0;
                foreach ($answers as $answer) {
                    $score+=$answer->getScore();
                }
                return $score.'pts';
            }
        });


        $dt->insertDefaultButtonIn(4, 'plus','_add circular green ',false,null);
        $dt->insertDefaultButtonIn(4, 'x','_remove hide circular red ',false,null);

        $dt->paginate(1,DAO::count(Question::class,'idUser=?',[USession::get('activeUser')['id']]),30);
        $this->jquery->attr('.field .dropdown.icon','class','ellipsis vertical icon',true);
        $dt->setUrls(["question/jsonPagination"]);
        $this->jquery->html('#htmltr-dtBankImport-tr-__id__-1','__tags__',true);
        $this->jquery->html('#htmltr-dtBankImport-tr-__id__-3','__answers__',true);
        $this->jquery->exec('function updateCounter(){
                                var sum = 0;
                                $("#dtBankChecked tr td:nth-child(4)").each(function(){
                                sum += parseFloat($(this).text());  // Or this.innerHTML, this.innerText
                            });
                            var countq = $("#dtBankChecked tr").length;
                            $("#counter").html(sum+"pts");
                            $("#counter2").html(countq);
                            }',true);
        $this->jquery->jsonArrayOn('click','._add','#dtBankImport-tr-__id__',Router::path('qcm.add.question'),'post',[
            'attr'=>'data-ajax',
            'params'=>'{tags:$("#input-filterTags").val(),types:$("#input-filterType").val()}',
            'listenerOn'=>'body',
            'before'=>'$(self).attr("class","hiddenbutton");$(self).closest("._element").prependTo("#dtBankChecked");',
            'jsCallback'=>'$(self).closest("tr").next().find("._remove").removeClass("hide");updateCounter();'
        ]);
        $this->jquery->jsonArrayOn('click','._remove','#dtBankImport-tr-__id__',Router::path('qcm.delete.question'),'post',[
            'attr'=>'data-ajax',
            'params'=>'{tags:$("#input-filterTags").val(),types:$("#input-filterType").val()}',
            'listenerOn'=>'body',
            'jsCallback'=>'$(self).closest("._element").remove();updateCounter();'
        ]);
    }


}