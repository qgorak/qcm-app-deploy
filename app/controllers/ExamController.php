<?php
namespace controllers;

use Ubiquity\controllers\Router;
use Ubiquity\orm\DAO;
use Ubiquity\utils\http\URequest;
use services\ExamDAOLoader;
use models\Exam;
use models\Qcm;
use models\Group;
use Ubiquity\translation\TranslatorManager;

/**
 * Controller ExamController
 * @route('exam','inherited'=>true,'automated'=>true)
 * @property \Ajax\php\ubiquity\JsUtils $jquery
 */
class ExamController extends ControllerBase{

    /**
     *
     * @autowired
     * @var ExamDAOLoader
     */
    private $loader;
    
    /**
     *
     * @param \services\ExamDAOLoader $loader
     */
    public function setLoader($loader) {
        $this->loader = $loader;
    }
    
    private function displayMyExam() {
        $exam=$this->loader->all();
        $this->jquery->semantic()->dataTable('myExam',Exam::class,$exam);
    }
    
    /**
     *
     * @route('/','name'=>'exam')
     */
    public function index(){
        $this->jquery->ajaxOnClick('#addExam',Router::path ('examAdd',[""]),'#response',[
            'hasloader'=>'internal'
        ]);
        $this->displayMyExam();
        $this->_index($this->jquery->renderView('ExamController/display.html',[],true));
    }
    
    private function _index($response = '') {
        $this->jquery->renderView ( 'ExamController/index.html', [
            'response' => $response
        ] );
    }
    
    /**
     * @get('add','name'=>'examAdd')
     */
    public function add(){
        $qcm=$this->loader->allMyQCM();
        $groups=$this->loader->allMyGroup();
        $this->jquery->exec("$('#rangestart').calendar({
          type: 'date',
          endCalendar: $('#rangeend')
        });
        $('#rangeend').calendar({
          type: 'date',
          startCalendar: $('#rangestart')
        });",true);
        $dtQcm=$this->jquery->semantic()->dataTable('dtQcm',Qcm::class,$qcm);
        $dtQcm->setFields([
            'id',
            'name',
            'description',
            'add'
        ]);
        $dtQcm->setCaptions([
            'id',
            TranslatorManager::trans('name',[],'main'),
            TranslatorManager::trans('description',[],'main')
        ]);
        $dtQcm->insertDefaultButtonIn('add','plus','addQcm',false);
        $this->jquery->ajaxOnClick('.addQcm',Router::path('chooseQcm',['']),'#response',[
            'method'=>'post',
            'attr'=>'data-ajax'
        ]);
        $dtGroups=$this->jquery->semantic()->dataTable('dtGroups',Group::class,$groups);
        $dtGroups->setFields([
            'id',
            'name',
            'description',
            'add'
        ]);
        $dtGroups->setCaptions([
            'id',
            TranslatorManager::trans('name',[],'main'),
            TranslatorManager::trans('description',[],'main')
        ]);
        if(URequest::isAjax()){
            $this->jquery->renderView('ExamController/add.html');
        }
        else{
            $this->_index($this->jquery->renderView('ExamController/add.html',[],true));
        }
    }
    
    /**
     * @post('add','name'=>'examAddSubmit')
     */
    public function addSubmit(){
        var_dump(URequest::getDatas());
    }
    
    /**
     * @post('choose/{id}','name'=>'chooseQcm')
     */
    public function chooseQcm(int $id){
        $qcm=[DAO::getOne(Qcm::class,'id=?',false,[$id])];
        $chooseQcm=$this->jquery->semantic()->dataTable('chooseQcm',Qcm::class,$qcm);
        $chooseQcm->setFields([
            'name',
            'description',
            'remove'
        ]);
        $chooseQcm->insertDefaultButtonIn('remove','remove','removeQcm',false);
        $this->jquery->ajaxOnClick('.addQcm',Router::path('removeQcm',['']),'#response',[
            'method'=>'post',
            'attr'=>'data-ajax'
        ]);
        if(URequest::isAjax()){
            $this->jquery->renderView('ExamController/add.html');
        }
        else{
            $this->_index($this->jquery->renderView('ExamController/add.html',[],true));
        }
    }
}

