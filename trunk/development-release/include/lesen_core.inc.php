<?php

/**
 * Lesen core: classes and functions
 *
 * Here are defined all the classes and functions that could be called
 * the "core". This structures define all the logic of the system
 * regardless the way it would be displayed. 
 *
 * PHP version 4
 *

 * This source code is free software; you can redistribute it
 * and/or modify it under the terms of the GNU Lesser General
 * Public License as published by the Free Software Foundation;
 * either version 2.1 of the License, or (at your option) any
 * later version.

 * This code is distributed in the hope that it will be
 * useful, but WITHOUT ANY WARRANTY; without even the implied
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
 * PURPOSE. See the GNU Lesser General Public License for more
 * details.

 * You should have received a copy of the GNU Lesser General
 * Public License along with this library; if not, write to the
 * Free Software Foundation, Inc., 59 Temple Place, Suite 330,
 * Boston, MA 02111-1307 USA
  
 * @author    Rodrigo Lazo Paz <rlazo.paz@gmail.com>
 * @license   http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
 * @version   0.5
 */

define('PERSON_CODE_MAX_LENGTH',4);
define('PAPER_CODE_MAX_LENGTH',4);

require_once('bundled-libs/MDB2/MDB2.php');
require_once('bundled-libs/PEAR.php');

/**
 * Lessen person class
 *
 * This class defines an author or tutor. Given that both share the
 * same information there was no need to create different classes. 
 */
class Lesen_person
{  
  // @var string
  var $_code;
  var $_name;
  var $_lastname;
  var $_webpage;
  var $_mail;
  // @var arrays. This vars only held the codes.
  var $_papers;
  var $_institutions;

  /**
   * Constructor
   *
   * Receives the person's code, then perfomes some (very) basic
   * validation and then retrieves all the rest using the
   * lesen_person::loadDB() function. 
   *
   * @params string $code  person's code
   *
   * @return null
   * @access public
   */
  function Lesen_person($code)
  {
    // we need to check wheter the db connection is open or not
    // TODO, improve this

    global $db;
    
    if (!isset($db))
      die('No db connection');

    if (strlen($code) > PERSON_CODE_MAX_LENGTH)
      die('wrong person code');

    $this->_code = $code;
    $this->loadDB($type);

  }

  /**
   * Database's information retriever
   *
   * Connects to the database and performs the necesary queries to
   * retrieve all the information about the person. All vars used in
   * the sql query must had been validated. Validation at this point
   * also relies on some way on the PEAR::DB package
   *
   * @return bool
   * @access public
   */
  function loadDB()
  {
    global $db;
    
    $query = "select person_lastname lastname, person_name name,
                     person_mail mail, person_webpage webpage
              from person
              where code = " . $db->quote($this->_code);
    $row =& $db->queryRow($query);
    $this->_name =  $row['name'];
    $this->_lastname =  $row['lastname'];
    $this->_webpage = $row['webpage'];
    $this->_mail = $row['mail'];


    $query = "select code_paper 
              from authoring
              where code_author = " . $db->quote($this->_code);
    $result =& $db->queryAll($query);

    foreach ($result as $row) 
      $this->_papers[] = $row['code_paper'];

    $query = "select code_institution
              from membership
              where code_person = " . $db->quote($this->_code);
    $result =& $db->queryAll($query);
    foreach ($result as $row) 
      $this->_institutions[] = $row['code_institution'];
  }

  /**
   * Database updater
   *
   * Given that all information is stored on the database, this
   * function rewrites all the data into the database, updating it.
   *
   * Maybe for next releases, it could verify if something has changed
   * and avoid the overload of doing something unnecesary
   *
   * @return bool 
   * @access public
   */
  function updateDB()
  {
    global $db;
    $query = "update person set 
                     person_name = " . $db->quote($this->_name) . " 
      person_lastname = " . $db->quote($this->_lastname) . " 
      person_mail = " . $db->quote($this->_mail) . " 
      person_webpage = " . $db->quote($this->_webpage) . " where code = " . $this->_code;
    
    //    $query = 
    print $query;
  }

  // FOR BACKWARDS COMPATIBILITY ONLY!!

  /**
   * Sets the person's e-mail
   *
   * @param string $email  e-mail address
   *
   * @return null
   * @access public
   */
  function setMail($email)
  {
    $this->_mail = $email;
  }

