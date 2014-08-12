<?php
/**
* @package			DigiStore Joomla Extension
 * @author			foobla.com
 * @version			$Revision: 341 $
 * @lastmodified	$LastChangedDate: 2013-10-10 14:28:28 +0200 (Thu, 10 Oct 2013) $
 * @copyright		Copyright (C) 2013 foobla.com. All rights reserved.
* @license			GNU/GPLv3
*/

defined ('_JEXEC') or die ("Go away.");

jimport ('joomla.application.component.controller');

// http://www.jomsocial.com/index.php?option=com_digistore&controller=digistoreJSCron&task=groupfix

class digistoreControllerdigistoreJSCron extends digistoreController {
	
	/*function expire(){

		$db = JFactory::getDBO();

		$sql = "SELECT DISTINCT(userid)
			FROM `#__digistore_licenses`
			WHERE `expires` < CURDATE()
			AND userid NOT IN(SELECT `userid` FROM `#__digistore_licenses` WHERE `expires` > CURDATE())
			ORDER BY `userid`";

		$db->setQuery($sql);
		$db->query();
		$rows = $db->loadObjectList();

		foreach($rows as $row)
		{
			try {
				JUserHelper::removeUserFromGroup($row->userid, 9);
				JUserHelper::addUserToGroup($row->userid, 15);
			} catch (Exception $e) {
				$errors++;
				// echo "".$row->id."\n";
				// echo "".$e->getMessage()."\n";
			}
		}

		exit;
	}*/

	/*function test()
	{
		$my = JFactory::getUser();
		if($my->id){
			$db = JFactory::getDBO();

			$sql = "SELECT licenses.expires as expires 
					FROM ejlxm_digistore_licenses as licenses 
					WHERE licenses.userid = " . $db->Quote( $my->id ) . " 
					AND licenses.expires > NOW()";

			$db->setQuery($sql);
			$db->query();
			$rows = $db->loadObjectList();

			if(count($rows)) {
				JUserHelper::addUserToGroup($my->id, 9);
			}
		}
	}*/

	function groupfix()
	{
		$db = JFactory::getDBO();

		$sql = "SELECT users.id as userid, users.registerDate as registerDate, licenses.expires as expires
				FROM #__users as users LEFT JOIN #__digistore_licenses as licenses
				ON users.id = licenses.userid
				WHERE licenses.expires > NOW()";

		$db->setQuery($sql);
		$db->query();
		$rows = $db->loadObjectList();

		$count = 0;

		foreach($rows as $row)
		{
			try
			{
				$groups = JUserHelper::getUserGroups($row->userid);	

				if(!in_array(9, $groups)) {
					JUserHelper::addUserToGroup($row->userid, 9);
					echo 'User ID: ' . $row->userid . ' Register Date: ' . $row->registerDate . ' Expiry Date: ' . $row->expires . "<br />";
					// echo 'Groups: ' . implode(",", $groups) . "<br />";
					$count++;
				}
			}
			catch (Exception $e)
			{
				$errors++;
				echo "".$row->id."\n";
				echo "".$e->getMessage()."\n";
			}
		}

		echo "Total users not in customer group: $count<br /><br />";
	}
};

?>