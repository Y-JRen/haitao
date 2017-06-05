<?php
function smarty_function_widget($params, &$smarty) {
   if (empty($params['class'])) {
     throw new Exception('Widget is missing name.');
   }
   
    require_once  SYSROOT.'/lib/Widget/SmartyWidget.php';
    $controller = sprintf('%s.php', SYSROOT.'/lib/Widget/'.$params['class'].'/'.$params['class']);
    if (file_exists($controller)) {
        require_once ($controller);
    } else {
      eval(sprintf('class %s extends Smarty_Widget {}', $params['class'])); 
    } 
    
    $params['smarty'] = $smarty;           
    if (class_exists($params['class'])) {
        $widget = call_user_func(array($params['class'] ,'factory'), $params);
        if(method_exists($widget, $params['action'])) 
        {
          $action = $params['action'];
          $widget->$action();
        }else{
          $widget->run();
        }      
     }else{
        throw new Exception('Widget class '.$params['class'].' does not exist!');
    }
}
