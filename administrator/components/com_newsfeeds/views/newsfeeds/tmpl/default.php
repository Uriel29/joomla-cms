<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_newsfeeds
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$archived	= $this->state->get('filter.published') == 2 ? true : false;
$trashed	= $this->state->get('filter.published') == -2 ? true : false;
$canOrder	= $user->authorise('core.edit.state', 'com_newsfeeds.category');
$saveOrder	= $listOrder == 'a.ordering';
if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_newsfeeds&task=newsfeeds.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'articleList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$sortFields = $this->getSortFields();
?>
<script type="text/javascript">
	Joomla.orderTable = function() {
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>') {
			dirn = 'asc';
		} else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_newsfeeds&view=newsfeeds'); ?>" method="post" name="adminForm" id="adminForm">
	<div class="row-fluid">
		<!-- Begin Sidebar -->
		<div id="sidebar" class="span2">
			<div class="sidebar-nav">
				<?php
					// Display the submenu position modules
					$this->modules = JModuleHelper::getModules('submenu');
					foreach ($this->modules as $module) {
						$output = JModuleHelper::renderModule($module);
						$params = new JRegistry;
						$params->loadString($module->params);
						echo $output;
					}
				?>
				<hr />
				<div class="filter-select hidden-phone">
					<h4 class="page-header"><?php echo JText::_('JSEARCH_FILTER_LABEL');?></h4>
					<label for="filter_published" class="element-invisible"><?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC');?></label>
					<select name="filter_published" id="filter_published" class="span12 small" onchange="this.form.submit()">
						<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
						<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true);?>
					</select>
					<hr class="hr-condensed" />
					<label for="filter_category_id" class="element-invisible"><?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC');?></label>
					<select name="filter_category_id" id="filter_category_id" class="span12 small" onchange="this.form.submit()">
						<option value=""><?php echo JText::_('JOPTION_SELECT_CATEGORY');?></option>
						<?php echo JHtml::_('select.options', JHtml::_('category.options', 'com_newsfeeds'), 'value', 'text', $this->state->get('filter.category_id'));?>
					</select>
					<hr class="hr-condensed" />
					<label for="filter_access" class="element-invisible"><?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC');?></label>
			        <select name="filter_access" id="filter_access" class="span12 small" onchange="this.form.submit()">
						<option value=""><?php echo JText::_('JOPTION_SELECT_ACCESS');?></option>
						<?php echo JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access'));?>
					</select>
					<hr class="hr-condensed" />
					<label for="filter_language" class="element-invisible"><?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC');?></label>
					<select name="filter_language" id="filter_language" class="span12 small" onchange="this.form.submit()">
						<option value=""><?php echo JText::_('JOPTION_SELECT_LANGUAGE');?></option>
						<?php echo JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'));?>
					</select>
				</div>
			</div>
		</div>
		<!-- End Sidebar -->
		<!-- Begin Content -->
		<div class="span10">
			<div id="filter-bar" class="btn-toolbar">
				<div class="filter-search btn-group pull-left">
					<label for="filter_search" class="element-invisible"><?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC');?></label>
					<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('COM_NEWSFEEDS_SEARCH_IN_TITLE'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_NEWSFEEDS_SEARCH_IN_TITLE'); ?>" />
				</div>
				<div class="btn-group pull-left hidden-phone">
					<button class="btn" rel="tooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
					<button class="btn" rel="tooltip" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
				</div>
				<div class="btn-group pull-right hidden-phone">
					<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
					<?php echo $this->pagination->getLimitBox(); ?>
				</div>
				<div class="btn-group pull-right hidden-phone">
					<label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC');?></label>
					<select name="directionTable" id="directionTable" class="input-small" onchange="Joomla.orderTable()">
						<option value=""><?php echo JText::_('JFIELD_ORDERING_DESC');?></option>
						<option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING');?></option>
						<option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING');?></option>
					</select>
				</div>
				<div class="btn-group pull-right">
					<label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY');?></label>
					<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
						<option value=""><?php echo JText::_('JGLOBAL_SORT_BY');?></option>
						<?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder);?>
					</select>
				</div>
			</div>
			<div class="clearfix"> </div>
			<table class="table table-striped" id="articleList">
				<thead>
					<tr>
						<th width="1%" class="center hidden-phone" nowrap="nowrap">
							<i class="icon-menu-2 hasTip" title="<?php echo JText::_('JGRID_HEADING_ORDERING'); ?>"></i>
						</th>
						<th width="1%" class="hidden-phone">
							<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
						</th>
						<th width="5%" class="center">
							<?php echo JText::_('JSTATUS'); ?>
						</th>
						<th class="title">
							<?php echo JText::_('JGLOBAL_TITLE'); ?>
						</th>
						<th width="5%" class="hidden-phone">
							<?php echo JText::_('JGRID_HEADING_ACCESS'); ?>
						</th>
						<th width="10%" class="hidden-phone">
							<?php echo JText::_('COM_NEWSFEEDS_NUM_ARTICLES_HEADING'); ?>
						</th>
						<th width="5%" class="hidden-phone">
							<?php echo JText::_('COM_NEWSFEEDS_CACHE_TIME_HEADING'); ?>
						</th>
						<th width="5%" class="hidden-phone">
							<?php echo JText::_('JGRID_HEADING_LANGUAGE'); ?>
						</th>
						<th width="1%" class="hidden-phone">
							<?php echo JText::_('JGRID_HEADING_ID'); ?>
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="11">
							<?php echo $this->pagination->getListFooter(); ?>
						</td>
					</tr>
				</tfoot>
				<tbody>
				<?php foreach ($this->items as $i => $item) :
					$ordering	= ($listOrder == 'a.ordering');
					$canCreate	= $user->authorise('core.create',		'com_newsfeeds.category.'.$item->catid);
					$canEdit	= $user->authorise('core.edit',			'com_newsfeeds.category.'.$item->catid);
					$canCheckin	= $user->authorise('core.manage',		'com_checkin') || $item->checked_out==$user->get('id') || $item->checked_out==0;
					$canChange	= $user->authorise('core.edit.state',	'com_newsfeeds.category.'.$item->catid) && $canCheckin;
					?>
					<tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo $item->catid?>">
						<td class="order nowrap center hidden-phone">
							<?php if ($canChange) :
								$disableClassName = '';
								$disabledLabel	  = '';
								if (!$saveOrder) :
									$disabledLabel    = JText::_('JORDERINGDISABLED');
									$disableClassName = 'inactive tip-top';
								endif; ?>
								<span class="sortable-handler <?php echo $disableClassName?>" title="<?php echo $disabledLabel?>" rel="tooltip">
									<i class="icon-menu"></i>
								</span>
								<input type="text" style="display:none"  name="order[]" size="5"
								value="<?php echo $item->ordering;?>" class="width-20 text-area-order " />
							<?php else : ?>
								<span class="sortable-handler inactive" >
									<i class="icon-menu"></i>
								</span>
							<?php endif; ?>
						</td>
						<td class="center hidden-phone">
							<?php echo JHtml::_('grid.id', $i, $item->id); ?>
						</td>
						<td class="center">
							<?php echo JHtml::_('jgrid.published', $item->published, $i, 'newsfeeds.', $canChange, 'cb', $item->publish_up, $item->publish_down); ?>
						</td>
						<td class="nowrap has-context">
							<div class="pull-left">
								<?php if ($item->checked_out) : ?>
									<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'newsfeeds.', $canCheckin); ?>
								<?php endif; ?>
								<?php if ($canEdit) : ?>
									<a href="<?php echo JRoute::_('index.php?option=com_newsfeeds&task=newsfeed.edit&id='.(int) $item->id); ?>">
										<?php echo $this->escape($item->name); ?></a>
								<?php else : ?>
										<?php echo $this->escape($item->name); ?>
								<?php endif; ?>
								<span class="small">
									<?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias));?>
								</span>
								<div class="small">
									<?php echo $this->escape($item->category_title); ?>
								</div>
							</div>
							<div class="pull-left">
								<?php
									// Create dropdown items
				 					JHtml::_('dropdown.edit', $item->id, 'newsfeed.');
				 					JHtml::_('dropdown.divider');
				 					if ($item->published) :
				 						JHtml::_('dropdown.unpublish', 'cb' . $i, 'newsfeeds.');
				 					else :
				 						JHtml::_('dropdown.publish', 'cb' . $i, 'newsfeeds.');
				 					endif;

				 					JHtml::_('dropdown.divider');

				 					if ($archived) :
				 						JHtml::_('dropdown.unarchive', 'cb' . $i, 'newsfeeds.');
				 					else :
				 						JHtml::_('dropdown.archive', 'cb' . $i, 'newsfeeds.');
				 					endif;

				 					if ($item->checked_out) :
				 						JHtml::_('dropdown.checkin', 'cb' . $i, 'newsfeeds.');
				 					endif;

				 					if ($trashed) :
				 						JHtml::_('dropdown.untrash', 'cb' . $i, 'newsfeeds.');
				 					else :
				 						JHtml::_('dropdown.trash', 'cb' . $i, 'newsfeeds.');
				 					endif;

				 					// render dropdown list
				 					echo JHtml::_('dropdown.render');
				 					?>
							</div>

						</td>
						<td class="small hidden-phone">
							<?php echo $this->escape($item->access_level); ?>
						</td>
						<td class="center hidden-phone">
							<?php echo (int) $item->numarticles; ?>
						</td>
						<td class="center hidden-phone">
							<?php echo (int) $item->cache_time; ?>
						</td>
						<td class="small hidden-phone">
							<?php if ($item->language=='*'):?>
								<?php echo JText::alt('JALL', 'language'); ?>
							<?php else:?>
								<?php echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
							<?php endif;?>
						</td>
						<td class="center hidden-phone">
							<?php echo (int) $item->id; ?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		
			<?php //Load the batch processing form. ?>
			<?php echo $this->loadTemplate('batch'); ?>
		
			<div>
				<input type="hidden" name="task" value="" />
				<input type="hidden" name="boxchecked" value="0" />
				<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
				<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
				<?php echo JHtml::_('form.token'); ?>
			</div>
		</div>
		<!-- End Content -->
	</div>
</form>
