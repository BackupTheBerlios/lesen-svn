<div id="title">
<h3>{$paper.keyword.spanish} / {$paper.keyword.english} </h3>
</div>
<ul>
{foreach item=item from=$paper.info}
{strip}
    {include file="paper.tpl"}
{/strip}
{/foreach}
</ul>
