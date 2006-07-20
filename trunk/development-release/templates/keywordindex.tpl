<div id="title">
<h3>{$keyword.spanish} / {$keyword.english} </h3>
</div>
<ul>
{foreach item=paper from=$paper}
<li><a href="paper.php?code={$paper.code}">{$paper.title}</a></li>
<p class="index">
	{foreach item=i name=it from=$paper.authors}
       	{if $i.webpage}
	       	<a href="{$i.webpage}">{$i.name} {$i.lastname}</a> 
        {else}
           	{$i.name} {$i.lastname}            
       	{/if}
        {if $smarty.foreach.it.last}
            .
        {else}
            ,
        {/if}
	{/foreach}
</p>
{/foreach}
</ul>
