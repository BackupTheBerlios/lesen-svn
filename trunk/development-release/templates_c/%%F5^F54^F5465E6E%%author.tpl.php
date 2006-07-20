<?php /* Smarty version 2.6.11, created on 2006-03-21 13:33:09
         compiled from author.tpl */ ?>
<div id="title">
<h3><?php echo $this->_tpl_vars['person']->getName(); ?>
 <?php echo $this->_tpl_vars['person']->getLastname(); ?>
 </h3>
</div>
<ul>
<?php $_from = $this->_tpl_vars['papers']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['title']):
 echo '<li><a href="paper.php?code=';  echo $this->_tpl_vars['key'];  echo '">';  echo $this->_tpl_vars['title'];  echo '</a>'; ?>

<?php endforeach; endif; unset($_from); ?>
</ul>