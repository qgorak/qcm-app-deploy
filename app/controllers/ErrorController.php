<?php
namespace controllers;

/**
 * Controller ErrorController
 */
class ErrorController extends ControllerBase{
    
    public function index(){}
  
    
    /**
      * @route("{url}","priority"=>-1000)
      */
     public function error($url){
        echo "<div class='ui container'><div class='ui error message'><div class='header'>404</div>The page `$url` you are loocking for doesn't exists!</div></div>";
     }
}