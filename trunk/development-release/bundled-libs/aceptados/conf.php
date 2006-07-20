<?php

require_once dirname(__FILE__) . '/bundled-libs/MDB2/MDB2.php';
require_once dirname(__FILE__) . '/bundled-libs/LiveUser/LiveUser.php';
require_once 'config.php'; 

$dsn = array(
	     'phptype'  => $config['database']['dbengine'],
	     'username' => $config['database']['dbusername'],
	     'password' => $config['database']['dbpassword'],
	     'hostspec' => $config['database']['dbhostname'],
	     'database' => $config['database']['dbname']);
   
$db =& MDB2::connect($dsn);
if (PEAR::isError($db))
  {
  echo $db->getMessage() . ' ' . $db->getUserInfo();
  }
 
$db->setFetchMode(MDB2_FETCHMODE_ASSOC);
 
$conf =
    array(
        'debug' => true,
        'session'  => array(
            'name'     => 'PHPSESSION',           // liveuser session name
            'varname'  => 'ludata'                // liveuser session var name
        ),
        'login' => array(
            'force'    => false                   // should the user be forced to login
        ),
        'logout' => array(
            'destroy'  => true                    // whether to destroy the session on logout
        ),
        'authContainers' => array(
            array(
                'type'          => 'MDB2',        // auth container name
                'expireTime'    => 3600,          // max lifetime of a session in seconds
                'idleTime'      => 1800,          // max time between 2 requests
                'allowDuplicateHandles' => 0,
                'allowEmptyPasswords'   => 0,     // 0=false, 1=true
                'passwordEncryptionMode'=> 'MD5',
                'storage' => array(
                    'dsn' => $dsn,
                    'alias' => array(             // contains any additional
                                                  // or non-default field alias
                        'lastlogin' => 'last_login',
                        'is_active' => 'is_active',
                        'owner_user_id' => 'owner_user_id',
                        'owner_group_id' => 'owner_group_id',
                        'email' => 'email'
                    ),
                    'fields' => array(            // contains any additional
                                                  // or non-default field types
                        'lastlogin' => 'timestamp',
                        'is_active' => 'boolean',
                        'owner_user_id' => 'integer',
                        'owner_group_id' => 'integer',
                        'email' => 'text'
                    ),
                    'tables' => array(            // contains additional tables
                                                  // or fields in existing tables
                        'users' => array(
                            'fields' => array(
                                'lastlogin' => false,
                                'is_active' => false,
                                'owner_user_id' => false,
                                'owner_group_id' => false,
                                'email' => false
                            )
                        )
                    )
                )
            )
        )
    );
 
PEAR::setErrorHandling(PEAR_ERROR_RETURN);
 
$LU = LiveUser::singleton($conf);
 
if (!$LU->init()) {
    var_dump($LU->getErrors());
    die();
}
$handle = (array_key_exists('handle', $_REQUEST)) ? $_REQUEST['handle'] : null;
$passwd = (array_key_exists('passwd', $_REQUEST)) ? $_REQUEST['passwd'] : null;
$logout = (array_key_exists('logout', $_REQUEST)) ? $_REQUEST['logout'] : false;
if ($logout)
  {
//  $LU->logout(true);
  $LU->logout(false);                       // does not delete the RememberMe cookie
  }
elseif(!$LU->isLoggedIn() || ($handle && $LU->getProperty('handle') != $handle))
  {
  if (!$handle)
    {
    $LU->login(null, null, true);
    }
  else
    {
    $LU->login($handle, $passwd, false);
    }
  }
 
?>