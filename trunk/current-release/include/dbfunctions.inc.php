<?php


require_once('paper.inc.php');  
  /**
   * database interaction functions
   *
   * PHP version 4 and 5
   *
   * @author 	Rodrigo Lazo <rlazo.paz@gmail.com>	
   * @license 	http://www.gnu.org/licenses/gpl.html GPL License version 2
   * @version   0.6
   */

  /**
   * Creates the database connection
   *
   * @param string $host      hostname to connect to
   * @param string $user      username  
   * @param string $password  password
   * @param string $database  database name
   *
   * @return connection object
   */
function initdb($database)
{
  $db = mysql_connect($database['dbhostname'], $database['dbusername'], $database['dbpassword']) 
    or die ("cannot connect to database");
  mysql_select_db($database['dbname']) 
    or die ("wrong database name");
  return $db;
}

/**
 * Returns all information about a paper
 *
 * @param int $code	code of the paper to look for
 *
 * @return array 	all the information in array form splited in 3 categories, 
 *                      [paper], [author], [tutor]
 */
function getPaper($code)
{
  /* paper information but keyword */
  $result = mysql_fetch_array(mysql_query("select paper_title title, course.course_name course, 
                                                  des_spanish, des_english, des_portuguese 
                                           from paper, course 
                                           where paper.code = ".$code." and
                                                 paper_course = course.code"));

  $paper = new Paper($code, $result['title'], $result['course']);
  $paper->addAbstract('spanish',$result['des_spanish']);
  $paper->addAbstract('english',$result['des_english']);
  $paper->addAbstract('portuguese',$result['des_portuguese']);

  /* paper keywords */
  $result = mysql_query("select k.keyword_spanish spanish, k.keyword_english english
                         from keyword k, terms t
                         where t.code_keyword = k.code and
                               t.code_paper = ".$code);

  for ($i=0; $row = mysql_fetch_array($result); $i++){
    $paper->addkeyword(array('spanish' => $row['spanish'],
			     'english' => $row['english']));
  }  

  /* author(s) information but membership(s) */
  $result = mysql_query("select a.code code, a.author_name name, 
                                a.author_lastname lastname, a.author_mail mail, 
                                a.author_webpage webpage 
                         from author a, authoring at 
                         where at.code_author = a.code and at.code_paper = ".$code."
                         order by author_order");
  for ($i=0; $row = mysql_fetch_array($result); $i++){
    $author[$i] = array('order'    => $i+1,
			'code'     => $row['code'], 
			'name'     => $row['name'], 
			'lastname' => $row['lastname'], 
			'mail'     => $row['mail'], 
			'webpage'  => $row['webpage']);
  }
  
  /* author(s) membership(s) */
  for ($i=0; isset($author[$i]); $i++){
    $result = mysql_query("select i.code code, i.institution_name name, 
                                  i.institution_webpage webpage, cit.city_name city, 
                                  cou.country_name country 
                           from institution i, city cit, country cou, membership mem 
                           where cit.city_country = cou.code and 
                                 i.institution_city = cit.code and 
                                 mem.code_institution = i.code and 
                                 mem.code_person = ".$author[$i]['code']);
    for ($j=0; $row = mysql_fetch_array($result); $j++) {
      $author[$i]['institution'][$j] = array('code'	=> $row['code'], 
                                             'name'	=> $row['name'], 
					     'webpage'  => $row['webpage'], 
					     'city'	=> $row['city'], 
					     'country' 	=> $row['country']);
    }
  }

  foreach ($author as $val) {
    $paper->addAuthor($val['order'], $val['code'], $val['name'], 
		      $val['lastname'], $val['mail'], $val['webpage'], $val['institution']);
  }
  
  /* tutor(s) information but membership(s) */
  $result = mysql_query("select t.code code, t.tutor_name name, t.tutor_lastname lastname, 
                                t.tutor_mail mail, t.tutor_webpage webpage 
                                from tutor t, tutoring tu 
                         where tu.code_tutor = t.code and tu.code_paper = ".$code);
											
  for ($i=0; $row = mysql_fetch_array($result); $i++){
    $tutor[$i] = array(	'code'    => $row['code'], 
			'name'    => $row['name'], 
			'lastname'=> $row['lastname'], 
			'mail'    => $row['mail'], 
			'webpage' => $row['webpage']);
  }
  
  /* tutor(s) membership(s) */
  for ($i=0; isset($tutor[$i]); $i++){
    $result = mysql_query("select i.code code, i.institution_name name, 
                                  i.institution_webpage webpage, cit.city_name city, 
                                  cou.country_name country 
                           from institution i, city cit, country cou, membership mem 
                           where cit.city_country = cou.code and 
                                 i.institution_city = cit.code and 
                                 mem.code_institution = i.code and 
                                 mem.code_person = ".$tutor[$i]['code']);
    for ($j=0; $row = mysql_fetch_array($result); $j++) {
      $tutor[$i]['institution'][$j] = array('code'    => $row['code'], 
					    'name'    => $row['name'], 
					    'webpage' => $row['webpage'], 
					    'city'    => $row['city'], 
					    'country' => $row['country']);
    }
  }
  
  if (isset($tutor)) {
    foreach ($tutor as $val) {
      $paper->addTutor($val['code'], $val['name'], $val['lastname'], 
		       $val['mail'], $val['webpage'], $val['institution']);
    }
  }
  return $paper;
}

/**
 * Returns all the papers of the author plus its own information
 *
 * @param int $code author's code
 *
 * @return array author's fullname plus paper(s) title and code
 */
function getAuthor($code)
{
  $result = mysql_query("select author_lastname lastname, author_name name
                         from author 
                         where code = '".$code."'"); 
  $row = mysql_fetch_array($result);
  $ans = array('lastname' => $row['lastname'], 
	       'name'     => $row['name']);
  $result = mysql_query("select p.code code, p.paper_title title
                         from paper p, authoring a
                         where a.code_paper = p.code and
                               a.code_author = '".$code."'");
  for ($i=0; $row = mysql_fetch_array($result); $i++) {
    $ans['paper'][$i] = array('code'  => $row['code'],
			      'title' => $row['title']);
  }
  return $ans;
}

function getTutor($code)
{
  $result = mysql_query("select tutor_lastname lastname, tutor_name name
                         from tutor 
                         where code = '".$code."'"); 
  $row = mysql_fetch_array($result);
  $ans = array('lastname' => $row['lastname'], 
	       'name'     => $row['name']);
  $result = mysql_query("select p.code code, p.paper_title title
                         from paper p, tutoring a
                         where a.code_paper = p.code and
                               a.code_tutor = '".$code."'");
  for ($i=0; $row = mysql_fetch_array($result); $i++) {
    $ans['paper'][$i] = array('code'  => $row['code'],
			      'title' => $row['title']);
  }
  //  print_r($ans);
  return $ans;
}

/**
 * Returns all the papers that have the keyword
 *
 * @param int $code keyword's code
 *
 * @return array paper(s) title and code that have the keyword
 */
function getKeyword($code)
{
  $result = mysql_query("select keyword_spanish spanish, keyword_english english
                         from keyword 
                         where code = '".$code."'"); 
  $keyword = mysql_fetch_array($result);
  $result = mysql_query("select p.code code, p.paper_title title
                         from paper p, terms t
                         where t.code_paper = p.code and
                               t.code_keyword = '".$code."'");

  /* Iteration to get all info about the paper */
  for ($i=0; $row = mysql_fetch_array($result);$i++){
    /* Iteration to get all paper's author(s) */
    $temporal = mysql_query("select a.author_name name, a.author_lastname lastname,  
                                a.author_webpage webpage 
                         from author a, authoring at 
                         where at.code_author = a.code and at.code_paper = ".$row['code']."
                         order by at.author_order");

    for ($j=0; $tmp_row = mysql_fetch_array($temporal); $j++){
      $author[$j] = array('name'     => $tmp_row['name'], 
			  'lastname' => $tmp_row['lastname'], 			
			  'webpage'  => $tmp_row['webpage']);
    }
    /*Iteration to get all paper's tutor(s) */
    $temporal = mysql_query("select t.tutor_name name, t.tutor_lastname lastname,  
                                t.tutor_webpage webpage 
                         from tutor t, tutoring tu 
                         where tu.code_tutor = t.code and tu.code_paper = ".$row['code']);
    for ($j=0; $tmp_row = mysql_fetch_array($temporal); $j++){
      $tutor[$j] = array('name'     => $tmp_row['name'], 
			 'lastname' => $tmp_row['lastname'], 			
			 'webpage'  => $tmp_row['webpage']);


    }

    /* Here we put together all the information */
    $paper[$i] = array(	'code'   => $row['code'],
			'title'  => $row['title'],
			'course' => $row['course'],
			'author' => $author,
			'tutor'  => $tutor);
    unset($tutor);
    unset($author);
  }	
  return array('info'    => $paper,
	       'keyword' => $keyword);

}

/**
 * Returns an array of all the authors registred
 *
 * @return array 	selft explaining
 */
function authorIndex()
{
  $result = mysql_query("select code, author_lastname lastname, author_name name 
                         from author 
                         order by author_lastname");
  for ($i=0; $row = mysql_fetch_array($result);$i++){
    $author[$i] = array('code'    => $row['code'],
			'name'    => $row['name'], 
			'lastname'=> $row['lastname']);
  }	
  return $author;
}

function tutorIndex()
{
  $result = mysql_query("select code, tutor_lastname lastname, tutor_name name 
                         from tutor 
                         order by tutor_lastname");
  for ($i=0; $row = mysql_fetch_array($result);$i++){
    $tutor[$i] = array('code'    => $row['code'],
		       'name'    => $row['name'], 
		       'lastname'=> $row['lastname']);
  }
  return $tutor;
}

/**
 * Returns an array of all the papers registred
 *
 * @return array 	self explaining
 */
function paperIndex($order = 'title')
{
  $result = mysql_query("select code, paper_title title 
                         from paper 
                         order by ".$order);
  /* Iteration to get all info about paper */
  for ($i=0; $row = mysql_fetch_array($result);$i++){
    /* Iteration to get all paper's author(s) */
    $temporal = mysql_query("select a.author_name name, a.author_lastname lastname,  
                                a.author_webpage webpage 
                         from author a, authoring at 
                         where at.code_author = a.code and at.code_paper = ".$row['code']."
                         order by at.author_order");

    for ($j=0; $tmp_row = mysql_fetch_array($temporal); $j++){
      $author[$j] = array('name'     => $tmp_row['name'], 
			  'lastname' => $tmp_row['lastname'], 			
			  'webpage'  => $tmp_row['webpage']);
    }
    /*Iteration to get all paper's tutor(s) */
    $temporal = mysql_query("select t.tutor_name name, t.tutor_lastname lastname,  
                                t.tutor_webpage webpage 
                         from tutor t, tutoring tu 
                         where tu.code_tutor = t.code and tu.code_paper = ".$row['code']);
    for ($j=0; $tmp_row = mysql_fetch_array($temporal); $j++){
      $tutor[$j] = array('name'     => $tmp_row['name'], 
			 'lastname' => $tmp_row['lastname'], 			
			 'webpage'  => $tmp_row['webpage']);


    }

    /* Here we put together all the information */
    $paper[$i] = array(	'code'   => $row['code'],
			'title'  => $row['title'],
			'author' => $author,
			'tutor'  => $tutor);
    unset($tutor);
    unset($author);
  }	
  return $paper;
}

/**
 * Returns an array of all the papers registred indexed by course
 *
 * @return array 	self explaining
 */
function courseIndex()
{
  $result = mysql_query('select c.course_name course, p.code code,
                                p.paper_title title
                         from paper p, course c
                         where p.paper_course = c.code
                         order by c.course_name, p.paper_title');
  
  /* Iteration to get all info about the paper */
  for ($i=0; $row = mysql_fetch_array($result);$i++){
    /* Iteration to get all paper's author(s) */
    $temporal = mysql_query("select a.author_name name, a.author_lastname lastname,  
                                a.author_webpage webpage 
                         from author a, authoring at 
                         where at.code_author = a.code and at.code_paper = ".$row['code']."
                         order by at.author_order");

    for ($j=0; $tmp_row = mysql_fetch_array($temporal); $j++){
      $author[$j] = array('name'     => $tmp_row['name'], 
			  'lastname' => $tmp_row['lastname'], 			
			  'webpage'  => $tmp_row['webpage']);
    }
    /*Iteration to get all paper's tutor(s) */
     $temporal = mysql_query("select t.tutor_name name, t.tutor_lastname lastname,  
                                t.tutor_webpage webpage 
                         from tutor t, tutoring tu 
                         where tu.code_tutor = t.code and tu.code_paper = ".$row['code']);
    for ($j=0; $tmp_row = mysql_fetch_array($temporal); $j++){
      $tutor[$j] = array('name'     => $tmp_row['name'], 
			 'lastname' => $tmp_row['lastname'], 			
			 'webpage'  => $tmp_row['webpage']);


    }

    /* Here we put together all the information */
    $paper[$i] = array(	'code'   => $row['code'],
			'title'  => $row['title'],
			'course' => $row['course'],
			'author' => $author,
			'tutor'  => $tutor);
    unset($tutor);
    unset($author);
  }	
  return $paper;
}

function keywordIndex($language = 'spanish')
{
  $result = mysql_query("select code, keyword_".$language." keyword
                         from keyword
                         order by keyword_".$language);
  for ($i=0; $row = mysql_fetch_array($result); $i++){
    $key[$i] = array('code'    => $row['code'],
		     'keyword' => $row['keyword']);
  }
  return $key;
}	
?>