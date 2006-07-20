<?php /* Smarty version 2.6.11, created on 2006-03-21 13:33:19
         compiled from paperindex.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'paperindex.tpl', 4, false),)), $this); ?>
<?php $_from = $this->_tpl_vars['index']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['title'] => $this->_tpl_vars['curr_id']):
?>
<div id="title">
<h3 id="<?php echo $this->_tpl_vars['title']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['title'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</h3>
</div>
<ul>
<?php $_from = $this->_tpl_vars['curr_id']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['paper']):
?>
<li><a href="paper.php?code=<?php echo $this->_tpl_vars['paper']['code']; ?>
"><?php echo $this->_tpl_vars['paper']['title']; ?>
</a></li>
<p class="index">
	<?php $_from = $this->_tpl_vars['paper']['authors']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['it'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['it']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['i']):
        $this->_foreach['it']['iteration']++;
?>
       	<?php if ($this->_tpl_vars['i']['webpage']): ?>
	       	<a href="<?php echo $this->_tpl_vars['i']['webpage']; ?>
"><?php echo $this->_tpl_vars['i']['name']; ?>
 <?php echo $this->_tpl_vars['i']['lastname']; ?>
</a> 
        <?php else: ?>
           	<?php echo $this->_tpl_vars['i']['name']; ?>
 <?php echo $this->_tpl_vars['i']['lastname']; ?>
            
       	<?php endif; ?>
        <?php if (($this->_foreach['it']['iteration'] == $this->_foreach['it']['total'])): ?>
            .
        <?php else: ?>
            ,
        <?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>
</p>
<?php endforeach; endif; unset($_from); ?>
</ul>
<?php endforeach; endif; unset($_from); ?>
