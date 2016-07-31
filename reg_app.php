<?php
include_once ('db.php');
include_once ('loan_app.class.php');


	$error = "";

	if(isset($_REQUEST['property_value'])){
		$property_value = $_REQUEST['property_value'];
	} else {
		$property_value = 0;
		$error = "Wrong property value";
    }

	if(isset($_REQUEST['loan_amount'])){
		$loan_amount = $_REQUEST['loan_amount'];
	} else {
		$loan_amount = 0;
		$error = "Wrong loan amount";
    }

	if(isset($_REQUEST['ssn'])){
		$ssn = $_REQUEST['ssn'];
	} else {
		$ssn = 0;
		$error = "Wrong ssn";
    }

	$dbh = new mydb();
	$dbh->connect();
	date_default_timezone_set('America/Los_Angeles');
	$today = date("mdY"); 
	
	
	$lap = new LoanApp( '', $property_value, $loan_amount, $ssn, $today, '');
	
	if( $loan_amount / $property_value > 0.4)
		$error = 'This loan amount is too big for your property value';
	
	$lap->find_in_db($dbh);
	$res = $lap->getError();
	if($res!='no_reccords'){
		$error = 'You already created this application.  The application ID is ' . $lap->getAppID();
	}
	
	if($error=='')
	{
		$lap->save_in_db($dbh);		
	}
	

	$xml = '<responce>';	
	$xml .= "<error>$error</error>"  ;	
	$xml .= '<loan_id>' . $lap->getAppID() . '</loan_id>'  ;	
	$xml .= '</responce>';	
	echo $xml;
	

	
?>