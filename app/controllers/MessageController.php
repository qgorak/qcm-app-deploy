<?php
namespace controllers;
use models\Exam;
use models\Message;
use models\User;
use services\DAO\MessageDAOLoader;
use Ubiquity\utils\http\URequest;

/**
 * Controller MessageController
 * @allow('role'=>'@USER')
 * @route('message','inherited'=>true,'automated'=>true)
 * @property \Ajax\php\ubiquity\JsUtils $jquery
 */
class MessageController extends ControllerBase{

    /**
     *
     * @autowired
     * @var MessageDAOLoader
     */
    private $loader;

    /**
     *
     * @param \services\DAO\MessageDAOLoader $loader
     */
    public function setLoader($loader) {
        $this->loader = $loader;
    }

	public function index(){}

    /**
     *
     * @post('post','name'=>'message.exam.post')
     */
    public function postMessage(){
        $message = new Message();
        $message->setContent(URequest::getPost()['message']);
        $target = new User();
        $target->setId(URequest::getPost()['target']);
        $message->setUser($target);
        $message->setSeen(0);
        $exam = new Exam();
        $exam->setId(URequest::getPost()['exam']);
        $message->setExam($exam);
        $this->loader->add($message);
        echo json_encode($message);

    }
}
