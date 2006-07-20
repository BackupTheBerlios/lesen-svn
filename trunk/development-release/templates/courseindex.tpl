    {foreach key=title from=$index item=curr_id}
    <div id="title">
    <h3 id="{$title}">{$title}</h3>
    </div>
    <ul>
        {foreach key=key item=paper from=$curr_id}
          <li><a href="paper.php?code={$paper.code}">{$paper.title}</a></li>
          <p class="index">
	  {foreach item=i name=it from=$paper.author}
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
