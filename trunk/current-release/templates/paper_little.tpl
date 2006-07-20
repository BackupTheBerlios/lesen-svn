{* Paper header for index *}
<li><a href="index.php?paper={$paper->_code}">{$paper->_title}</a></li>
<p class="index">
	{foreach item=i name=it from=$item.author}
       	{if $i.webpage}
	       	<a href="{$i.webpage}">{$i.name} {$i.lastname}</a> 
        {else}
           	{$i.name} {$i.lastname}            
       	{/if}
        {if $smarty.foreach.it.last}
            ;
        {else}
            ,
        {/if}
	{/foreach}
	{foreach item=i name=it from=$item.tutor}
       	{if $i.webpage}
	    	<a href="{$i.webpage}">{$i.name} {$i.lastname}</a>
        {else}
           	{$i.name} {$i.lastname}
        {if $smarty.foreach.it.last}
            .
        {else}
            ;
        {/if}
	{/if}
	{/foreach}
</p>
