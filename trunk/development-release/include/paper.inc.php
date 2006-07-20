<?php
  
  /**
   * Academic Paper class
   *
   * This class provides basic functionality to work with
   * Papers. Almost all the information it stores is common to any
   * kind of academic paper. It was design based upon the paper model
   * that is handled by the UCSP - Projects. 
   *
   * PHP version 4
   *
   * @author 	Rodrigo Lazo <rlazo.paz@gmail.com>	
   * @copyright 2006 Rodrigo Lazo
   * @license 	http://www.opensource.org/licenses/lgpl-license.php  LGPL
   * @version   0.4
   */

  /**
   * Paper Class
   * 
   * This class is basically a wrapper around all the info that you
   * need of a Paper. Functions aren't very usefull besides filling
   * the information
   *
   */

include_once('latexfunctions.inc.php');

class Paper
{
  /**
   * Paper's information. Names are self-explaining. Authors and
   * Tutors hold almost the same information, so for next versions
   * could be created a class People to store both. Abstracts and
   * Keywords share the same concept language => information. 
   *
   * @access private
   */

  /** @var string **/
  var $_code;
  var $_title;
  var $_course;
  /** @var array **/
  var $_authors;  
  var $_tutors;
  var $_abstracts;
  var $_keywords;

  /**
   * Constructor
   *
   * It just assigns the Paper's title, course and code. All the
   * information left must me handled by their respective functions,
   * given that it they would be too many parameters to this function
   * to work with
   *
   * @access public
   * @param string $tit  Paper's title
   * @param string $cour  Paper's course
   */
  function Paper($code, $tit, $cour)
  {
    $this->_code = $code;
    $this->_title = $tit;
    $this->_course = $cour;
  }

  /**
   * Adds a new Author
   *
   * @param int $order          Author's order of relevance
   * @param string $code        Author's code
   * @param string $name        Author's name
   * @param string $lastname    Author's lastname
   * @param string $mail        Author's e-mail
   * @param string $webpage     Author webpage's url
   * @param array $institutions Author's affiliations
   *
   * @return bool 
   **/   
  function addAuthor($order, $code, $name, $lastname, $mail, $webpage, 
		     $institutions)
  {
    $this->_authors[$order] = array('code'         => $code,
				     'name'         => $name,
				     'lastname'     => $lastname,
				     'mail'         => $mail,
				     'webpage'      => $webpage,
				     'institutions' => $institutions);
    return true;
  }

  /**
   * Adds a new Tutor
   *
   * @param string $code        Tutor's code
   * @param string $name        Tutor's name
   * @param string $lastname    Tutor's lastname
   * @param string $mail        Tutor's e-mail
   * @param string $webpage     Tutor webpage's url
   * @param array $institutions Tutor's affiliations
   *
   * @return bool 
   **/  
  function addTutor($code, $name, $lastname, $mail, $webpage, $institutions)
  {
    $this->_tutors[] = array('code'         => $code,
			     'name'         => $name,
			     'lastname'     => $lastname,
			     'mail'         => $mail,
			     'webpage'      => $webpage,
			     'institutions' => $institutions);
    return true;
  }

  /**
   * Adds a new abstract
   *
   * @param string $language       Language's name
   * @param string $abstract       Language's abstract
   *
   * @return bool 
   **/
  function addAbstract($language, $abstract)
  {
    $this->_abstracts[$language] = $abstract;
    return true;
  }

  /**
   * Adds a new Keyword n-tuple 
   *
   * @param array $value      array in the form language => keyword
   *
   * @return bool 
   **/
  function addKeyword($value)
  {
    $this->_keywords[] = $value;
    return true;
  }
  
  /**
   * Returns the Paper's Title
   *
   * @return string
   */
  function getTitle()
  {
    return $this->_title;
  }

  /**
   * Returns the Paper's Title
   *
   * @return string
   */
  function getCourse()
  {
    return $this->_course;
  }
  /**
   * Returns the Paper's Authors
   *
   * @return array
   */ 
  function getAuthors()
  {
    return $this->_authors;
  }

  /**
   * Returns the Paper's Tutors
   *
   * @return array
   */ 
  function getTutors()
  {
    return $this->_tutors;
  }

