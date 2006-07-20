<?php /* Smarty version 2.6.11, created on 2006-03-29 17:26:10
         compiled from keywordselkey.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'cat', 'keywordselkey.tpl', 7, false),)), $this); ?>
        <?php $_from = $this->_tpl_vars['keyword']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['title'] => $this->_tpl_vars['curr_id']):
?>
        <div id="title">
        <h3 id="<?php echo $this->_tpl_vars['title']; ?>
"><?php echo $this->_tpl_vars['title']; ?>
</h3>
        </div>
        <ul>
        	<?php $_from = $this->_tpl_vars['curr_id']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
            	<li><a href="<?php echo ((is_array($_tmp=$_SERVER['REQUEST_URI'])) ? $this->_run_mod_handler('cat', true, $_tmp, "&keycode=".($this->_tpl_vars['item']['code'])) : smarty_modifier_cat($_tmp, "&keycode=".($this->_tpl_vars['item']['code']))); ?>
"><?php echo $this->_tpl_vars['item']['keyword']; ?>
</a></li>
            <?php endforeach; endif; unset($_from); ?>
        </ul>
        <?php endforeach; endif; unset($_from); ?>