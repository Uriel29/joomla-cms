<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_newsfeeds
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.framework');

$n			= count($this->items);
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
?>

<?php if (empty($this->items)) : ?>
	<p> <?php echo JText::_('COM_NEWSFEEDS_NO_ARTICLES'); ?>	 </p>
<?php else : ?>

<form action="<?php echo htmlspecialchars(JUri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm">
	<?php if ($this->params->get('filter_field') != 'hide') : ?>
	<fieldset class="filters">
		<legend class="element-invisible">
			<?php echo JText::_('JGLOBAL_FILTER_LABEL'); ?>
		</legend>

		<div class="filter-search">
			<label class="filter-search-lbl" for="filter-search"><?php echo JText::_('COM_NEWSFEEDS_'.$this->params->get('filter_field').'_FILTER_LABEL').'&#160;'; ?></label>
			<input type="text" name="filter-search" id="filter-search" value="<?php echo $this->escape($this->state->get('list.filter')); ?>" class="inputbox" onchange="document.adminForm.submit();" title="<?php echo JText::_('COM_NEWSFEEDS_FILTER_SEARCH_DESC'); ?>" />
		</div>
	<?php endif; ?>

	<?php if ($this->params->get('show_pagination_limit')) : ?>
		<div class="display-limit">
			<?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>&#160;
			<?php echo $this->pagination->getLimitBox(); ?>
		</div>
	<?php endif; ?>

	<?php if ($this->params->get('filter_field') != 'hide') :?>
		</fieldset>
	<?php endif; ?>

	<ul class="category list-striped list-condensed">

		<?php foreach ($this->items as $i => $item) : ?>
			<?php if ($this->items[$i]->published == 0) : ?>
				<li class="system-unpublished cat-list-row<?php echo $i % 2; ?>">
			<?php else: ?>
				<li class="cat-list-row<?php echo $i % 2; ?>" >
			<?php endif; ?>
					<?php  if ($this->params->get('show_articles')) : ?>
						<span class="list-hits badge badge-info pull-right">
							<?php echo  JText::sprintf('COM_NEWSFEEDS_NUM_ARTICLES_COUNT', $item->numarticles); ?>
						</span>
					<?php  endif; ?>
					<span class="list pull-left">
					<strong class="list-title">
						<a href="<?php echo JRoute::_(NewsFeedsHelperRoute::getNewsfeedRoute($item->slug, $item->catid)); ?>">
							<?php echo $item->name; ?></a>
					</strong>
					</span>
					<?php if ($this->items[$i]->published == 0): ?>
						<span class="label label-warning">Unpublished</span>
					<?php endif; ?>
					<br />
					<?php  if ($this->params->get('show_link')) : ?>					
						<span class="list pull-left">
							<small>
								<a href="<?php echo $item->link; ?>"><?php echo $item->link; ?></a>
							</small>
						</span>
						<br/>
					<?php  endif; ?>
			</li>

		<?php endforeach; ?>
	</ul>
<?php endif; ?>

<?php // Add pagination links ?>
<?php if (!empty($this->items)) : ?>
	<?php if (($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->pagesTotal > 1)) : ?>
	<div class="pagination">

		<?php if ($this->params->def('show_pagination_results', 1)) : ?>
			<p class="counter pull-right">
				<?php echo $this->pagination->getPagesCounter(); ?>
			</p>
		<?php endif; ?>

		<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
	<?php endif; ?>
</form>
<?php  endif; ?>

