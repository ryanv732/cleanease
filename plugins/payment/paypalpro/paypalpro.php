<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
defined( 'DS' ) or define( 'DS', DIRECTORY_SEPARATOR );

jimport( 'joomla.filesystem.file' );
jimport( 'joomla.plugin.plugin' );

require_once(dirname(__FILE__).DS.'paypalpro'.DS.'helper.php');

$lang = JFactory::getLanguage();
$lang->load('plg_payment_paypalpro', JPATH_ADMINISTRATOR);

class plgpaymentpaypalpro extends JPlugin 
{
	var $_payment_gateway = 'payment_paypalpro';
	var $_log = null;

	function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		//Set the language in the class
		$config = JFactory::getConfig();


		//Define Payment Status codes in Authorise  And Respective Alias in Framework
		//1 = Approved, 2 = Declined, 3 = Error, 4 = Held for Review
		$this->responseStatus= array(
			'Success' =>'C',
			'SuccessWithWarning' =>'C',
			'FailureWithWarning' =>'X',
			'Failure' =>'X',
		);



 		$this->login_id = $this->params->get( 'login_id', '1' );
		 $this->tran_key = $this->params->get( 'tran_key', '1' );

	}

	/* Internal use functions */
	function buildLayoutPath($layout) {
		$app = JFactory::getApplication();
		$core_file 	= dirname(__FILE__).DS.$this->_name.DS.'tmpl'.DS.'form.php';
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
	//gets param values
	function getParamResult($name, $default = '') 
	{
		$sandbox_param = "sandbox_$name";
		$sb_value = $this->params->get($sandbox_param);
	
		if ($this->params->get('sandbox') && !empty($sb_value)) {
			$param = $this->params->get($sandbox_param, $default);
		}
		else {
			$param = $this->params->get($name, $default);
		}
		
		return $param;
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



	//Constructs the Payment form in case of On Site Payment gateways like Auth.net & constructs the Submit button in case of offsite ones like Paypal
	function onTP_GetHTML($vars)
	{
		$html = $this->buildLayout($vars);
		return $html;
	}

	function onTP_Processpayment($data) 
	{
		$error=array();
		$sandbox = $this->params->get('sandbox') ;
		$action_url = plgPaymentpaypalproHelper::buildpaypalproUrl( $sandbox );

		$exp_month=str_pad($data['expire_month'],2, "0", STR_PAD_LEFT);
		$data['cardexp']=$exp_month.$data['expire_year'];

		$pro_values				= array(
									"METHOD"					=>'DoDirectPayment', 
									"VERSION"					=> "65.0",
									"USER"						=> $this->params->get('pro_api_username'),
								 	"PWD"							=> $this->params->get('pro_api_password'),
									"SIGNATURE"				=> $this->params->get('pro_api_signature'),
									"PAYMENTACTION"		=> "Sale",
									"IPADDRESS"				=> $_SERVER['REMOTE_ADDR'],
									"AMT"							=> $data['chargetotal'],
									"CREDITCARDTYPE"	=> $data['credit_card_type'],
									"ACCT"						=> $data['cardnum'],
									"EXPDATE"					=> $data['cardexp'],
									"CVV2"						=> $data['cardcsc'],
									"FIRSTNAME"				=> $data['cardfname'],
										"LASTNAME"			=> $data['cardlname'],
										"STREET"				=> $data['cardaddress1'],
										"CITY"					=> $data['cardcity'],
										"STATE"					=> $data['cardstate'],
										"ZIP"						=> $data['cardzip'],
									"COUNTRYCODE"			=>$data['cardcountry'],
									"INVNUM"			=>$data['order_id'],
									);

		$fields = "";
		foreach($pro_values as $key => $value) 
			$fields .= "$key=".urlencode($value). "&";

		//call to curl
		$ch = curl_init($action_url); 
		curl_setopt($ch, CURLOPT_HEADER, 0); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim( $fields, "& " )); 
		//echo $ch;die;
		$resp = curl_exec($ch); //execute post and get results
		curl_close ($ch);
		//call to curl

		$allresp = explode('&',$resp);
		foreach($allresp as $r)
		{
				$res=explode('=',$r);
				$final_res[$res[0]]=urldecode($res[1]);
		}
	$payment_status=$this->translateResponse($final_res['ACK']);
 	  $error['code']=$final_res['L_ERRORCODE0'];
	  $error['desc']=$final_res['L_LONGMESSAGE0'];

		 
	$transaction_id = $final_res['TRANSACTIONID'];	 

	$result = array('transaction_id'=>$transaction_id,
					'order_id'=>$data['order_id'],
						'status'=>$payment_status,
						'total_paid_amt'=> $final_res['AMT'],
						'raw_data'=>$resp,
						'error'=>$error,
						'return'=>$data['return'],
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
			$log = plgPaymentPaypalproHelper::Storelog($this->_name,$data);

	}

}

