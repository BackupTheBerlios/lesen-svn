{* title *}
<div id="title">
<h3>{$paper->getTitle()}</h3>
</div>
{* Name of the author(s) and tutor(s) *}
<h4>Autores:</h4>
<ul>
{foreach item=it from=$authors}
{strip}
	<li>{$it->getName()} {$it->getLastname()} ({mailto address=$it->getMail() encode="javascript"})</li>
{/strip}
{/foreach}
</ul>
<h4>Asesores:</h4>
<ul>
{foreach item=it from=$tutors}
{strip}
	<li>{$it->getName()} {$it->getLastname()} ({mailto address=$it->getMail() encode="javascript"})</li>
{/strip}
{/foreach}
</ul>

{* All the keywords *}
<h4>Palabras clave</h4>
<p class="keyword">
{foreach name=for item=it from=$paper->getKeywords()}
	 {if $smarty.foreach.for.last}
	 {$it.spanish}.
	 {else}
	 {$it.spanish},
	 {/if}
{/foreach}
</p>

<!--
<h4>Keywords</h4>
<p class="keyword">
{section name=it loop=$paper.keywords}
	 {$paper.keywords[it].english}, 
{/section}
</p>
-->

{* Abstracts in the three languages *}
{assign var="des" value=$paper->getAbstracts()}

{if $des.english}
<h4>Abstract</h4>
<p>{$des.english} </p>
{/if}

{if $des.spanish}
<h4>Resumen</h4>
<p> {$des.spanish} </p>
{/if}

{if $des.portuguese}
<h4>Resumo</h4>
<p> {$des.portuguese} </p>
{/if}

<br />

<div id="paperOptions">
<a href="#newcomment">Agregar un comentario</a>
</div>
<br />

{* Comments *}
{if count($comments) > 0}
<div id="title">
<h3>Comentarios</h3>
</div>


{foreach item=it from=$comments}
<div id="{cycle values="comment1,comment2"}">
  <strong class="commentName">{$it.author}</strong> comenta: 
  <p class="date">{$it.timestamp|date_format:"%B %e, %Y %T"}</p>
  <div id="commentBody">{$it.body}</div>
</div>
<hr />
{/foreach}

{/if}
{* New comment *}
<div id="title">
<h3 id="newcomment">Añadir un nuevo comentario</h3>
</div>
<br />
<form action="{$smarty.server.REQUEST_URI}" method="post">
<input type="text" name="name" maxlength=40 size=25> <label for="name"> Nombre (requerido)</label><br /><br />
<input type="text" maxlength=60 size=25 /> <label for="email"> E-mail (no será mostrado)</label><br /><br />
<input type="text" maxlength=60 size=25 /> <label for="url"> Homepage </label><br /><br />
<textarea name="body" rows=10 cols=60 /> </textarea>
<br/>
<input type="submit" value="Agregar" />
</form>

<p class="comment">Para hacer alguna parte del texto
<strong>negrita</strong> enci&eacute;rrala entre [b][/b], si deseas que
est&eacute; <u>subrayada</u> enci&eacute;rrala entre [u][/u] o si prefieres
en <i>it&aacute;lica</i> con [i][/i].</p>

<p class="comment">El texto de tu comentario tambi&eacute;n puede contener cualquier otro
c&oacute;digo BB, para m&aacute;s posibilidades visita <a
href="http://www.phpbb.com/phpBB/faq.php?mode=bbcode#0">esta
p&aacute;gina</a>.</p>