<?php
require_once "bundled-libs/Auth/Auth.php";                                
require_once "bundled-libs/PEAR.php";
                                                          
function loginFunction()                                
{                                            
  /*                                         
   * Change the HTML output so that it fits to your                  
   * application.                                   
   */                                         
  echo "<form method=\"post\" action=\"test.php\">";
  echo "<input type=\"text\" name=\"username\"> <br />";                   
  echo "<input type=\"password\" name=\"password\"> <br />";                 
  echo "<input type=\"submit\">";                           
  echo "</form>";                                   
}                                            
                                                          
if (isset($_GET['login']) && $_GET['login'] == 1) {
     $optional = true;
} else {
     $optional = false;
}                                          
$dsn = array(
	     'phptype'  => $config['database']['dbengine'],
	     'username' => $config['database']['dbusername'],
	     'password' => $config['database']['dbpassword'],
	     'hostspec' => $config['database']['dbhostname'],
	     'database' => $config['database']['dbname']);

$params = array(
                "dsn" => $dsn,
                "table" => "person",
                "usernamecol" => "username",
                "passwordcol" => "password"
		);

$a = new Auth("MDB2",$params, "loginFunction" , $optional);
$a->start();                                      

echo "Everybody can see this text!<br />";                                              

if (!isset($_GET['login'])) {                              
  echo "<a href=\"test.php?login=1\">Click here to log in</a>\n";          
 }                                            

if ($a->getAuth()) {                                  
  echo "One can only see this if he is logged in!";                 
 }     

?>