<?php /* Smarty version 2.6.11, created on 2006-03-21 18:56:51
         compiled from paper.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'mailto', 'paper.tpl', 10, false),array('function', 'cycle', 'paper.tpl', 77, false),array('modifier', 'date_format', 'paper.tpl', 79, false),)), $this); ?>
<div id="title">
<h3><?php echo $this->_tpl_vars['paper']->getTitle(); ?>
</h3>
</div>
<h4>Autores:</h4>
<ul>
<?php $_from = $this->_tpl_vars['authors']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['it']):
 echo '<li>';  echo $this->_tpl_vars['it']->getName();  echo ' ';  echo $this->_tpl_vars['it']->getLastname();  echo ' (';  echo smarty_function_mailto(array('address' => $this->_tpl_vars['it']->getMail(),'encode' => 'javascript'), $this); echo ')</li>'; ?>

<?php endforeach; endif; unset($_from); ?>
</ul>
<h4>Asesores:</h4>
<ul>
<?php $_from = $this->_tpl_vars['tutors']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['it']):
 echo '<li>';  echo $this->_tpl_vars['it']->getName();  echo ' ';  echo $this->_tpl_vars['it']->getLastname();  echo ' (';  echo smarty_function_mailto(array('address' => $this->_tpl_vars['it']->getMail(),'encode' => 'javascript'), $this); echo ')</li>'; ?>

<?php endforeach; endif; unset($_from); ?>
</ul>

<h4>Palabras clave</h4>
<p class="keyword">
<?php $_from = $this->_tpl_vars['paper']->getKeywords(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['for'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['for']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['it']):
        $this->_foreach['for']['iteration']++;
?>
	 <?php if (($this->_foreach['for']['iteration'] == $this->_foreach['for']['total'])): ?>
	 <?php echo $this->_tpl_vars['it']['spanish']; ?>
.
	 <?php else: ?>
	 <?php echo $this->_tpl_vars['it']['spanish']; ?>
,
	 <?php endif;  endforeach; endif; unset($_from); ?>
</p>

<!--
<h4>Keywords</h4>
<p class="keyword">
<?php unset($this->_sections['it']);
$this->_sections['it']['name'] = 'it';
$this->_sections['it']['loop'] = is_array($_loop=$this->_tpl_vars['paper']['keywords']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['it']['show'] = true;
$this->_sections['it']['max'] = $this->_sections['it']['loop'];
$this->_sections['it']['step'] = 1;
$this->_sections['it']['start'] = $this->_sections['it']['step'] > 0 ? 0 : $this->_sections['it']['loop']-1;
if ($this->_sections['it']['show']) {
    $this->_sections['it']['total'] = $this->_sections['it']['loop'];
    if ($this->_sections['it']['total'] == 0)
        $this->_sections['it']['show'] = false;
} else
    $this->_sections['it']['total'] = 0;
if ($this->_sections['it']['show']):

            for ($this->_sections['it']['index'] = $this->_sections['it']['start'], $this->_sections['it']['iteration'] = 1;
                 $this->_sections['it']['iteration'] <= $this->_sections['it']['total'];
                 $this->_sections['it']['index'] += $this->_sections['it']['step'], $this->_sections['it']['iteration']++):
$this->_sections['it']['rownum'] = $this->_sections['it']['iteration'];
$this->_sections['it']['index_prev'] = $this->_sections['it']['index'] - $this->_sections['it']['step'];
$this->_sections['it']['index_next'] = $this->_sections['it']['index'] + $this->_sections['it']['step'];
$this->_sections['it']['first']      = ($this->_sections['it']['iteration'] == 1);
$this->_sections['it']['last']       = ($this->_sections['it']['iteration'] == $this->_sections['it']['total']);
?>
	 <?php echo $this->_tpl_vars['paper']['keywords'][$this->_sections['it']['index']]['english']; ?>
, 
<?php endfor; endif; ?>
</p>
-->

<?php $this->assign('des', $this->_tpl_vars['paper']->getAbstracts()); ?>

<?php if ($this->_tpl_vars['des']['english']): ?>
<h4>Abstract</h4>
<p><?php echo $this->_tpl_vars['des']['english']; ?>
 </p>
<?php endif; ?>

<?php if ($this->_tpl_vars['des']['spanish']): ?>
<h4>Resumen</h4>
<p> <?php echo $this->_tpl_vars['des']['spanish']; ?>
 </p>
<?php endif; ?>

<?php if ($this->_tpl_vars['des']['portuguese']): ?>
<h4>Resumo</h4>
<p> <?php echo $this->_tpl_vars['des']['portuguese']; ?>
 </p>
<?php endif; ?>

<br />

<div id="paperOptions">
<a href="#newcomment">Agregar un comentario</a>
</div>
<br />

<?php if (count ( $this->_tpl_vars['comments'] ) > 0): ?>
<div id="title">
<h3>Comentarios</h3>
</div>


<?php $_from = $this->_tpl_vars['comments']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['it']):
?>
<div id="<?php echo smarty_function_cycle(array('values' => "comment1,comment2"), $this);?>
">
  <strong class="commentName"><?php echo $this->_tpl_vars['it']['author']; ?>
</strong> comenta: 
  <p class="date"><?php echo ((is_array($_tmp=$this->_tpl_vars['it']['timestamp'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%B %e, %Y %T") : smarty_modifier_date_format($_tmp, "%B %e, %Y %T")); ?>
</p>
  <div id="commentBody"><?php echo $this->_tpl_vars['it']['body']; ?>
</div>
</div>
<hr />
<?php endforeach; endif; unset($_from); ?>

<?php endif; ?>
<div id="title">
<h3 id="newcomment">Añadir un nuevo comentario</h3>
</div>
<br />
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>
" method="post">
<input type="text" name="name" maxlength=40 size=25> <label for="name"> Nombre (requerido)</label><br /><br />
<input type="text" maxlength=60 size=25 /> <label for="email"> E-mail (no será mostrado)</label><br /><br />
<input type="text" maxlength=60 size=25 /> <label for="url"> Homepage </label><br /><br />
<textarea name="body" rows=10 cols=60 /> </textarea>
<br/>
<input type="submit" value="Agregar" />
</form>

<p class="comment">Para hacer alguna parte del texto
<strong>negrita</strong> enci&eacute;rrala entre [b][/b], si deseas que
est&eacute; <u>subrayada</u> enci&eacute;rrala entre [u][/u] o si prefieres
en <i>it&aacute;lica</i> con [i][/i].</p>

<p class="comment">El texto de tu comentario tambi&eacute;n puede contener cualquier otro
c&oacute;digo BB, para m&aacute;s posibilidades visita <a
href="http://www.phpbb.com/phpBB/faq.php?mode=bbcode#0">esta
p&aacute;gina</a>.</p>