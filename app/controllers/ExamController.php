<?php
namespace controllers;

use Ubiquity\controllers\Router;
use Ubiquity\orm\DAO;
use Ubiquity\utils\http\URequest;
use services\ExamDAOLoader;
use DateTime;
use models\Exam;
use models\Qcm;
use models\Group;
use Ubiquity\translation\TranslatorManager;
use Ajax\service\JArray;
use models\Option;
use models\Examoption;

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
        $exams=$this->jquery->semantic()->dataTable('myExam',Exam::class,$exam);
        $exams->setFields([
        	'dated',
        	'datef',
        	'qcm',
        	'group'
        ]);
        $exams->setCaptions([
        	'Date de début',
        	'Date de fin',
        	'QCM',
        	'Groupe'
        ]);
        $exams->setValueFunction('qcm',function($v){return $v->getName();});
        $exams->setValueFunction('group',function($v){return $v->getName();});
    }
    
    /**
     *
     * @route('/','name'=>'exam')
     */
    public function index(){
        $this->jquery->ajaxOnClick('#addExam',Router::path ('examAdd'),'#response',[
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
        $this->jquery->exec("
        $('.ui.dropdown').dropdown();
		$('.ui.calendar').calendar();
		$('#rangestart').calendar({
          type: 'date',
          endCalendar: $('#rangeend')
        });
        $('#rangeend').calendar({
          type: 'date',
          startCalendar: $('#rangestart')
        });",true);
        $exam=$this->jquery->semantic()->dataForm('exam',new Exam());
        $exam->setFields([
        	'idQcm',
        	'idGroup'
        ]);
        $exam->setCaptions([
        	'QCM',
        	'Group'
        ]);
        $exam->fieldAsDropDown('idQcm',JArray::modelArray($qcm,'getId','getId'));
        $exam->fieldAsDropDown('idGroup',JArray::modelArray($groups,'getId','getId'));
        $this->jquery->postFormOnClick('#examSubmit',Router::path ('examAddSubmit'),'examAdd','#response',[
        	'hasloader'=>'internal'
        ]);
        $this->jquery->renderView('ExamController/add.html');
    }
    
    /**
     * @post('add','name'=>'examAddSubmit')
     */
    public function addSubmit(){
        $exam=new Exam();
        $dated=str_replace(',','',URequest::post('dated'));
        $dated = new DateTime($dated);
        $datef=str_replace(',','',URequest::post('datef'));
        $datef = new DateTime($datef);
        $exam->setDated(date_format($dated,'Y-m-d H:i'));
        $exam->setDatef(date_format($datef,'Y-m-d H:i'));
        $exam->setQcm(DAO::getById(Qcm::class,URequest::post('idQcm'),false));
        $exam->setGroup(DAO::getById(Group::class,URequest::post('idGroup'),false));
        $exam->setOptions(URequest::post('options'));
        DAO::save($exam,true);
        $this->displayMyExam();
        var_dump(URequest::getDatas());
        $this->jquery->renderView('ExamController/display.html');
    }
}

