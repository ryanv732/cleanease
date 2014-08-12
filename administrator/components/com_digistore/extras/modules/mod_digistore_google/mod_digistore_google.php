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

// locationid must be an integer

$database = JFactory::getDBO();
//$google_UA = trim( $params->get( 'google_account' ) );
$sql = "select google_account from #__digistore_settings";
$database->setQuery($sql);
$google_UA = $database->loadResult();
if (getenv("HTTPS") == "on") $url = "https://ssl.google-analytics.com/urchin.js"; 
else $url = "http://www.google-analytics.com/urchin.js";
if (strlen(trim($google_UA)) > 1 )
$content123 ='
<script src="'.$url.'" type="text/javascript">
	</script>
	<script type="text/javascript">
	  _uacct="'.$google_UA.'";
	  urchinTracker();
	</script>
'; else $content123 = '';

if (strlen(trim($google_UA)) > 1 )
$content = '
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("'.$google_UA.'");
pageTracker._trackPageview();
} catch(err) {alert(err);}</script>
';
//echo $content;
?>