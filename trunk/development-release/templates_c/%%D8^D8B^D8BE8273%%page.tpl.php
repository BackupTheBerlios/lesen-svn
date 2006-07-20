<?php /* Smarty version 2.6.11, created on 2006-03-19 17:36:00
         compiled from page.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'page.tpl', 7, false),array('modifier', 'date_format', 'page.tpl', 9, false),)), $this); ?>
<?php echo '<?xml'; ?>
 version="1.0" encoding="ISO-8859-1"<?php echo '?>'; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
<head>
<title><?php echo ((is_array($_tmp=@$this->_tpl_vars['head_title'])) ? $this->_run_mod_handler('default', true, $_tmp, "Ingenier&iacute;a Inform&aacute;tica - UCSP") : smarty_modifier_default($_tmp, "Ingenier&iacute;a Inform&aacute;tica - UCSP")); ?>
</title>
<meta name="author" content="Rodrigo Lazo Paz" />
<meta name="date" content="<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%dT%H:%M:%S-0500") : smarty_modifier_date_format($_tmp, "%Y-%m-%dT%H:%M:%S-0500")); ?>
" />
<meta name="copyright" content="" />
<meta name="ROBOTS" content="NOINDEX, NOFOLLOW"/>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=ISO-8859-1"/>
<meta http-equiv="Content-Style-Type" content="text/css"/>
<link rel="stylesheet" type="text/css" href="css/style.css"/>
</head>
<body>


<div id="container">

<div id="menu">
    <ul>
<?php $_from = $this->_tpl_vars['topmenu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['name'] => $this->_tpl_vars['url']):
?>
    	<li><a href="<?php echo $this->_tpl_vars['url']; ?>
"><?php echo $this->_tpl_vars['name']; ?>
</a></li>
<?php endforeach; endif; unset($_from); ?>   
     </ul>
</div>

  <div id="header">
   <img src="img/banner2.jpg" border="0" hspace="0" alt="Inform&aacute;tica" 
   title="Ingenier&iacute;a Inform&aacute;tica">
  </div>

  <div id="statusbar">
   <ul>
   <li><a href="#">Home</a></li>
   </ul>
  </div>

<div id="leftnav">
<?php $_from = $this->_tpl_vars['leftnav']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['title'] => $this->_tpl_vars['item']):
?>
   <h3><?php echo $this->_tpl_vars['title']; ?>
</h3>
    <div>
    <ul>
    <?php $_from = $this->_tpl_vars['item']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['name'] => $this->_tpl_vars['url']):
?>
    	<li><a href="<?php echo $this->_tpl_vars['url']; ?>
"><?php echo $this->_tpl_vars['name']; ?>
</a></li>
    <?php endforeach; endif; unset($_from); ?>   
    </ul>
    </div>

<?php endforeach; endif; unset($_from); ?>
</div>

<div id="content">
     <?php echo $this->_tpl_vars['body']; ?>

</div>

 <div id="footer">
  <p>Ingenier&iacute;a Inform&aacute;tica. Copyright &copy; 2006 Rodrigo Lazo</p>
  <p>Todos los derechos reservados</p>
 </div>

</div> 

</body>
</html>