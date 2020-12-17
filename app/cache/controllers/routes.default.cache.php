<?php
return array("/loginForm/"=>array("get"=>array("controller"=>"controllers\\BaseAuthController","action"=>"loginform","parameters"=>array(),"name"=>"loginform","cache"=>false,"duration"=>false)),"/question/"=>array("controller"=>"controllers\\QuestionController","action"=>"index","parameters"=>array(),"name"=>"question","cache"=>false,"duration"=>false),"/notification/json/"=>array("get"=>array("controller"=>"controllers\\NotificationController","action"=>"json","parameters"=>array(),"name"=>"notification.json","cache"=>false,"duration"=>false)),"/qcm/setLoader/(.+?)/"=>array("controller"=>"controllers\\QcmController","action"=>"setLoader","parameters"=>array(0),"name"=>"QcmController-setLoader","cache"=>false,"duration"=>false),"/qcm/"=>array("controller"=>"controllers\\QcmController","action"=>"index","parameters"=>array(),"name"=>"qcm","cache"=>false,"duration"=>false),"/qcm/add/"=>array("get"=>array("controller"=>"controllers\\QcmController","action"=>"add","parameters"=>array(),"name"=>"qcm.add","cache"=>false,"duration"=>false),"post"=>array("controller"=>"controllers\\QcmController","action"=>"submit","parameters"=>array(),"name"=>"qcm.submit","cache"=>false,"duration"=>false)),"/qcm/addQuestion/(.+?)/"=>array("get"=>array("controller"=>"controllers\\QcmController","action"=>"addQuestionToQcm","parameters"=>array(0),"name"=>"qcm.add.question","cache"=>false,"duration"=>false)),"/qcm/questionBankImport/"=>array("get"=>array("controller"=>"controllers\\QcmController","action"=>"displayQuestionBankImport","parameters"=>array(),"name"=>"qcm.display.bank","cache"=>false,"duration"=>false)),"/qcm/deleteQuestion/(.+?)/"=>array("delete"=>array("controller"=>"controllers\\QcmController","action"=>"removeQuestionToQcm","parameters"=>array(0),"name"=>"qcm.delete.question","cache"=>false,"duration"=>false)),"/qcm/filterQuestionBank/"=>array("post"=>array("controller"=>"controllers\\QcmController","action"=>"filterQuestionBank","parameters"=>array(),"name"=>"qcm.filter","cache"=>false,"duration"=>false)),"/qcm/delete/(.+?)/"=>array("get"=>array("controller"=>"controllers\\QcmController","action"=>"delete","parameters"=>array(0),"name"=>"qcm.delete","cache"=>false,"duration"=>false)),"/qcm/preview/(.+?)/"=>array("get"=>array("controller"=>"controllers\\QcmController","action"=>"preview","parameters"=>array(0),"name"=>"qcm.preview","cache"=>false,"duration"=>false)),"/question/setLoader/(.+?)/"=>array("controller"=>"controllers\\QuestionController","action"=>"setLoader","parameters"=>array(0),"name"=>"QuestionController-setLoader","cache"=>false,"duration"=>false),"/question/add/"=>array("get"=>array("controller"=>"controllers\\QuestionController","action"=>"add","parameters"=>array(),"name"=>"question.add","cache"=>false,"duration"=>false),"post"=>array("controller"=>"controllers\\QuestionController","action"=>"submit","parameters"=>array(),"name"=>"question.submit","cache"=>false,"duration"=>false)),"/notification/"=>array("controller"=>"controllers\\NotificationController","action"=>"index","parameters"=>array(),"name"=>"notification","cache"=>false,"duration"=>false),"/question/delete/(.+?)/"=>array("get"=>array("controller"=>"controllers\\QuestionController","action"=>"delete","parameters"=>array(0),"name"=>"question.delete","cache"=>false,"duration"=>false)),"/question/patch/(.+?)/"=>array("get"=>array("controller"=>"controllers\\QuestionController","action"=>"patch","parameters"=>array(0),"name"=>"question.patch","cache"=>false,"duration"=>false)),"/question/preview/(.+?)/"=>array("get"=>array("controller"=>"controllers\\QuestionController","action"=>"preview","parameters"=>array(0),"name"=>"question.preview","cache"=>false,"duration"=>false)),"/question/getform/(.+?)/(.*?)"=>array("controller"=>"controllers\\QuestionController","action"=>"getform","parameters"=>array(0,"~1"),"name"=>"QuestionController-getform","cache"=>false,"duration"=>false),"/question/getByTags/"=>array("post"=>array("controller"=>"controllers\\QuestionController","action"=>"getByTags","parameters"=>array(),"name"=>"question.getBy.tags","cache"=>false,"duration"=>false)),"/question/displayMyQuestions/"=>array("get"=>array("controller"=>"controllers\\QuestionController","action"=>"displayMyQuestions","parameters"=>array(),"name"=>"question.my","cache"=>false,"duration"=>false)),"/question/submitpatch/"=>array("post"=>array("controller"=>"controllers\\QuestionController","action"=>"submitPatch","parameters"=>array(),"name"=>"question.submit.patch","cache"=>false,"duration"=>false)),"/tag/(index/)?"=>array("controller"=>"controllers\\TagController","action"=>"index","parameters"=>array(),"name"=>"TagController-index","cache"=>false,"duration"=>false),"/tag/my/"=>array("get"=>array("controller"=>"controllers\\TagController","action"=>"my","parameters"=>array(),"name"=>"tag.my","cache"=>false,"duration"=>false)),"/tag/submit/"=>array("post"=>array("controller"=>"controllers\\TagController","action"=>"submit","parameters"=>array(),"name"=>"tag.submit","cache"=>false,"duration"=>false)),"/user/setLoader/(.+?)/"=>array("controller"=>"controllers\\UserController","action"=>"setLoader","parameters"=>array(0),"name"=>"UserController-setLoader","cache"=>false,"duration"=>false),"/user/"=>array("controller"=>"controllers\\UserController","action"=>"index","parameters"=>array(),"name"=>"user","cache"=>false,"duration"=>false),"/notification/refresh/"=>array("get"=>array("controller"=>"controllers\\NotificationController","action"=>"refresh","parameters"=>array(),"name"=>"refresh","cache"=>false,"duration"=>false)),"/notification/setLoader/(.+?)/"=>array("controller"=>"controllers\\NotificationController","action"=>"setLoader","parameters"=>array(0),"name"=>"NotificationController-setLoader","cache"=>false,"duration"=>false),"/registerForm/"=>array("get"=>array("controller"=>"controllers\\BaseAuthController","action"=>"registerform","parameters"=>array(),"name"=>"registerform","cache"=>false,"duration"=>false)),"/exam/next/"=>array("post"=>array("controller"=>"controllers\\ExamController","action"=>"nextQuestion","parameters"=>array(),"name"=>"exam.next","cache"=>false,"duration"=>false)),"/login/"=>array("post"=>array("controller"=>"controllers\\BaseAuthController","action"=>"loginPost","parameters"=>array(),"name"=>"loginPost","cache"=>false,"duration"=>false)),"/terminate/"=>array("get"=>array("controller"=>"controllers\\BaseAuthController","action"=>"terminate","parameters"=>array(),"name"=>"terminate","cache"=>false,"duration"=>false)),"/register/"=>array("post"=>array("controller"=>"controllers\\BaseAuthController","action"=>"registerPost","parameters"=>array(),"name"=>"registerPost","cache"=>false,"duration"=>false)),"/Correction/setLoader/(.+?)/"=>array("controller"=>"controllers\\CorrectionController","action"=>"setLoader","parameters"=>array(0),"name"=>"CorrectionController-setLoader","cache"=>false,"duration"=>false),"/Correction/(index/)?"=>array("controller"=>"controllers\\CorrectionController","action"=>"index","parameters"=>array(),"name"=>"CorrectionController-index","cache"=>false,"duration"=>false),"/Correction/myresult/(.+?)/(.+?)/"=>array("controller"=>"controllers\\CorrectionController","action"=>"result","parameters"=>array(0,1),"name"=>"Correction.myExam","cache"=>false,"duration"=>false),"/Correction/correctAnswer/"=>array("post"=>array("controller"=>"controllers\\CorrectionController","action"=>"correctAnswer","parameters"=>array(),"name"=>"correct.answer","cache"=>false,"duration"=>false)),"/exam/setLoader/(.+?)/"=>array("controller"=>"controllers\\ExamController","action"=>"setLoader","parameters"=>array(0),"name"=>"ExamController-setLoader","cache"=>false,"duration"=>false),"/exam/"=>array("controller"=>"controllers\\ExamController","action"=>"index","parameters"=>array(),"name"=>"exam","cache"=>false,"duration"=>false),"/exam/add/"=>array("get"=>array("controller"=>"controllers\\ExamController","action"=>"add","parameters"=>array(),"name"=>"examAdd","cache"=>false,"duration"=>false),"post"=>array("controller"=>"controllers\\ExamController","action"=>"addSubmit","parameters"=>array(),"name"=>"examAddSubmit","cache"=>false,"duration"=>false)),"/exam/get/(.+?)/"=>array("get"=>array("controller"=>"controllers\\ExamController","action"=>"getExam","parameters"=>array(0),"name"=>"exam.get","cache"=>false,"duration"=>false)),"/exam/start/(.+?)/"=>array("get"=>array("controller"=>"controllers\\ExamController","action"=>"ExamStart","parameters"=>array(0),"name"=>"exam.start","cache"=>false,"duration"=>false)),"/exam/oversee/(.+?)/"=>array("get"=>array("controller"=>"controllers\\ExamController","action"=>"ExamOverseePage","parameters"=>array(0),"name"=>"examStart","cache"=>false,"duration"=>false)),"/change/(.+?)/"=>array("controller"=>"controllers\\MainController","action"=>"changeLanguage","parameters"=>array(0),"name"=>"changeLanguage","cache"=>false,"duration"=>false),"/group/setLoader/(.+?)/"=>array("controller"=>"controllers\\GroupController","action"=>"setLoader","parameters"=>array(0),"name"=>"GroupController-setLoader","cache"=>false,"duration"=>false),"/group/"=>array("controller"=>"controllers\\GroupController","action"=>"index","parameters"=>array(),"name"=>"group","cache"=>false,"duration"=>false),"/group/view/(.+?)/"=>array("get"=>array("controller"=>"controllers\\GroupController","action"=>"viewGroup","parameters"=>array(0),"name"=>"groupView","cache"=>false,"duration"=>false)),"/group/add/"=>array("get"=>array("controller"=>"controllers\\GroupController","action"=>"addGroup","parameters"=>array(),"name"=>"groupAdd","cache"=>false,"duration"=>false),"post"=>array("controller"=>"controllers\\GroupController","action"=>"addSubmit","parameters"=>array(),"name"=>"GroupAddSubmit","cache"=>false,"duration"=>false)),"/group/join/"=>array("get"=>array("controller"=>"controllers\\GroupController","action"=>"joinGroup","parameters"=>array(),"name"=>"groupJoin","cache"=>false,"duration"=>false),"post"=>array("controller"=>"controllers\\GroupController","action"=>"joinSubmit","parameters"=>array(),"name"=>"joinSubmit","cache"=>false,"duration"=>false)),"/group/delete/(.+?)/"=>array("get"=>array("controller"=>"controllers\\GroupController","action"=>"groupDelete","parameters"=>array(0),"name"=>"groupDelete","cache"=>false,"duration"=>false)),"/group/demand/(.+?)/"=>array("get"=>array("controller"=>"controllers\\GroupController","action"=>"getUserDemand","parameters"=>array(0),"name"=>"groupDemand","cache"=>false,"duration"=>false)),"/group/valid/(.+?)/(.+?)/(.+?)/"=>array("get"=>array("controller"=>"controllers\\GroupController","action"=>"acceptDemand","parameters"=>array(0,1,2),"name"=>"groupDemandAccept","cache"=>false,"duration"=>false)),"/group/ban/"=>array("post"=>array("controller"=>"controllers\\GroupController","action"=>"banUser","parameters"=>array(),"name"=>"banUser","cache"=>false,"duration"=>false)),"/image/(index/)?"=>array("controller"=>"controllers\\ImageController","action"=>"index","parameters"=>array(),"name"=>"ImageController-index","cache"=>false,"duration"=>false),"/image/add/"=>array("post"=>array("controller"=>"controllers\\ImageController","action"=>"add","parameters"=>array(),"name"=>"ImageController-add","cache"=>false,"duration"=>false)),"/_default/"=>array("controller"=>"controllers\\MainController","action"=>"index","parameters"=>array(),"name"=>"MainController-index","cache"=>false,"duration"=>false),"/user/lang/"=>array("post"=>array("controller"=>"controllers\\UserController","action"=>"langSubmit","parameters"=>array(),"name"=>"langSubmit","cache"=>false,"duration"=>false)));