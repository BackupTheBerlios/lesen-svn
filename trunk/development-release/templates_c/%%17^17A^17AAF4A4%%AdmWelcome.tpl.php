<?php /* Smarty version 2.6.11, created on 2006-04-01 19:02:21
         compiled from AdmWelcome.tpl */ ?>
<div id="title">
<h3>Suite de Administraci�n</h3>
</div>

<p>Bienvenido <strong><?php echo $this->_tpl_vars['person']->getName(); ?>
</strong> ,</p>
<br />
<p>Esta es la Suite de Administraci�n del sistema. Desde aqu�
podr�s:</p>

<ul>
<li><a href="admin.php?option=myinfo">Ver y modificar tu informaci�n personal.</a></li>
<li><a href="admin.php?option=mypapers">Ver y modificar tus trabajos.</a></li>
<li><a href="admin.php?option=newpaper">Agregar nuevos trabajos.</a></li>

</ul>

</table>