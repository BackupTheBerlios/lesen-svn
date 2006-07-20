<?php
require_once('config.php');
require_once(SMARTY_DIR.'Smarty.class.php');
include_once('include/varfunctions.inc.php');
//require_once('include/dbfunctions.inc.php');
require_once('include/lesen_core.inc.php');
include_once('include/lesen_ui.inc.php');

require_once('bundled-libs/MDB2/MDB2.php');

$smarty = new Smarty();
//$smarty->debugging = true;
//OLD STYLE
$smarty->assign('topmenu',$topmenu);

$smarty->assign('head_title','Proyectos - Ingeniería Informática UCSP');
$nav = array('Proyectos' => array('Home' => 'index.php'),
	     'Índices'   => array('Por autor'         => 'index.php?browseby=person',
				  'Por título'        => 'index.php?browseby=paper',
				  'Por curso'         => 'index.php?browseby=course',
				  'Por palabra clave' => 'index.php?browseby=keyword'));

$smarty->assign('leftnav',$nav);

///////

$dsn = array(
	     'phptype'  => $config['database']['dbengine'],
	     'username' => $config['database']['dbusername'],
	     'password' => $config['database']['dbpassword'],
	     'hostspec' => $config['database']['dbhostname'],
	     'database' => $config['database']['dbname']);
   
$options = array(
		 'debug'       => 2,
		 'portability' => DB_PORTABILITY_ALL,
		 );


$db =& MDB2::factory($dsn);
$db->setFetchMode(MDB2_FETCHMODE_ASSOC);

function showPerson($code)
{
  global $smarty, $db;

  if (!is_int($code))  {
    $code = (int)$code;
    }
  $person = new Lesen_person($code);
  if (is_null($person))
    die("wrong paper code");
  
  foreach ($person->_papers as $value) {
    $query = "select paper_title title from paper where code = " . $db->quote($value);    
    $row =& $db->queryRow($query);
    $papers[$value] = $row['title'];

  }
  $smarty->assign(array('1'      => $row,
			'papers' => $papers,
			'person' => $person));
  $smarty->assign('body', $smarty->fetch('author.tpl'));
}

function indexPaper()
{
  global $db, $smarty, $nav;

  $order = "title";
  $query = "select code, paper_title title 
            from paper 
            order by ".$db->quote($order);
  $result =& $db->queryAll($query);

  /* Iteration to get all info about paper */  
  
  foreach ($result as $row) {
    $query = "select p.person_name name, p.person_lastname lastname,  
                     p.person_webpage webpage 
              from person p, authoring at 
              where at.code_author = p.code and at.code_paper = ".$db->quote($row['code']).
             "order by at.author_type, at.author_order";
    $temporal =& $db->queryAll($query);
    foreach ($temporal as $tmp_row) {
      $author[] = array('name'     => $tmp_row['name'], 
			'lastname' => $tmp_row['lastname'], 			
			'webpage'  => $tmp_row['webpage']);
    }

    /* Here we put together all the information */
    $paper[] = array('code'   => $row['code'],
		     'title'  => $row['title'],
		     'authors'=> $author);

    unset($author);
  }

  $arr = generateIndex($paper, 'title', false, true);  
  $nav['Papers'] = navigationIndex($arr);
  $smarty->assign('leftnav',$nav);
  $smarty->assign('index', $arr);
  $smarty->assign('body', $smarty->fetch('paperindex.tpl'));

}

function indexPerson()
{
  global $db, $smarty, $nav;

  $query = "select code, person_lastname lastname, person_name name 
            from person 
            order by person_lastname";
  $result =& $db->queryAll($query);

  foreach ($result as $row) {
    $person[] = array('code'    => $row['code'],
		      'name'    => $row['name'], 
		      'lastname'=> $row['lastname']);
  }	
  
  $arr = generateIndex($person, 'lastname', false, true);
  $nav['Autores'] = navigationIndex($arr);
  $smarty->assign('leftnav', $nav);  
  $smarty->assign('index',$arr);
  $smarty->assign('body',$smarty->fetch('personindex.tpl'));
}

