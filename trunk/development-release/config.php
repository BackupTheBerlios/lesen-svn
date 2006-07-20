<?php
# Database configuration
$config['database']['dbengine']='mysql';
$config['database']['dbhostname']='localhost';
$config['database']['dbname']='proyectos';
$config['database']['dbusername']='rodrigo';
$config['database']['dbpassword']='mailman';


# Path configuration for Smarty
define('SMARTY_DIR', '/home/rodrigo/develop/lesen/development-release/bundled-libs/Smarty/libs/');

# Top menu definition
$topmenu = array('Universidad San Pablo' => "http://www.ucsp.edu.pe",
		 'Proyectos'             => "#",
		 'Mail'                  => "http://inf.ucsp.edu.pe/horde");

# Left Navigation Bar definition
$leftnav = array('General'       => array('Home'                => "index.php",
					  'Perfil del Graduado' => "#",
					  'Plan de estudios'    => "#",
					  'Grados y títulos'    => "#",
					  'Infraestructura'     => "#",
					  'Autoridades'         => "#"),
		 'Investigación' => array('Gidis'         => "#",
					  'Proyectos'     => "proyectos.php",
					  'Publicaciones' => "#"),
		 'Personas'      => array('Autoridades' => "#",
					  'Alumnos'     => "#"));

?>