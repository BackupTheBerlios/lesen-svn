<?php /* Smarty version 2.6.11, created on 2006-03-19 21:04:53
         compiled from personindex.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'personindex.tpl', 3, false),)), $this); ?>
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
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
       <li><a href="index.php?person=<?php echo $this->_tpl_vars['item']['code']; ?>
"><?php echo $this->_tpl_vars['item']['lastname']; ?>
, <?php echo $this->_tpl_vars['item']['name']; ?>
</a></li>
       <?php endforeach; endif; unset($_from); ?>
   </ul>
<?php endforeach; endif; unset($_from); ?>