  /**
   * Sets the person's webpage
   *
   * @param string $page  page's url
   *
   * @return null
   * @access public
   */
  function setWebpage($page)
  {
    $this->_webpage = $page;
  }

  /**
   * Adds a new paper into the stack of the person's works
   *
   * @param string $code  paper's code
   *
   * @return null
   * @access public
   */
  function addPaper($code)
  {
    $this->_papers[] = $code;
  }

  /**
   * Adds a new institution into the stack of the person's memberships
   *
   * @param string $code institution's code
   *
   * @return null
   * @access public
   */
  function addInstitution($code)
  {
    $this->_institutions[] = $code;
  }

  /**
   * Returns the mail
   *
   * @return string
   * @access public
   */
  function getName()
  {
    return $this->_name;
  }

  /**
   * Returns the last name
   *
   * @return null
   * @access public
   */
  function getLastName()
  {
    return $this->_lastname;
  }

  /**
   * Returns the institutions array
   *
   * @return null
   * @access public
   */
  function getInstitutions()
  {
    return $this->_institutions;
  }

  /**
   * Returns person's webpage url
   *
   * @return string
   * @access public
   */
  function getWebPage()
  {
    return $this->_webpage;
  }

  /**
   * Returns person's mail address
   *
   * @return string
   * @access public
   */
  function getMail()
  {
    return $this->_mail;
  }
  /**
   * Returns the papers of the person
   *
   * @return array
   * @access public
   */
  function getPapers()
  {
    return $this->_papers;
  }
}
/**
 * Paper Class
 * 
 * This class is basically a wrapper around all the info that you
 * need of a Paper. Functions aren't very usefull besides filling
 * the information
 *
 */
class Lesen_paper
{

  /**
   * Paper's information. Names are self-explaining. We don't store
   * nothing but codes for authors, tutors. Abstracts and Keywords share
   * the same concept language => information.
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
   * Receives the paper's code, then perfomes some (very) basic
   * validation and then retrieves all the rest using the
   * lesen_person::loadDB() function.
   *
   * @access public
   * @param  string $code  paper's code
   */
  function Lesen_paper($code)
  {
    if (strlen($code) > PAPER_CODE_MAX_LENGTH)
      die('wrong paper code');
    $this->_code = $code;
    $this->loadDB();
  }


  /**
   * Database's information retriever
   *
   * Connects to the database and performs the necesary queries to
   * retrieve all the information about the paper. All information for
   * the sql query must had been validated.
   *
   * @return null
   * @access public
   */
  function loadDB()
  {
    global $db;

    $query = "select paper_title title, course.course_name course, 
                     des_spanish, des_english, des_portuguese 
              from paper, course 
              where paper.code = " . $db->quote($this->_code) ." and
              paper_course = course.code";

    /* paper information but keyword */
    $row =& $db->queryRow($query);

    $this->_course = $row['course'];
    $this->_title = $row['title'];
    
    // Get a better way to check problems
    if (is_null($this->_title))
      die("wrong code");

    $this->_abstracts['spanish'] = $row['des_spanish'];
    $this->_abstracts['english'] = $row['des_english'];
    $this->_abstracts['portuguese'] = $row['des_portuguese'];

    /* paper keywords */
    $query = "select k.keyword_spanish spanish, k.keyword_english english
              from keyword k, terms t
              where t.code_keyword = k.code and
                    t.code_paper = " . $db->quote($this->_code);

    $result =& $db->queryAll($query);
    foreach ($result as $row) {
      $this->_keywords[] = (array('spanish' => $row['spanish'],
				  'english' => $row['english']));
    }  
    
    /* author(s) information but membership(s) */
    $query = "select a.code_author code, r.rol_name rol
                     from authoring a, rol r
	      where a.code_paper = " . $db->quote($this->_code) . " and r.code = a.author_type
	            order by a.author_order";

    $result =& $db->queryAll($query);
    foreach ($result as $row) {
       // !!!!!!! HARDCODED !!!!!!!!!!!!!!!!!
      if ($row['rol'] == "tutor" )
	 $this->_tutors[] = $row['code'];
      else if  ($row['rol'] == "author" )
	$this->_authors[] = $row['code'];       
    }
  }


  // FOR BACKWARDS COMPATIBILITY ONLY!!

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
