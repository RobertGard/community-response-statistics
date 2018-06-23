<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BaseObject
 *
 * @author robert
 */

class BaseObject 
{

  public $tplPath = '';
  public $tplControllerPath = '';
   
  function __construct(){
    $this->tplPath = ROOT_PATH . '/views/layouts/';
    $this->tplControllerPath = ROOT_PATH . '/views/'.strtolower(str_replace('Controller','',get_called_class())).'/';
  }
   
  private function _renderPartial($fullpath,$variables=array(),$output=true){
    extract($variables);
     
    if( file_exists($fullpath) ){
      if( !$output )
        ob_start();
      include $fullpath;
      return !$output?ob_get_clean():true;
    }else
      throw new Except('File '.$fullpath.'.php not found');
     
  }
  /**
   * renderPartial - метод доступный в контроллере, для вывода файла шаблона.
   * Не запускает больше никаких файлов. Удобен при ajax вызове контроллера
   *
   * @params $filename - название шаблона в папке views/название контроллера/{}.php
   * @params $variables - ключи массива будут доступны в шаблоне как переменные с
   * теми же именами
   * @params $output - если указать false, то данные из шаблона не будут выведены в основной поток а будут возвращены методом
   */
  public function renderPartial($filename,$variables=array(),$output=true){
    $file = $this->tplControllerPath.str_replace('..','',$filename).'.php';
    return $this->_renderPartial($file,$variables,$output);
  }
   
  /**
   * render - метод выполняет полный вывод страницы на экран. При этом в нее включается
   * содержимое файла шаблона $filename
   *
   * @params - все параметры идентичны renderPartial
   */
  public function render($filename,$variables=array(),$output=true){
    $content = $this->renderPartial($filename,$variables,false);
    return $this->_renderPartial($this->tplPath.'main.php',array_merge(array('content'=>$content),$variables),$output);
  }
}