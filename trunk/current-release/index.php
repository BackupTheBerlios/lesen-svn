<?php
require_once('config.php');
require_once(SMARTY_DIR.'Smarty.class.php');
include_once('include/varfunctions.inc.php');
//require_once('include/dbfunctions.inc.php');
require_once('include/lesen_core.inc.php');
include_once('bundled-libs/DB/DB.php');

$smarty = new Smarty();
$smarty->debugging = true;
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

$db =& DB::connect($dsn, $options);
if (PEAR::isError($db)) {
  die($db->getMessage());
 }
$db->setFetchMode(DB_FETCHMODE_ASSOC);

function showPaper($code)
{
  global $smarty;

  $article = new Lesen_paper($code);
  if (is_null($article))
    die("wrong paper code");

  foreach (array("authors", "tutors") as $name) {
    foreach ($article->{"_".$name} as $val) {
      ${$name}[] = new Lesen_person((int)$val);
    }
  }

  $smarty->assign(array('paper'   => $article,
			'authors' => $authors,
			'tutors'  => $tutors));
  $smarty->assign('body',$smarty->fetch('paper.tpl'));  
}

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
    $query = $db->prepare("select paper_title title from paper where code = ?");    
  
    $result =& $db->execute($query, $value);
    if (PEAR::isError($db)) {
      die($db->getMessage());
    }
    
    $result->fetchInto($row);
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
  $query = $db->prepare('select code, paper_title title 
                         from paper 
                         order by ?');

  $result =& $db->execute($query, $order);
  if (PEAR::isError($db)) {
    die($db->getMessage());
  }

  /* Iteration to get all info about paper */  
  while ($result->fetchInto($row)) {
    /* Iteration to get all paper's author(s) */
    $query = $db->prepare("select p.person_name name, p.person_lastname lastname,  
                                  p.person_webpage webpage 
                           from person p, authoring at 
                           where at.code_author = p.code and at.code_paper = ?
                           order by at.author_type, at.author_order");

    $temporal =& $db->execute($query, $row['code']);
    if (PEAR::isError($db)) {
      die($db->getMessage());
    }

    while ($temporal->fetchInto($tmp_row)) {
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

  $query = $db->prepare("select code, person_lastname lastname, person_name name 
                         from person 
                         order by person_lastname");
  $result =& $db->execute($query, $order);
  if (PEAR::isError($db)) {
    die($db->getMessage());
  } 
  
  while ($result->fetchInto($row)) {
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

  $result = $db->prepare('select c.course_name course, p.code code,
                                 p.paper_title title
                          from paper p, course c
                          where p.paper_course = c.code
                          order by c.course_name, p.paper_title');

  $result =& $db->execute($query);
  if (PEAR::isError($db)) {
    die($db->getMessage());
  } 

  /* Iteration to get all info about the paper */
  while ($result->fetchInto($row)) {
    /* Iteration to get all paper's author(s) */
    $query = $db->prepare("select a.person_name name, a.person_lastname lastname,  
                                a.person_webpage webpage 
                           from person a, authoring at 
                           where at.code_author = a.code and at.code_paper = ?
                           order by at.author_order");

    $temporal =& $db->execute($query, $row['code']);
    if (PEAR::isError($db)) {
      die($db->getMessage());
    } 

    while ($temporal->fetchInto($tmp_row)) {
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
    $query = $db->prepare("select code, keyword_$lang keyword
                           from keyword
                           order by keyword_$lang");

    $result =& $db->execute($query);
    if (PEAR::isError($db)) {
      die($db->getMessage());
    } 
    
    while ($result->fetchInto($row)) {
      $key[] = array('code'    => $row['code'],
		     'keyword' => $row['keyword']);
    }
   
    $arr = generateIndex($key, 'keyword');
    $smarty->assign('keyword', $arr);    
    $smarty->assign('body', $smarty->fetch('keywordselkey.tpl'));
    return;
  }
  // ** show papers 
  $query = $db->prepare("select keyword_spanish spanish, keyword_english english
                         from keyword 
                         where code = ?");
  $result =& $db->execute($query, $key);
  if (PEAR::isError($db)) {
    die($db->getMessage());
  } 
  $result->fetchInto($keyword);

  $query = $db->prepare("select p.code code, p.paper_title title
                         from paper p, terms t
                         where t.code_paper = p.code and
                               t.code_keyword = ?");
  $result =& $db->execute($query, $key);
  if (PEAR::isError($db)) {
    die($db->getMessage());
  } 
  /* Iteration to get all info about the paper */
  while ($result->fetchInto($row)) {
    /* Iteration to get all paper's author(s) */
    $query = $db->prepare("select p.person_name name, p.person_lastname lastname,  
                                  p.person_webpage webpage 
                           from person p, authoring at 
                           where at.code_author = p.code and at.code_paper = ?
                           order by at.author_order");
    $tmp_result =& $db->execute($query, $row['code']);
    if (PEAR::isError($db)) {
      die($db->getMessage());
    } 
    while ($tmp_result->fetchInto($tmp_row, DB_FETCHMODE_ASSOC)) {
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

if (isset($_GET['paper']))  {
  showPaper($_GET['paper']);
 }
 else if (isset($_GET['person'])) {
   showPerson($_GET['person']);
 }


$smarty->display('page.tpl');




// /* Initialization routine */
// //$db = initdb($cfg['database']);
// $db;

// $smarty = new Smarty();
// $smarty->debugging = true;
// $smarty->assign('head_title','Proyectos - Ingeniería Informática UCSP');
// $nav = array('Proyectos' => array('Home' => 'index.php'),
// 	     'Índices'   => array('Por autor'         => 'index.php?index=author',
// 				  'Por título'        => 'index.php?index=paper',
// 				  'Por curso'         => 'index.php?index=course',
// 				  'Por palabra clave' => 'index.php?index=keyword'));

// $smarty->assign('leftnav',$nav);
// $smarty->assign('topmenu',$topmenu);
// /***************************/

// /* We want the whole paper */
// if (isset($_GET['paper'])) {
//   $paper = getPaper($_GET['paper']);
//   $paper->generateLatex();
//   $smarty->assign('paper',$paper);
//   $smarty->display('proyectos.tpl');
//  }


// /* We just want the list of papers by the author */
//  else if (isset($_GET['author'])) {
//    $author = getAuthor($_GET['author']);
//    $smarty->assign('author',$author);
//    $smarty->assign('paper',$author['paper']);
//    $smarty->display('proyectos.tpl');
//  }
// /* We just want the list of paper that share a keyword */
//  else if (isset($_GET['keyword'])) {
//    $keyword = getKeyword($_GET['keyword']);
//    $smarty->assign('paper',$keyword);
//    $smarty->display('proyectos.tpl');
//  }

// /* We need some sort of index */
//  else if (isset($_GET['index'])) {
//    switch ($_GET['index']) {
//      /* author case */
//    case 'author':
//      $arr = generateIndex(authorIndex(), 'lastname', false, true);
//      $nav['Autores'] = navigationIndex($arr);
//      $smarty->assign('leftnav', $nav);
//      $smarty->assign('type','author');
//      $smarty->assign('author', $arr);
//      $smarty->display('proyectos.tpl');
//      break;
//      /* paper case */
//    case 'paper':
//      $arr = generateIndex(paperIndex(), 'title', false, true);
//      $nav['Papers'] = navigationIndex($arr);
//      $smarty->assign('leftnav',$nav);
//      $smarty->assign('type', 'paper');
//      $smarty->assign('paper', $arr);
//      $smarty->display('proyectos.tpl');
//      break;
//      /* course case */
//    case 'course':
//      $arr = generateIndex(courseIndex(), 'course', 1);
//      $nav['Cursos'] = navigationIndex($arr);
//      $smarty->assign('leftnav',$nav);
//      $smarty->assign('type', 'course');
//      $smarty->assign('paper', $arr);
//      $smarty->display('proyectos.tpl');
//      break;
//      /* keyword case */
//    case 'keyword':
//      $smarty->assign('type', 'keyword');
//      if ($_GET['lang'])
//        {
// 	 $arr = generateIndex(keywordIndex($_GET['lang']), 'keyword');
// 	 $nav['Palabra clave'] = navigationIndex($arr);
// 	 $smarty->assign('leftnav',$nav);
// 	 $smarty->assign('keyword', $arr);
//        }
//      $smarty->display('proyectos.tpl');     
//      break;
//    default:
//      break;
//    }
//  }
// /* We just want the home page */
//  else {

// //    $dsn = array(
// // 		'phptype'  => $config['database']['dbengine'],
// // 		'username' => $config['database']['dbusername'],
// // 		'password' => $config['database']['dbpassword'],
// // 		'hostspec' => $config['database']['dbhostname'],
// // 		'database' => $config['database']['dbname']);
   
// //    //   print $dsn;
// //    $options = array(
// // 		    'debug'       => 2,
// // 		    'portability' => DB_PORTABILITY_ALL,
// // 		    );

// //    global $db;

// //    $db =& DB::connect($dsn, $options);
// //    if (PEAR::isError($db)) {
// //      die($db->getMessage());
// //    }
 

// //    $q = new Lesen_person(64,'author');
// //    $q = new Lesen_paper(1004);
// //    $smarty->assign('lesen',$q);
// //    $smarty->display('proyectos.tpl');

// //    $db->disconnect();

//    $smarty->display('page.tpl');
//    //   $smarty->display('proyectos.tpl');
//  }

// /* Close routine */
// //mysql_close($db);
/*****************/
?>
