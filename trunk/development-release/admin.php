<?php
require_once('config.php');
require_once(SMARTY_DIR.'Smarty.class.php');
include_once('include/varfunctions.inc.php');
//require_once('include/dbfunctions.inc.php');
require_once('include/lesen_core.inc.php');
include_once('include/lesen_ui.inc.php');

require_once('bundled-libs/MDB2/MDB2.php');

$smarty = new Smarty();
$smarty->debugging = true;
//OLD STYLE
$smarty->assign('topmenu',$topmenu);

$smarty->assign('head_title','Proyectos - Ingeniería Informática UCSP');
$nav = array('Administraci&oacute;n' => array('Home'                  => 'admin.php',
					      'Mi informaci&oacute;n' => 'admin.php?option=myinfo',
					      'Mis trabajos'          => 'admin.php?option=mypapers',
					      'Agregar trabajo'          => 'admin.php?option=newpaper'));

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

function welcome($code)
{
  global $smarty, $db;
  $person = new Lesen_person($code);
  $smarty->assign('person', $person);
  $smarty->assign('body', $smarty->fetch('AdmWelcome.tpl'));
}

//falta lo de las instituciones
function personalInfo($code)
{
  global $smarty;
  $person = new Lesen_person($code);
  $smarty->assign('person', $person);
  $smarty->assign('body', $smarty->fetch('AdmMyInfo.tpl'));
}

function updPersonalInfo($code)
{
  global $smarty, $db;
  $person = new Lesen_person($code);
  if (isset($_POST['name']))
    {
      $param['name'] = $_POST['name'];
    }
  if (isset($_POST['lastname']))
    {
      $param['lastname'] = $_POST['lastname'];
    }
  if (isset($_POST['email']))
    {
      $param['email'] = $_POST['email'];
    }
  if (isset($_POST['webpage']))
    {
      $param['webpage'] = $_POST['webpage'];
    }
  $person->updPersonalInfo($param);
}

function papers($code)
{
  global $smarty, $db;
  $query = 'select code_paper from authoring where code_author = ' . $db->quote($code);
  $result =& $db->queryAll($query);
  foreach ($result as $val) {
    $paper = new Lesen_paper($val['code_paper']);
  }
  $smarty->assign('body', $smarty->fetch('AdmMyPapers.tpl'));
}

if (isset($_GET['option']))
  {
    switch ($_GET['option']) {
    case 'myinfo':
      personalInfo(1);
      break;
    case 'mypapers':
      papers(1);
      break;
    case 'newpaper':
      addPaper(1);
      break;
    default:
      break;
    }
  }
else
  welcome('1');


$smarty->display('page.tpl');

?>
