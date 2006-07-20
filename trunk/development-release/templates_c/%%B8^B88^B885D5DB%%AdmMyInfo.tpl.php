<?php /* Smarty version 2.6.11, created on 2006-04-04 23:14:01
         compiled from AdmMyInfo.tpl */ ?>
<div id="title">
<h3>Mi información</h3>
</div>

<br/> <p>Actualmente, el sistema almacena la siguiente informaci&oacute;n
sobre ti. Para cambiar alg&uacute;n dato simplemente sobreescribe la anterior
y pulsa el bot&oacute;n actualizar:</p>

<form>
<table class="description" action="admin.php" method="post">
<tr> <td class="name">Nombres:</td>      
     <td class="value"><input type="text" value="<?php echo $this->_tpl_vars['person']->getName(); ?>
" name="name" /></td> </tr>
<tr> <td class="name">Apellidos:</td>    
     <td class="value"><input type="text" value="<?php echo $this->_tpl_vars['person']->getLastname(); ?>
" name="lastname" /></td> </tr
<tr> <td class="name">Página Web:</td>   
     <td class="value"><input type="text" value="<?php echo $this->_tpl_vars['person']->getWebpage(); ?>
" name="webpage" /></td> </tr>
<tr> <td class="name">Correo Electrónico:</td> 
     <td class="value"><input type="text" value="<?php echo $this->_tpl_vars['person']->getMail(); ?>
" name="email" /></td> </tr>
</table>
<input type="submit" value="Actualizar" />
</form>