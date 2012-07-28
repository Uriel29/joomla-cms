<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_config
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<?php /* ?><ul class="nav nav-list">
	<?php
		if ($this->userIsSuperAdmin):
	?>
	<li class="nav-header"><?php echo JText::_('COM_CONFIG_SYSTEM'); ?></li>
	<li><a href="index.php?option=com_config"><?php echo JText::_('COM_CONFIG_GLOBAL_CONFIGURATION'); ?></a></li>
	<li class="divider"></li>
	<?php
		endif;
	?>
	<li class="nav-header"><?php echo JText::_('COM_CONFIG_COMPONENT_FIELDSET_LABEL'); ?></li>
	<?php
		foreach($this->components as $component):
		$active = '';
		if ($this->currentComponent === $component):
			$active = ' class="active"';
		endif;
	?>
		<li<?php echo $active; ?>><a href="index.php?option=com_config&view=component&component=<?php echo $component; ?>"><?php echo JText::_($component); ?></a></li>
	<?php
		endforeach;
	?>
	
</ul>
<?php */ ?>
<div id="nav-header">
	<ul id="submenu" class="nav-header">
		<li><a href="#" onclick="return false;" id="site" class="active"><?php echo JText::_('JSITE'); ?></a></li>
		<li><a href="#" onclick="return false;" id="system"><?php echo JText::_('COM_CONFIG_SYSTEM'); ?></a></li>
		<li><a href="#" onclick="return false;" id="server"><?php echo JText::_('COM_CONFIG_SERVER'); ?></a></li>
		<li><a href="#" onclick="return false;" id="permissions"><?php echo JText::_('COM_CONFIG_PERMISSIONS'); ?></a></li>
		<li><a href="#" onclick="return false;" id="filters"><?php echo JText::_('COM_CONFIG_TEXT_FILTERS')?></a></li>
	</ul>
	<div class="clr"></div>
</div>
