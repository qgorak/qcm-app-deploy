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
    
    public function getQcmDataTable($qcms){
        $dt = $this->jquery->semantic ()->dataTable ( 'dtQcms', Question::class, $qcms );
        $msg = new HtmlMessage ( '', TranslatorManager::trans('noDisplay',[],'main') );
        $msg->addIcon ( "x" );
        $dt->setEmptyMessage ( $msg );
        $dt->setFields ( [
            'name',
            'description',
            'cdate',
        ] );
        $dt->insertDeleteButtonIn(3,true);
        $dt->insertEditButtonIn(3,true);
        $dt->insertDisplayButtonIn(3,true);
        $dt->setClass(['ui very basic table']);
        $dt->setCaptions([
            TranslatorManager::trans('name',[],'main')
        ]);
        $dt->setIdentifierFunction ( 'getId' );
        $dt->setColWidths([0=>2,1=>8,2=>3,2=>3]);
        $dt->setEdition ();
        $this->jquery->getOnClick ( '._delete', Router::path ('qcm.delete',[""]), '#response', [
            'hasLoader' => 'internal',
            'attr' => 'data-ajax'
        ] );
        $this->jquery->ajaxOnClick ( '._display', Router::path('qcm.preview',['']) , '#response-modal', [
            'hasLoader' => 'internal',
            'method' => 'get',
            'attr' => 'data-ajax',
            'jsCallback'=>'$("#modal").modal("show");'
        ] );
        $this->jquery->getOnClick ( '._edit', Router::path ('qcm.patch',[""]), '#response', [
            'hasLoader' => 'internal',
            'attr' => 'data-ajax'
        ] );
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
        $dt->setColWidths([0=>8,1=>5,2=>2,3=>1]);
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

        $dt->insertDefaultButtonIn(3, 'plus','_add circular green ',false,null);
        $dt->insertDefaultButtonIn(3, 'x','_remove hide circular red ',false,null);
        $this->jquery->html('#htmltr-dtItems-tr-__id__-1','__tags__',true);
        $dt->paginate(1,DAO::count(Question::class,'idUser=?',[USession::get('activeUser')['id']]),30);
        $this->jquery->attr('.field .dropdown.icon','class','ellipsis vertical icon',true);
        $dt->setUrls(["question/jsonPagination"]);
        $this->jquery->html('#htmltr-dtBankImport-tr-__id__-1','__tags__',true);
        $this->jquery->exec('function onLoad(){',true);
        $this->jquery->jsonArrayOn('click','._add','#dtBankImport-tr-__id__',Router::path('qcm.add.question'),'get',[
            'attr'=>'data-ajax',
            'listenerOn'=>'body',
            'before'=>'$(self).attr("class","hiddenbutton");$(self).closest("._element").prependTo("#dtBankChecked");',
            'jsCallback'=>'onLoad();$(self).closest("tr").next().find("._remove").removeClass("hide");'
        ]);
        $this->jquery->jsonArrayOn('click','._remove','#dtBankImport-tr-__id__',Router::path('qcm.delete.question'),'get',[
            'attr'=>'data-ajax',
            'listenerOn'=>'body',
            'jsCallback'=>'onLoad();$(self).closest("._element").remove();'
        ]);
        $this->jquery->exec('}onLoad();',true);
    }


}