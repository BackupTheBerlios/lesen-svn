<div id="title">
<h3>Mi informaci�n</h3>
</div>

<br/> <p>Actualmente, el sistema almacena la siguiente informaci&oacute;n
sobre ti. Para cambiar alg&uacute;n dato simplemente sobreescribe la anterior
y pulsa el bot&oacute;n actualizar:</p>

<form>
<table class="description" action="admin.php" method="post">
<tr> <td class="name">Nombres:</td>      
     <td class="value"><input type="text" value="{$person->getName()}" name="name" /></td> </tr>
<tr> <td class="name">Apellidos:</td>    
     <td class="value"><input type="text" value="{$person->getLastname()}" name="lastname" /></td> </tr
<tr> <td class="name">P�gina Web:</td>   
     <td class="value"><input type="text" value="{$person->getWebpage()}" name="webpage" /></td> </tr>
<tr> <td class="name">Correo Electr�nico:</td> 
     <td class="value"><input type="text" value="{$person->getMail()}" name="email" /></td> </tr>
</table>
<input type="submit" value="Actualizar" />
</form>