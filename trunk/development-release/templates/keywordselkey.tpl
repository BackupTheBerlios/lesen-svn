        {foreach key=title from=$keyword item=curr_id}
        <div id="title">
        <h3 id="{$title}">{$title}</h3>
        </div>
        <ul>
        	{foreach key=key item=item from=$curr_id}
            	<li><a href="{$smarty.server.REQUEST_URI|cat:"&keycode=`$item.code`"}">{$item.keyword}</a></li>
            {/foreach}
        </ul>
        {/foreach}