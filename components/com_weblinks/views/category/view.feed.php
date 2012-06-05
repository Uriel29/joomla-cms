<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_weblinks
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * HTML View class for the WebLinks component
 *
 * @static
 * @package		Joomla.Site
 * @subpackage	com_weblinks
 * @since 1.0
 */
class WeblinksViewCategory extends JView
{
	function display($tpl = null)
	{
		$app	= JFactory::getApplication();
		$document = JFactory::getDocument();

		$document->link = JRoute::_(WeblinksHelperRoute::getCategoryRoute(JRequest::getVar('id', null, '', 'int')));

		JRequest::setVar('limit', $app->getCfg('feed_limit'));
		$params = $app->getParams();
		$siteEmail = $app->getCfg('mailfrom');
		$fromName = $app->getCfg('fromname');
		$feedEmail	= $app->getCfg('feed_email','author');
		$document->editor = $fromName;
		$document->editorEmail = $siteEmail;

		// Get some data from the model
		$items		= $this->get('Items');
		$category	= $this->get('Category');

		foreach ($items as $item)
		{
			// strip html from feed item title
			$title = $this->escape($item->title);
			$title = html_entity_decode($title, ENT_COMPAT, 'UTF-8');

			// url link to article
			$link = JRoute::_(WeblinksHelperRoute::getWeblinkRoute($item->id, $item->catid));

			// strip html from feed item description text
			$description = $item->description;
			$author			= $item->created_by_alias ? $item->created_by_alias : $item->created_by;
			$date = ($item->date ? date('r', strtotime($item->date)) : '');

			// load individual item creator class
			$feeditem = new JFeedItem();
			$feeditem->title		= $title;
			$feeditem->link			= $link;
			$feeditem->description	= $description;
			$feeditem->date			= $date;
			$feeditem->category		= $category->title;
			$feeditem->author		= $author;

			// We don't have the author email so we have to use site in both cases.
			if ($feedEmail == 'site') 
			{
				$feeditem->authorEmail = $siteEmail;
			} 
			elseif($feedEmail === 'author')
			{
				$feeditem->authorEmail = $siteEmail;
			}

			// loads item info into rss array
			$document->addItem($feeditem);
		}
	}
}
?>
