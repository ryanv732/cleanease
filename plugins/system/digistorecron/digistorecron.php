<?php
/**
* @package			DigiStore Joomla Extension
 * @author			foobla.com
 * @version			$Revision: 341 $
 * @lastmodified	$LastChangedDate: 2013-10-10 14:28:28 +0200 (Thu, 10 Oct 2013) $
 * @copyright		Copyright (C) 2013 foobla.com. All rights reserved.
* @license			GNU/GPLv3
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );
defined ('DS') or define('DS', DIRECTORY_SEPARATOR);

class plgSystemDigistoreCron extends JPlugin
{
	function plgSystemDigistoreCron(&$subject, $config)
	{
		parent::__construct($subject, $config);
	}

	function onAfterRender()
	{
	   $app = JFactory::getApplication();
		if($app->isSite())
		{
			require_once ( JPATH_ROOT . DS . 'components' . DS . 'com_digistore' . DS . 'helpers' . DS . 'cronjobs.php' );
			cronjobs();
		}
	}
}