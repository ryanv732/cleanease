<?php
	jimport('joomla.html.html');
	jimport( 'joomla.plugin.helper' );
class plgPaymentPayuHelper
{ 

	//gets the Payu URL
	function buildPayuUrl($secure = true)
	{
		$url = $this->params->get('sandbox') ? 'test.payu.in/_payment' : 'secure.payu.in/_payment';
		if ($secure) {
			$url = 'https://' . $url;
		}
		return $url;
	}


	public static function Storelog($name,$logdata)
	{

		jimport('joomla.error.log');
	$options = array('format' => "{DATE}\t{TIME}\t{USER}\t{DESC}");
		if(JVERSION >='1.6.0')
			$path=JPATH_SITE.'/plugins/payment/'.$name.'/'.$name.'/';
		else
			$path=JPATH_SITE.'/plugins/payment/'.$name.'/';	  
	  $my = JFactory::getUser();		
		//$logs = &JLog::getInstance($logdata['JT_CLIENT'].'_'.$name.'.log',$options,$path);
	JLog::addLogger(array('user' => $my->name.'('.$my->id.')','desc'=>json_encode($logdata['raw_data'])));

	}

}
