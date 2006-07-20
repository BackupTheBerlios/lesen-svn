<?php
require_once('config.php');
require_once(SMARTY_DIR.'Smarty.class.php');
include_once('include/varfunctions.inc.php');
//require_once('include/dbfunctions.inc.php');
require_once('include/lesen_core.inc.php');
include_once('include/lesen_ui.inc.php');

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

$db =& MDB2::factory($dsn);
$db->setFetchMode(MDB2_FETCHMODE_ASSOC);

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
			'tutors'  => $tutors,
			'comments' => lesen_getComment($code)));
  $smarty->assign('body',$smarty->fetch('paper.tpl'));  
}

function addComment()
{
  if (ereg("[A-Za-z0-9]", $_POST['name']) && ereg("[A-Za-z0-9]", $_POST['body']))  {
    lesen_saveComment($_POST['name'], $_POST['email'], $_POST['url'],
		      $_POST['body'], $_GET['code'], 0);
    }
  else {
    return false;
  }
}

if (isset($_POST))  {
  addComment();
  }

if (isset($_GET['code']))  {
  showPaper($_GET['code']);
 }


$smarty->display('page.tpl');

?>