  /**
   * Returns the Paper Code
   *
   * @return int
   */ 
  function getCode()
  {
    return $this->_code;
  }

  /**
   * Returns the Paper's Abstracts
   *
   * @return array
   */   
  function getAbstracts()
  {
    return $this->_abstracts;
  }
  
  /**
   * Returns the Paper's Keywords
   *
   * @return array
   */ 
  function getKeywords()
  {
    return $this->_keywords;
  }

  /**
   * Creates the paper's latex file. This file is based upon a
   * template
   *
   * @access public
   * @return bool
   */ 
  function generateLatex()
  {
    $dir = 'files/'.$this->_code.'/';
    // First we need to be sure that the directory for our paper do exists
    if (!is_dir($dir)) {
      mkdir($dir, 02777) or die("couldn't create directory");
    }
    $output = fopen($dir.$this->_code.'.tex','w');
    
    $doc = new LatexDoc;    
    $doc->addDate(' ');
    $doc->addUsePackage('babel', 'american,spanish');
    $doc->addUsePackage('inputenc', 'latin1');
    $doc->addTitle($this->_title);

    foreach (array('_authors','_tutors') as $var) {
      foreach ($this->$var as $value) {
	$tmp = $value['name'].' '.$value['lastname'];
	$email[] = $value['mail'];
	foreach ($value['institutions'] as $in) {
	  if (!isset($institution[$in['code']])) 
	    $institution[$in['code']] = $in['name']." -  ".$in['country'];
	$co[] = $in['code'];
	}
	$people[] = $tmp."(".join(",", $co).")";
	unset($co);
	}
    }
    foreach ($institution as $key => $val) {
      $res[] = "(".$key.")".$val;
    }
    
    $res = join(" \\and ",$res);
    $doc->addAuthor(join("\\and ", $people)."\\\\"."\nemails: ".join("\\and ",$email)."\\\\\n".$res);

    if (isset($this->_abstracts['spanish']))
      {
	$doc->addContent("\\section*{Resumen}\n".$this->_abstracts['spanish']."\n");
      }
    if (isset($this->_abstracts['english']))
      {
	$doc->addContent("\\section*{Abstract}\n".$this->_abstracts['english']."\n");
      }
    if (isset($this->_abstracts['portuguese']))
      {
	$doc->addContent("\\section*{Resumo}\n".$this->_abstracts['portuguese']."\n");
      }

    fwrite($output,$doc->generateDoc());
    fclose($output);
    $this->generateProceeding();
    chmod($dir.$this->_code.'.tex', 0777);
  }

  function generateProceeding()
  {
    $doc = new LatexDoc('include');

    $doc->addContent("\\label{".$this->_code."}\n");
    $doc->addContent("\\Title{".$this->_title."}\n");
    $doc->addContent("\\Author{");

    foreach (array('_authors','_tutors') as $var) {
      foreach ($this->$var as $value) {
	$tmp = $value['name'].' '.$value['lastname'];
	$email[] = latexEntities($value['mail']);
	foreach ($value['institutions'] as $in) {
	  if (!isset($institution[$in['code']])) 
	    $institution[$in['code']] = $in['name']." -  ".$in['country'];
	$co[] = $in['code'];
	}
	$people[] = $tmp."(".join(",", $co).")";
	unset($co);
	}
    }

    foreach ($institution as $key => $val) {
      $res[] = "(".$key.")".$val;
    }
    
    $res = join(", ",$res);
    $doc->addContent(join(",  ", $people)."\\\\"."\n\\textit{emails: ".join(", ",$email)."}\\\\\n".$res);

    $doc->addContent("}\n");

    if (isset($this->_abstracts['spanish']))
      {
	$doc->addContent("\\section*{Resumen}\n".$this->_abstracts['spanish']."\n");
      }
    if (isset($this->_abstracts['english']))
      {
	$doc->addContent("\\section*{Abstract}\n".$this->_abstracts['english']."\n");
      }
    if (isset($this->_abstracts['portuguese']))
      {
	$doc->addContent("\\section*{Resumo}\n".$this->_abstracts['portuguese']."\n");
      }
    
    $output = fopen('files/proceedings/'.$this->_code.'_proceedings.tex','w');

    fwrite($output, $doc->generateDoc());
    fclose($output);
  }
}
?>