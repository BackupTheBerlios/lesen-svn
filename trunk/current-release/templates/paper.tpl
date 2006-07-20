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

