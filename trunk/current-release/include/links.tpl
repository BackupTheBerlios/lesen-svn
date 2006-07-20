{foreach key=header item=item from=$content}
   <h3>{$header}</h3>
    <div>
    <ul>
    {foreach key=name item=url from=$item}
    	<li><a href="{$url}">{$name}</a></li>
    {/foreach}   
    </ul>
    </div>

{/foreach}