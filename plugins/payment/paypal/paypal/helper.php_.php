<?php
defined( '_JEXEC' ) or die( ';)' );
	jimport('joomla.html.html');
	jimport( 'joomla.plugin.helper' );
class plgPaymentPaypalHelper
{ 

	//gets the paypal URL
	public static function buildPaypalUrl($secure = true, $params )
	{
		$secure_post 	= $params->get('secure_post');
		$url 			= $params->get('sandbox') ? 'www.sandbox.paypal.com' : 'www.paypal.com';
		if ( $secure_post )
			$url = 'https://' . $url . '/cgi-bin/webscr';
		else
			$url = 'http://' . $url . '/cgi-bin/webscr';
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
		function validateIPN( $data)
	{

	 // parse the paypal URL
	 $url=plgPaymentPaypalHelper::buildPaypalUrl();		  
	 $this->paypal_url= $url;
	  $url_parsed=parse_url($url);
$data1=array();
$data1['raw_data']=$data;
$data1['JT_CLIENT']="com_digistore_validate";
Storelog('paypal',$data1);
	  // generate the post string from the _POST vars aswell as load the
	  // _POST vars into an arry so we can play with them from the calling
	  // script.
	   // append ipn command
	  // open the connection to paypal
	  $fp = fsockopen($url_parsed[host],"80",$err_num,$err_str,30);
	 // $fp = fsockopen ($this->paypal_url, 80, $errno, $errstr, 30);

	  if(!$fp) {

		 // could not open the connection.  If loggin is on, the error message
		 // will be in the log.
		 $this->last_error = "fsockopen error no. $errnum: $errstr";
		 plgPaymentPaypalHelper::log_ipn_results(false);	   
		 return false;
		 
	  } else {



 		$post_string = '';
	  foreach ($data as $field=>$value) { 
		 $this->ipn_data["$field"] = $value;
		 $post_string .= $field.'='.urlencode(stripslashes($value)).'&'; 
	  }
	  $post_string.="cmd=_notify-validate";

		 // Post the data back to paypal
		 fputs($fp, "POST $url_parsed[path] HTTP/1.1\r\n"); 
		 fputs($fp, "Host: $url_parsed[host]\r\n"); 
		 fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n"); 
		 fputs($fp, "Content-length: ".strlen($post_string)."\r\n"); 
		 fputs($fp, "Connection: close\r\n\r\n"); 
		 fputs($fp, $post_string . "\r\n\r\n"); 

		 // loop through the response from the server and append to variable
		 while(!feof($fp)) { 
			$this->ipn_response .= fgets($fp, 1024); 
		 } 

		 fclose($fp); // close connection

	  }

	  if (eregi("verified",$post_string)) {

		 // Valid IPN transaction.
		 plgPaymentPaypalHelper::log_ipn_results(true);
		 return true;	   
		 
	  } else {

		 // Invalid IPN transaction.  Check the log for details.
		 $this->last_error = 'IPN Validation Failed.';
		 plgPaymentPaypalHelper::log_ipn_results(false);   
		 return false;
		 
	  }

	}
		function log_ipn_results($success) {
	   
	  if (!$this->ipn_log) return; 
	  
	  // Timestamp
	  $text = '['.date('m/d/Y g:i A').'] - '; 
	  
	  // Success or failure being logged?
	  if ($success) $text .= "SUCCESS!\n";
	  else $text .= 'FAIL: '.$this->last_error."\n";
	  
	  // Log the POST variables
	  $text .= "IPN POST Vars from Paypal:\n";
	  foreach ($this->ipn_data as $key=>$value) {
		 $text .= "$key=$value, ";
	  }
 
	  // Log the response from the paypal server
	  $text .= "\nIPN Response from Paypal Server:\n ".$this->ipn_response;
	  // Write to log
	  $fp=fopen($this->ipn_log_file,'a');
	  fwrite($fp, $text . "\n\n");
	  fclose($fp);  // close file
   }

}
