{* AUTHOR *}
{if $type == 'author'}
   {foreach key=title from=$author item=curr_id}
      <div id="title">
      <h3 id="{$title}">{$title|upper}</h3>
      </div>
      <ul>
      	{foreach key=key item=item from=$curr_id}
        <li><a href="index.php?author={$item.code}">{$item.lastname}, {$item.name}</a></li>
        {/foreach}
      </ul>
     {/foreach}

{* PAPER*}
{elseif $type == 'paper'}
    {foreach key=title from=$paper item=curr_id}
    <div id="title">
    <h3 id="{$title}">{$title|upper}</h3>
    </div>
    <ul>
        {foreach key=key item=item from=$curr_id}
             {include file="paper.tpl"}
        {/foreach}	
    </ul>
    {/foreach}

{* COURSE *}
{elseif $type == 'course'}
    {foreach key=title from=$paper item=curr_id}
    <div id="title">
    <h3 id="{$title}">{$title}</h3>
    </div>
    <ul>
        {foreach key=key item=item from=$curr_id}
             {include file="paper.tpl"}
        {/foreach}	
    </ul>
    {/foreach}

{* KEYWORD *}
{elseif $type == 'keyword'}
    {if $smarty.get.lang}
        {foreach key=title from=$keyword item=curr_id}
        <div id="title">
        <h3 id="{$title}">{$title}</h3>
        </div>
        <ul>
        	{foreach key=key item=item from=$curr_id}
            	<li><a href="index.php?keyword={$item.code}">{$item.keyword}</a></li>
            {/foreach}
        </ul>
        {/foreach}
    {else}
        <div id="title">
        <h3>Selecciona el lenguaje</h3>
        </div>
        <ul>
        	<li><a href="{$smarty.server.REQUEST_URI|cat:"&lang=spanish"}">Español</a></li>
            <li><a href="{$smarty.server.REQUEST_URI|cat:"&lang=english"}">Inglés</a></li>
        </ul>
        {/if}
{/if}
