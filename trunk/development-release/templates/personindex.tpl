{foreach key=title from=$index item=curr_id}
   <div id="title">
   <h3 id="{$title}">{$title|upper}</h3>
   </div>
   <ul>
      {foreach key=key item=item from=$curr_id}
       <li><a href="index.php?person={$item.code}">{$item.lastname}, {$item.name}</a></li>
       {/foreach}
   </ul>
{/foreach}