<?php
/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );
//require_once JPATH_COMPONENT . DS . 'helper.php';
$lang = JFactory::getLanguage();
$lang->load('plg_payment_jomsocialpoints', JPATH_ADMINISTRATOR);
if(JVERSION >='1.6.0')
	require_once(JPATH_SITE.'/plugins/payment/jomsocialpoints/jomsocialpoints/helper.php');
else
	require_once(JPATH_SITE.'/plugins/payment/jomsocialpoints/helper.php');
class plgpaymentjomsocialpoints extends JPlugin 
{
	var $_payment_gateway = 'payment_jomsocialpoints';
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
			'Failure' =>'X',
		);
	}


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

	function onTP_GetHTML($vars)
	{
		$db = JFactory::getDBO();
		$query="SELECT points FROM #__community_users where userid=$vars->user_id";
		$db->setQuery($query);
		$user_points = $db->loadResult();
		$vars->user_points = $user_points;
		$vars->convert_val = $this->params->get('conversion');

		$html = $this->buildLayout($vars);
		return $html;
	}

	function onTP_GetInfo($config)
	{

		if(!in_array($this->_name,$config))
		return;
		$obj 		= new stdClass;
		$obj->name 	=$this->params->get( 'plugin_name' );
		$obj->id	= $this->_name;
		return $obj;
	}
	//Adds a row for the first time in the db, calls the layout view
	function onTP_Processpayment($data)
	{
		$db = JFactory::getDBO();
		$query="SELECT points FROM #__community_users where userid=".$data['user_id'];
		$db->setQuery($query);
		$points_count = $db->loadResult();
		$convert_val = $this->params->get('conversion');
		$points_charge=$data['total']*$convert_val;

		if($points_charge <= $points_count)
		{
			$count=$points_count - $points_charge;
			$sql ="UPDATE #__community_users SET points =".$db->quote($count)." WHERE userid=".$data['user_id'];
	  $db->setQuery($sql);
	  $db->query();
		  $payment_status=$this->translateResponse('Success');
		}
		else
		{
			$payment_status=$this->translateResponse('Failure');
	}
		$data['payment_status']=$payment_status;
			$result = array('transaction_id'=>'',
					'order_id'=>$data['order_id'],
						'status'=>$payment_status,
						'total_paid_amt'=>$data['total'],
						'raw_data'=>json_encode($data),
						'error'=>'',
						'return'=>$data['return'],
						);
	return $result;
  }
	function translateResponse($invoice_status){

		foreach($this->responseStatus as $key=>$value)
				{
					if($key==$invoice_status)
					return $value;
				}
	}
	function onTP_Storelog($data)
	{
			$log = plgPaymentJomsocialpointsHelper::Storelog($this->_name,$data);

	}
}


