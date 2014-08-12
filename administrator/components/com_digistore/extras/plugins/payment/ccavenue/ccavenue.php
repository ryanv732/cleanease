<?php
//error_reporting(E_ALL);
//ini_set('display_errors','On');

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );
if(JVERSION >='1.6.0')
	require_once(JPATH_SITE.'/plugins/payment/ccavenue/ccavenue/helper.php');
else
	require_once(JPATH_SITE.'/plugins/payment/ccavenue/helper.php');
class  plgPaymentCcavenue extends JPlugin
{

	function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		//Set the language in the class
		$config = JFactory::getConfig();


		//Define Payment Status codes in payu  And Respective Alias in Framework
		$this->responseStatus= array( 'Y'=>'C', 'B'=>'P', 'N'=>'D' );
	}

	function buildLayoutPath($layout) {
		$app = JFactory::getApplication();
	if(empty($layout))
		$layout="default";

		$core_file 	= dirname(__FILE__).DS.$this->_name.DS.'tmpl'.DS.$layout.'.php';
		$override		= JPATH_BASE.DS.'templates'.DS.$app->getTemplate().DS.'html'.DS.'plugins'.DS.$this->_type.DS.$this->_name.DS.$layout.'.php';
		if(JFile::exists($override))
		{
			return $override;
		}
		else
		{
	  	return  $core_file;
	}
	}

	//Builds the layout to be shown, along with hidden fields.
	function buildLayout($vars, $layout = 'default' )
	{
		// Load the layout & push variables
		ob_start();
		$layout = $this->buildLayoutPath($layout);
		include($layout);
		$html = ob_get_contents(); 
		ob_end_clean();
		return $html;
	}

	// Used to Build List of Payment Gateway in the respective Components
	function onTP_GetInfo($config)
	{
		if(!in_array($this->_name,$config))
		return;

		$obj 		= new stdClass;
		$obj->name 	=$this->params->get( 'plugin_name' );
		$obj->id	= $this->_name;
		return $obj;
	}

	//Constructs the Payment form in case of On Site Payment gateways like Auth.net & constructs the Submit button in case of offsite ones like Payu
	function onTP_GetHTML($vars)
	{

		$vars->action_url = plgPaymentCcavenueHelper::buildCcavenueUrl();
		//Take this receiver email address from plugin if component not provided it
//		if(empty($vars->business))

		$vars->merchant_id = $this->params->get('merchant_id');
		$vars->working_key = $this->params->get('working_key');
		$vars->notify_url=JURI::base().'ccavenue.php';
		$vars->checksumval = $this->getCheckSum($vars->merchant_id,$vars->amount,$vars->order_id,$vars->notify_url,$vars->working_key);

		$html = $this->buildLayout($vars);

		return $html;
	}

	function getchecksum($MerchantId,$Amount,$OrderId,$URL,$WorkingKey) {
		$str ="$MerchantId|$OrderId|$Amount|$URL|$WorkingKey";
		$adler = 1;
		$adler = $this->adler32($adler,$str);
		return $adler;
	}


	function verifychecksum($MerchantId,$OrderId,$Amount,$AuthDesc,$CheckSum,$WorkingKey) {
		$str = "$MerchantId|$OrderId|$Amount|$AuthDesc|$WorkingKey";
		$adler = 1;
		$adler = $this->adler32($adler,$str);

		if($adler == $CheckSum)
			return "true" ;
		else
			return "false" ;
	}


	function adler32($adler , $str) {
		$BASE =  65521 ;

		$s1 = $adler & 0xffff ;
		$s2 = ($adler >> 16) & 0xffff;
		for($i = 0 ; $i < strlen($str) ; $i++)
		{
			$s1 = ($s1 + Ord($str[$i])) % $BASE ;
			$s2 = ($s2 + $s1) % $BASE ;
			//echo "s1 : $s1 <BR> s2 : $s2 <BR>";

		}
		return $this->leftshift($s2 , 16) + $s1;
	}


	function leftshift($str , $num)
	{
		$str = DecBin($str);

		for( $i = 0 ; $i < (64 - strlen($str)) ; $i++)
		$str = "0".$str ;

		for($i = 0 ; $i < $num ; $i++) {
			$str = $str."0";
			$str = substr($str , 1 ) ;
			//echo "str : $str <BR>";
		}
		return $this->cdec($str) ;
	}


	function cdec($num)
	{

		$dec =  '';
		for ($n = 0 ; $n < strlen($num) ; $n++) {
			$temp = $num[$n] ;
			$dec =  $dec + $temp*pow(2 , strlen($num) - $n - 1);
		}

		return $dec;
	}

	function onTP_Processpayment($data) {

		$working_key = $this->params->get('working_key');
		$verify = $this->verifychecksum($data['Merchant_Id'], $data['Order_Id'], $data['Amount'], $data['AuthDesc'], $data['Checksum'], $working_key);

//commented by Dipti @7/9/12
//		if (!$verify) { return false; }

		$payment_status = $this->translateResponse($data['AuthDesc']);
		$data['verify'] = $verify;

		//Error Handling
		$error=array();
		if (!$verify) {
			$error['code']	='501'; //@TODO change these $data indexes afterwards
			$error['desc']	='Checksum failed';
		}

		$result = array(
						'order_id'=>$data['Order_Id'],
						'transaction_id'=>$data['nb_bid'],
						'buyer_email'=>$data['billing_cust_email'],
						'status'=>$payment_status,
						'txn_type'=>$data['card_category'],
						'total_paid_amt'=>$data['Amount'],
						'raw_data'=>$data,
						'error'=>$error,
						);
		return $result;
	}

	function translateResponse($payment_status){
		foreach($this->responseStatus as $key=>$value)
		{
			if($key==$payment_status)
			return $value;
		}
	}

	function onTP_Storelog($data)
	{
			$log = plgPaymentCcavenueHelper::Storelog($this->_name,$data);

	}

}
