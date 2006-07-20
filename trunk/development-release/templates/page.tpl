<?xml version="1.0" encoding="ISO-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
<head>
<title>{$head_title|default:"Ingenier&iacute;a Inform&aacute;tica - UCSP"}</title>
<meta name="author" content="Rodrigo Lazo Paz" />
<meta name="date" content="{$smarty.now|date_format:"%Y-%m-%dT%H:%M:%S-0500"}" />
<meta name="copyright" content="" />
<meta name="ROBOTS" content="NOINDEX, NOFOLLOW"/>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=ISO-8859-1"/>
<meta http-equiv="Content-Style-Type" content="text/css"/>
<link rel="stylesheet" type="text/css" href="css/style.css"/>
</head>
<body>

{** Container **}

<div id="container">

{* Menu *}
<div id="menu">
    <ul>
{foreach key=name item=url from=$topmenu}
    	<li><a href="{$url}">{$name}</a></li>
{/foreach}   
     </ul>
</div>
{* Menu *}

{* Header *}
  <div id="header">
   <img src="img/banner2.jpg" border="0" hspace="0" alt="Inform&aacute;tica" 
   title="Ingenier&iacute;a Inform&aacute;tica">
  </div>
{* Header *}

{* Status bar *}
  <div id="statusbar">
   <ul>
   <li><a href="#">Home</a></li>
   </ul>
  </div>
{* Status bar *}

{* Left nav *}
<div id="leftnav">
{foreach key=title item=item from=$leftnav}
   <h3>{$title}</h3>
    <div>
    <ul>
    {foreach key=name item=url from=$item}
    	<li><a href="{$url}">{$name}</a></li>
    {/foreach}   
    </ul>
    </div>

{/foreach}
</div>
{* Left nav *}

{* BODY *}
<div id="content">
     {$body}
</div>
{* BODY *}

{* Footer *}
 <div id="footer">
  <p>Ingenier&iacute;a Inform&aacute;tica. Copyright &copy; 2006 Rodrigo Lazo</p>
  <p>Todos los derechos reservados</p>
 </div>
{* Footer *}

</div> 
{** Container div **}

</body>
</html>
