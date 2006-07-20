<?php /* Smarty version 2.6.11, created on 2006-03-29 17:02:08
         compiled from keywordsellang.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'cat', 'keywordsellang.tpl', 5, false),)), $this); ?>
        <div id="title">
        <h3>Selecciona el lenguaje</h3>
        </div>
        <ul>
            <li><a href="<?php echo ((is_array($_tmp=$_SERVER['REQUEST_URI'])) ? $this->_run_mod_handler('cat', true, $_tmp, "&lang=spanish") : smarty_modifier_cat($_tmp, "&lang=spanish")); ?>
">Español</a></li>
            <li><a href="<?php echo ((is_array($_tmp=$_SERVER['REQUEST_URI'])) ? $this->_run_mod_handler('cat', true, $_tmp, "&lang=english") : smarty_modifier_cat($_tmp, "&lang=english")); ?>
">Inglés</a></li>
        </ul>