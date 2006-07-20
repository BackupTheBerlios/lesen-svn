<?php

class Sidebar
{

  var $content;

  function addContent($info, $type)
  {
    switch ($type) {
    case "links": 
      $this->content[] = generateLinks($info);
      break;
      
    default:
      print "wront content type";
      return false;
    }
  }

  function generateLinks($val)
  {
    $smarty = new Smarty();
    $smarty->assign('content',$val);
    return $smarty->fetch('links.tpl');
  }
}



?>