function indexCourse()
{
  global $db, $smarty, $nav;

  $query = "select c.course_name course, p.code code,
                   p.paper_title title
            from paper p, course c
            where p.paper_course = c.code
            order by c.course_name, p.paper_title";

  $result =& $db->queryAll($query);

  /* Iteration to get all info about the paper */
  foreach ($result as $row) {
    /* Iteration to get all paper's author(s) */
    $query = "select a.person_name name, a.person_lastname lastname,  
                     a.person_webpage webpage 
              from person a, authoring at 
              where at.code_author = a.code and at.code_paper = ".$db->quote($row['code']).
              "order by at.author_order";
    
    $temporal =& $db->queryAll($query);

    foreach ($temporal as $tmp_row) {
      $author[] = array('name'     => $tmp_row['name'], 
			'lastname' => $tmp_row['lastname'], 			
			'webpage'  => $tmp_row['webpage']);
    }

    /* Here we put together all the information */
    $paper[] = array(	'code'   => $row['code'],
			'title'  => $row['title'],
			'course' => $row['course'],
			'author' => $author,
			'tutor'  => $tutor);
    unset($author);
  }	

  $arr = generateIndex($paper, 'course', 1);
  $nav['Cursos'] = navigationIndex($arr);
  $smarty->assign('leftnav',$nav);
  $smarty->assign('index', $arr);
  $smarty->assign('body', $smarty->fetch('courseindex.tpl'));

}

function indexKeyword($lang = '', $key = '')
{
  global $smarty, $db;

  // ** select language
  if (!$lang) {
    $smarty->assign('body', $smarty->fetch('keywordsellang.tpl'));
    return;
  }
  // ** select key
  if (!$key) {
    $query = "select code, keyword_$lang keyword
              from keyword
              order by keyword_$lang";

    $result =& $db->queryAll($query);
    
    foreach ($result as $row) {
      $key[] = array('code'    => $row['code'],
		     'keyword' => $row['keyword']);
    }
   
    $arr = generateIndex($key, 'keyword');
    $smarty->assign('keyword', $arr);    
    $smarty->assign('body', $smarty->fetch('keywordselkey.tpl'));
    return;
  }
  // ** show papers 
  $query = "select keyword_spanish spanish, keyword_english english
            from keyword 
            where code = " . $db->quote($key);
  $keyword =& $db->queryRow($query);

  $query = "select p.code code, p.paper_title title
            from paper p, terms t
            where t.code_paper = p.code and
                  t.code_keyword = " . $db->quote($key);
  
  $result =& $db->queryAll($query);
  /* Iteration to get all info about the paper */
  foreach ($result as $row) {
    /* Iteration to get all paper's author(s) */
    $query = "select p.person_name name, p.person_lastname lastname,  
                     p.person_webpage webpage 
              from person p, authoring at 
              where at.code_author = p.code and at.code_paper = " . $db->quote($row['code']) .
              "order by at.author_order";
    $tmp_result =& $db->queryAll($query);
    foreach ($tmp_result as $tmp_row) {
      $author[] = array('name'     => $tmp_row['name'], 
			'lastname' => $tmp_row['lastname'], 			
			'webpage'  => $tmp_row['webpage']);
    }
 
    /* Here we put together all the information */
    $paper[] = array(	'code'   => $row['code'],
			'title'  => $row['title'],
			'authors'=> $author);
    
    unset($author);
  }	
  
  $smarty->assign(array('paper'   => $paper,
			'keyword' => $keyword));
  $smarty->assign('body', $smarty->fetch('keywordindex.tpl'));
}
  
//indexPerson();
//indexPaper();
//indexCourse();
//indexKeyword('spanish',118);

if (isset($_GET['browseby'])) {
  switch ($_GET['browseby']) {
   case 'person':
     indexPerson();
     break;
   case 'paper':
     indexPaper();
     break;
   case 'course':
     indexCourse();
     break;
   case 'keyword':
     indexKeyword($_GET['lang'], $_GET['keycode']);
     break;
   default:
     break;    
  }
 }

if (isset($_GET['person'])) {
   showPerson($_GET['person']);
 }


$smarty->display('page.tpl');

?>
