<?php

class mydb  {

/* public/private variables: configuration parameters */
	var $_debug;
	var $debug_handle;
	var $query;
	var $insert_id;
  
  	# @object, The PDO object
	private $pdo;
	# @object, PDO statement object
	private $sQuery;
	# @array,  The database settings
	private $settings;
	# @bool ,  Connected to the database
	private $bConnected = false;
	# @object, Object for logging exceptions	
	private $log;
	# @array, The parameters of the SQL query
	private $parameters;
	private $_password;
	private $_host;
	private $_user;
	private $_database;
		

		public function __construct()
		{ 			
			$this->parameters = array();
		}

		  /* connection management */
		function connect () {

			/* Handle defaults */
			include ("config.php");

			$this->_host     = $host;
			$this->_database = $database;
			$this->_user     = $user;
			$this->_password =  $password;
			$dsn = 'mysql:dbname='.$this->_database.';host='.$this->_host.'';
			try 
			{
				$dsn = 'mysql:host='.$this->_host. ';dbname='.$this->_database.';charset=utf8';
				$this->pdo  = new PDO($dsn, $this->_user, $this->_password , array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));						
				$this->bConnected = true;
			}
			catch (PDOException $e) 
			{
				echo "Could not connect to DB";
				die();
			}
			
			
		}

	 	public function CloseConnection()
	 	{
	 		$this->pdo = null;
	 	}
	

  	private function Init($query,$parameters = "")
		{
		# Connect to database
		if(!$this->bConnected) { $this->Connect(); }
		try {
				# Prepare query
				$this->sQuery = $this->pdo->prepare($query);
				
				# Add parameters to the parameter array	
				$this->bindMore($parameters);
				# Bind parameters
				if(!empty($this->parameters)) {
					foreach($this->parameters as $param)
					{
						$parameters = explode("\x7F",$param);
						$this->sQuery->bindParam($parameters[0],$parameters[1]);
					}		
				}
				# Execute SQL 
				$this->succes 	= $this->sQuery->execute();		
			}
			catch(PDOException $e)
			{
					# Write into log and display Exception
////					echo $this->ExceptionLog($e->getMessage(), $query );
					die();
			}
			# Reset the parameters
			$this->parameters = array();
		}
		
  /**
	*	@void 
	*
	*	Add the parameter to the parameter array
	*	@param string $para  
	*	@param string $value 
	*/	
		public function bind($para, $value)
		{	
			$this->parameters[sizeof($this->parameters)] = ":" . $para . "\x7F" . utf8_encode($value);
		}
  /**
	*	@void
	*	
	*	Add more parameters to the parameter array
	*	@param array $parray
	*/	
		public function bindMore($parray)
		{
			if(empty($this->parameters) && is_array($parray)) {
				$columns = array_keys($parray);
				foreach($columns as $i => &$column)	{
					$this->bind($column, $parray[$column]);
				}
			}
		}
		public function query($query,$params = null, $fetchmode = PDO::FETCH_ASSOC)
		{
			$query = trim($query);
			$this->Init($query,$params);
			$rawStatement = explode(" ", $query);
			
			# Which SQL statement is used 
			$statement = strtolower($rawStatement[0]);
			
			if ($statement === 'select' || $statement === 'show') {
				return $this->sQuery->fetchAll($fetchmode);
			}
			elseif ( $statement === 'insert' ||  $statement === 'update' || $statement === 'delete' ) {
				return $this->sQuery->rowCount();	
			}	
			else {
				return NULL;
			}
		}
		
 /**
       *  Returns the last inserted id.
       *  @return string
       */	
		public function lastInsertId() {
			return $this->pdo->lastInsertId();
		}	

  /**
  *  Returns an array where key is columsn name and value is data
  *  @access public
  *  @return array.

	Usage:
	  $user_array = $dbh->db_result_to_array($query);
	  echo  "<BR>NumRS = " . $user_array['num_results'] . "<BR>";
	  echo  "<BR>Last_Name = " . $user_array[0]['last_name'] . "<BR>";

  */
  function db_result_to_array($query)  {

    $returnback   = array();
    $ret =  $this->query($query);
	
	$ar_cnt = sizeof($ret);
    if($ar_cnt  > 0){
        for ($count=0; $count < $ar_cnt; $count++) {
          $returnback[$count] = $ret[$count];
	    }
        $returnback['num_results'] = $ar_cnt;
        $returnback['errors']      = 0;
        $returnback['sql']         = $query;
	}
	else{
        $returnback['results'] = 0;
        $returnback['num_results']   = 0;
        $returnback['errors']        = 1;
        $returnback['sql']           = $this->query;
	}

    return $returnback;

  }

	
/***
 *  Function returns one value of a query doesn't matter what name

**/
	function return_one_value ($query ){

		$ret =  $this->query($query);
		$line = $ret[0];
 		if (is_array($line  ) ){
		   foreach ($line as $val ) {
		  		   return $val;
		   }
		}else
			return '';

	}
  
 /***
 **  Function checks if there are any records

**/
	function are_any_records ($query ){

		$ret_arr = $this->query($query);;
		
		$ar_cnt = count($ret_arr);

		if($ar_cnt  > 0){
			return 1;
		}else
			return 0;
	}

  
 } //end of class 

?>