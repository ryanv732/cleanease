<?php
/**
* @package			DigiStore Joomla Extension
 * @author			foobla.com
 * @version			$Revision: 341 $
 * @lastmodified	$LastChangedDate: 2013-10-10 14:28:28 +0200 (Thu, 10 Oct 2013) $
 * @copyright		Copyright (C) 2013 foobla.com. All rights reserved.
* @license			GNU/GPLv3
*/

defined( '_JEXEC' ) or die( "Go away." );

jimport( 'joomla.application.component.controller' );

class digistoreAdminControllerdigistoreUpgrade extends digistoreAdminController
{
	function __construct()
	{
		parent::__construct();
		$this->registerTask( "", "run" );
		//$this->registerTask( "once", "run" );
	}

	function run() {
		require_once( JPATH_COMPONENT . DS . 'upgrade' . DS . 'upgrade.php' );
		$upgrade = new dsUpgrader();
		$upgrade->run();
//		$upgrade->once();
	}

	function once() {
		require_once( JPATH_COMPONENT . DS . 'upgrade' . DS . 'upgrade.php' );
		$upgrade = new dsUpgrader();
		$upgrade->once();
	}

}

?>