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

$mosConfig_absolute_path = JPATH_ROOT;

$document = JFactory::getDocument();
$document->addStyleSheet("components/com_digistore/assets/css/digistore.css");

?>
<table class="adminform table">
	<tr>
		<td>
			<table>
				<tr>
					<td colspan="4">
	<?php
	//echo LM_ABOUT_HEADING;
	echo "			</td>
				</tr>
	";

	//angek: magazine-66 : start: lets do this dynamically
	$installed_parts	= array();
	$notinstalled_parts	= array();
	global $counter;

	$titles = array(
		(JText::_("DSABOUTCOMPONENT")),
		(JText::_('DSABOUTMODULES')),
		(JText::_('DSABOUTPLUGIN'))
	);

	$total_file_titles = array (
		"component"	=>"DigiStore",
		"module1"	=>"DigiStore Manager",
		"module2"	=>"DigiStore Shopping Cart"
	// 	"module3"=>"Digistore Categories",
	// 	"module4"=>"Featured Article",
	// 	"module5"=>"Current Articles",
	// 	"module6"=>"Author List",
	// 	"plugin"=>"Magazine"
	);

	$total_file_paths = array(
		"component"	=> "/administrator/components/com_digistore/digistore.xml",
		"module1"	=> "/modules/mod_digistore_manager/mod_digistore_manager.xml",
		"module2"	=> "/modules/mod_digistore_cart/mod_digistore_cart.xml"
	//	"module3"	=> "/modules/mod_digistore_categories.xml",
	//	"module4"	=> "/modules/mod_featured_article.xml",
	//	"module5"	=> "/modules/mod_current_articles.xml",
	//	"module6"	=> "/modules/mod_author_list.xml",
	//	"plugin"	=> "/mambots/content/mos_magazine.xml"
	);

	foreach ($total_file_paths as $var=>$val) :
		$counter += 1;
		if ($counter == 1) {
			echo "<td colspan=\"4\"><strong>".$titles[0]."</strong></td>";
		} else if ($counter == 2) {
			echo "<td colspan=\"4\"><strong>".$titles[1]."</strong></td>";
		} else if ($counter == 8 ) {
			echo "<td colspan=\"4\"><strong>".$titles[2]."</strong></td>";
		}
		echo "</tr>";
		$f_data = $mosConfig_absolute_path.$val;

		if (is_file($f_data)) {
			$data = implode ("", file ( $f_data ) );
			$pos1 = strpos ($data,"<version>");
			$pos2 = strpos ($data,"</version>");
			$version = substr ($data, $pos1+strlen("<version>"), $pos2-$pos1-strlen("<version>"));

		//  fclose( file ($f_data) );
			echo "<tr><td width=\"20px\">&nbsp;</td><td width=\"90px\" align=\"left\"><font color=\"green\"><strong>".(JText::_('DSINSTALLED'))."</strong></font></td><td width=\"130px\" nowrap>+ ".$total_file_titles[$var]. "<td nowrap>version ";
			echo $version;
			echo "</td></tr>";
		} else {
			//echo "&nbsp;&nbsp;&nbsp;&nbsp;+ ".$total_file_titles[$var]. "&nbsp;&nbsp<font color=\"red\"><strong>Not Installed</strong></font><br />";
			echo "<tr><td width=\"20px\">&nbsp;</td><td  nowrap width=\"90px\" align=\"left\"><font color=\"red\"><strong><nowrap>".(JText::_('DSNOTINSTALLED'))."</nowrap></strong></font></td><td width=\"130px\" nowrap>+ ".$total_file_titles[$var]. "<td nowrap>&nbsp;</td></tr>";
			array_push($notinstalled_parts,$var);
		}
	endforeach;

	echo "
			</td>
			</tr>
		</table>
	";
	echo "<tr><td> ";
	echo JText::_("DSABOUTBODY");
?>

</td></tr>
</table>