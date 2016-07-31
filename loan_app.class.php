<?php
include_once ('db.php');

class  LoanApp{

   protected  $property_value;
   protected  $ssn;
   protected  $loan_amount;
   protected  $date_created;
   protected  $application_id;
   protected  $loan_status;
   protected  $error;
   

   function __construct( $application_id_in='', $property_value_in='', $loan_amount_in='', $ssn_in ='', $date_created_in='', $loan_status_in='')
   {
   
	   $this->property_value = $property_value_in;
	   $this->ssn = $ssn_in;
	   $this->loan_amount = $loan_amount_in;
	   $this->date_created = $date_created_in;
	   $this->loan_status = $loan_status_in;
	   $this->application_id = $application_id_in;
	   $this->error = '';
	   
	}
	
	public function find_in_db( $dbh){

		$this->error = '';
		$sql = "SELECT * FROM application WHERE (loan_amount='$this->loan_amount' AND property_value='$this->property_value' AND ssn='$this->ssn' AND date_created='$this->date_created') OR ";	
		$sql .= " (application_id='$this->application_id') ";

		$arr = $dbh->db_result_to_array($sql);

		if( $arr['num_results'] ==1){
		   $this->property_value = $arr[0][''];
		   $this->ssn = $arr[0]['ssn'];
		   $this->loan_amount = $arr[0]['loan_amount'];
		   $this->date_created = $arr[0]['date_created'];
		   $this->loan_status = $arr[0]['loan_status'];
		   $this->application_id = $arr[0]['application_id'];
			
		}
		if( $arr['num_results'] > 1){
			$this->error = 'Too many';
		}	
		if( $arr['num_results'] ==0){
			$this->error = 'no_reccords';
		}	
		
    }

	public function save_in_db( $dbh){

	$this->error = '';
		if ($this->application_id !='' && $this->application_id != 0){
			$app_part = ",application_id='$this->application_id' ";
		}else{
			$this->application_id = $this->create_app_id();
			$app_part = ",application_id='$this->application_id' ";
		}		

		if ($this->loan_status !='' && $this->loan_status != 0){
			$status_part = ",loan_status='$this->loan_status' ";
		}else{
			$status_part = '';
		}		
		
		
		$sql = "INSERT INTO application SET loan_amount='$this->loan_amount',  property_value='$this->property_value', ssn='$this->ssn', date_created='$this->date_created' ";	
		$sql .= $app_part . $status_part;
		$arr = $dbh->query($sql);

	}
	
	public function read_from_db( $dbh, $application_id_in='', $property_value_in='', $loan_amount_in='', $ssn_in ='', $date_created_in=''){

		$this->error = '';
		$sql = "SELECT * FROM application WHERE (loan_amount='$loan_amount_in' AND property_value='$property_value_in' AND ssn='$ssn_in' AND date_created='$date_created_in') OR ";	
		$sql .= " (application_id='$application_id_in') ";
		$arr = $dbh->db_result_to_array($sql);


		if( $arr['num_results'] ==1){
		   $this->property_value = $arr[0][''];
		   $this->ssn = $arr[0]['ssn'];
		   $this->loan_amount = $arr[0]['loan_amount'];
		   $this->date_created = $arr[0]['date_created'];
		   $this->loan_status = $arr[0]['loan_status'];
		   $this->application_id = $arr[0]['application_id'];
			
		}
		if( $arr['num_results'] > 1){
			$this->error = 'Too many';
		}	
		if( $arr['num_results'] ==0){
			$this->error = 'no_reccords';
		}	
		
    }
	
	public function getError(){
         return $this->error;
    }

	public function getAppID(){
         return $this->application_id;
    }

	public function getLoanStatus(){
         return $this->loan_status;
    }

	private function create_app_id(){
		$buf = $this->date_created;
		$sum=0;
		for($i=0; $i< strlen($this->ssn); $i++){
			$sum+= ord(substr($this->ssn, $i, 1)) * ($i + 1);
		}
		$buf .= $sum . $this->loan_amount ;
		
         return $buf;
    }
	
	
} // end of class LoanApp
