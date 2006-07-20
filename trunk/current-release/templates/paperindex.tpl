{* Paper header for index *}
{foreach key=title item=curr_id from=$index}
<div id="title">
<h3 id="{$title}">{$title|upper}</h3>
</div>
<ul>
{foreach item=paper from=$curr_id}
<li><a href="index.php?paper={$paper.code}">{$paper.title}</a></li>
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
{/foreach}

