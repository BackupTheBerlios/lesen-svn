<?php
  
  /**
   * Latex functions
   *
   * This functions allow to work with Latex documents. At this point
   * is basically just to write them. 
   *
   * PHP version 4
   *
   * @author 	Rodrigo Lazo <rlazo.paz@gmail.com>	
   * @copyright 2006 Rodrigo Lazo
   * @license 	http://www.opensource.org/licenses/lgpl-license.php  LGPL
   * @version   0.2
   */

  /**
   *
   *@param string $name
   *@param string $content
   *@return string
   */
function latexEnviroment($name, $content)
{
  $begin = "\\begin{".$name."}\n";
  $end = "\\begin{".$name."}";
  return $begin.latexEntities($content).$end;
}

function latexEntities($string)
{
  for ($i = 0; $i < strlen($string); $i++) {
    if ($string{$i} == '_')
      $res .= '\_';
    else 
      $res .= $string{$i};
  }
  return $res;
}

class LatexDoc
{
  var $documentclass;
  var $type;
  var $packages;
  var $title;
  var $author;
  var $date;
  var $content;
  //  var $footer;

  function LatexDoc($type = 'article', $extra = '')
  {
    $this->type = $type;
    if (!strcmp($type,'include'))
      return;
    $this->documentclass = "\\documentclass[".$extra."]{".$type."}";
    $this->title = '';
    
  }

  function addUsePackage($package, $extra = '')
  {
    $this->packages[] = "\\usepackage[".$extra."]{".$package."}";
  }

  function addTitle($val)
  {
    $this->title = "\\title{".$val."}";
  }

  function addAuthor($val)
  {
    $this->author = "\\author{".$val."}";
  }

  function addDate($val)
  {
    $this->date = $val;
  }

  function addContent($val)
  {
    $this->content .= $val;
  }
  function generateDoc()
  {
    if (!strcmp($this->type, 'include'))
      return $this->content;
    else
      return $this->documentclass."\n".join("\n",$this->packages)."\n".
	$this->title."\n".$this->author."\n".$this->date."\n".
	"\\begin{document}\n\\maketitle\n".$this->content."\n"."\\end{document}";
  }

}

  

?>