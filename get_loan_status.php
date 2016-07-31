<?php
include_once ('db.php');
include_once ('loan_app.class.php');


	$error = "";

	
	if(isset($_REQUEST['loan_id'])){
		$loan_id = $_REQUEST['loan_id'];
	} else {
		$loan_id = 0;
		$error = "Wrong loan ID";
    }


	$dbh = new mydb();
	$dbh->connect();
	
	$lap = new LoanApp( $loan_id);
	
	$lap->find_in_db($dbh);
	$res = $lap->getError();
	if($res=='no_reccords'){
		$error = "You entered wrong application ID";
	}
	
	$xml = '<responce>';	
	$xml .= "<error>$error</error>"  ;	
	$xml .= '<loan_id>' . $lap->getAppID() . '</loan_id>'  ;	
	$xml .= '<loan_status>' . $lap->getLoanStatus() . '</loan_status>'  ;	
	$xml .= '</responce>';	
	echo $xml;
	

	
?>