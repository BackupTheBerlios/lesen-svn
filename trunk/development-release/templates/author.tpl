<div id="title">
<h3>{$person->getName()} {$person->getLastname()} </h3>
</div>
<ul>
{foreach item=title key=key from=$papers}
{strip}
	<li><a href="paper.php?code={$key}">{$title}</a>
{/strip}
{/foreach}
</ul>