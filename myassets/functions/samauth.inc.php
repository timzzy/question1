<?php
/**
 * A simple but powerful general purpose Authentication Application Class. <br />
 *
 * This is a very <b>simple</b> but <b>powerful</b> general purpose Authentication Application Class
 *
 * EXAMPLES:
 *  <code>
 * $yourclass = new SamAuth(..........); //
 * </code>
 *
 * @copyright 2012
 * @author Ezekiel Aliyu
 * @version 1.00
 * @package SamAuthentication_Class
 * @name SamAuth
 */
/**
 * We will need two classes to work with this class
 * (1) SamMysql Class
 * 
 *
 */
// initialise SamMysql Class
//$mydb = &new SamMysql($dbcon);
// Begin Class Declaration
class SamAuth {
/**
  * The instance of the SamMysql database class
  * @var object
  * @access private
  */
    var $mydb;

/**
  * The user details from the users from table
  * @var array
  * @access private
  */
    var $user_det = array();

/**
  * The url of the page to return after authentication
  * @var string
  * @access private
  */
    var $to_url;

/**
 * The UserID returned if user is logged in
 * @var string
 * @access private
 */
	var $userID;

/**
 * The name of the users Logged in table in the database
 * @var string
 * @access private
 */
	var $user_log;

/**
 * The name of the users login record table in the database
 * @var string
 * @access private
 */
	var $rec_log;

/**
 * The name of the users temporary login table in the database
 * @var string
 * @access private
 */
	var $temp_log;

/**
 * The name of the date-time_log field in the 'login record' and the 'temporary login' tables above in the database
 * @var string
 * @access private
 */
	var $tlog_field;

/**
 * The name of the timer_log field in the user login above in the database
 * @var string
 * @access private
 */
	var $tim_field;

/**
 * The name of the users table in the database
 * @var string
 * @access private
 */
	var $u_table;

/**
 * The name of the user ID field in the users/log tables in the database
 * @var string
 * @access private
 */
	var $uid_field;

/**
 * The name of the username field in the users/log tables in the database
 * @var string
 * @access private
 */
	var $u_field;

/**
 * The name of the password field in the users table in the database
 * @var string
 * @access private
 */
	var $p_field;

/**
 * The correct time value for inserting into database
 * @var datetime
 * @access private
 */
	var $db_date;

/**
 * The Class Constructor
 *
 * Accepts your database connection object.
 * @param  object, instance of the SamMysql Class
 * @param string Name of Users Table in the Database
 * @param string Name of the UserID field on the Users table
 * @param string Name of the Username field on the Users table
 * @param string Name of the Password field on the Users table
 * @param string Name of the User_login table
 * @param string Name of the Record_login table
 * @param string Name of the Temp_login table
 * @param string Name of the Time_log field in the tables
 * @param string Name of the timer field in the User_login table
 */
	function SamAuth(&$mydb,$usertable,$userIDField,$usernameField,$passwordField,$userlog,$recordlog,$templog,$tlogField,$timField)
	{
		$this->mydb = &$mydb;
		$this->u_table = $usertable;
		$this->uid_field = $userIDField;
		$this->u_field = $usernameField;
		$this->p_field = $passwordField;
        $this->user_log = $userlog;
        $this->rec_log = $recordlog;
        $this->temp_log = $templog;
        $this->tlog_field = $tlogField;
        $this->tim_field = $timField;
	}


	/**
	 * Function to check if user login details are correct
	 *
	 * It checks if the user submitted details for loggin are correct
	 * <code>
	 * Usage
	 * =====
	 * $myauth = new SamAuth(.......);
	 * // check if user can login in
	 * if (!$myauth->user_login($_POST['username'],$_POST['password'])){
	 * 		// user cannot log in
	 * }
	 * else {
	 *  // user can log in, so get the user ID returned
	 *  $my_uid = $myauth->user_login($_POST['username'],$_POST['password']);
	 * }
	 * </code>
	 * @param string Username
	 * @param string Password
	 *
	 * @access Public
	 * @return mixed User ID
	 */
	function user_login($username,$password){
		// Are supplied values empty?
		if ($username =="" || $password =="") {
			$this->displayerror("You did not supply are valid Username and/Or Password. Please try again.");
		}

		// get details from database and compare
		$usdetail = $this->mydb->getrow_two($this->u_field,$username,$this->p_field,$password,$this->u_table);
		if (!$usdetail) {
			$this->displayerror(" Sorry, there is a problem with your username/password. Please try again");
		}
		if ($usdetail[$this->p_field] =="") {
			return false;
		}
		else {
			// return user ID
          $this->userID = $usdetail[$this->uid_field];
          return $this->userID;
		}

	}

   /**
    * Function to check if user lOGGED OUT from previous sessions
    *
    * @access Public
    * @param mixed UserID
    * @return True
    */
    function checkLogout ($userid) {
    	if ($userid =="") {
    		$this->displayerror("The user ID suplied seems incorrect or missing.");
    	}
    	// begin check - the user_login table
        $first = $this->mydb->getrow($this->uid_field,$userid,$this->user_log);
        if ($first[$this->u_field] !="") {
        	// user did not logged out previous session
        	// so get all temporary login details
        	$second = $this->mydb->getrow($this->uid_field,$userid,$this->temp_log);
            // see if this user already has a record in the Login Record table
            $third = $this->mydb->getrow($this->uid_field,$userid,$this->rec_log);
            if ($third[$this->u_field] =="") {
            	// user has no pevious login record, so copy temporary login details to login record
            	$fs = "$this->uid_field,,$this->u_field,,$this->tlog_field";
            	$v1 = $second[$this->uid_field];
            	$v2 = $second[$this->u_field];
            	$v3 = $second[$this->tlog_field];
            	$vs = "'$v1',,'$v2',,'$v3'";
            	// add the record
            	$this->mydb->addlist($fs,$vs,$this->rec_log,1);
            }
            elseif ($third[$this->u_field] == $second[$this->u_field]) {
            	// user already has a loggin record, so just update the loggin time
            	$fs = "$this->tlog_field";
            	$v1 = $second[$this->tlog_field];
            	$vs = "$v1";
            	// update the record
            	$this->mydb->updateval($fs,$vs,$this->rec_log,$this->uid_field,$userid);
            }
            // now delete the previous session data from user_loggin record
            $this->mydb->deleteval($this->user_log,$this->uid_field,$userid);
        }
        return true;
    }

    /**
     * Function to carry out the login process
     *
     * It logs the user details into the various log tables
     *
     * @access Public
     * @param mixed UserID
     * @return True
     */
    function doLogin($userid) {
    	if ($userid =="") {
    		$this->displayerror("The user ID suplied seems incorrect or missing. Cannot perform Login");
    	}
		// get the present time
		$tm = time();
		// insert details into user_login record
		// get user details from the users table
		$usdet = $this->guserdetail($userid);
		$v1 = $usdet[$this->u_field];
		$v2 = $tm;
		$fs = "$this->uid_field,,$this->u_field,,$this->tim_field";
		$vs = "'$userid',,'$v1',,'$v2'";
		$fs2 = "$this->uid_field,,$this->u_field,,$this->tlog_field";
		$vs2 ="'$userid',,'$v1',,NOW()";
		// insert record
		$this->mydb->addlist($fs,$vs,$this->user_log,1);
		// now update the temporary login record with this user
		// but, first check if user already has a record in the temporary login table
		$first = $this->mydb->getrow($this->uid_field,$userid,$this->temp_log);
		if ($first[$this->u_field] =="") {
			// not record yet, so add
			$this->mydb->addlist($fs2,$vs2,$this->temp_log,1);
		}
		elseif ($usdet[$this->u_field] == $first[$this->u_field]) {
			// record already exists, so update
			$nfs = "$this->tlog_field";
			//get the correct time format
			$mtim = $this->prep_date(time());
			$nvs = "$mtim";
			$this->mydb->updateval($nfs,$nvs,$this->temp_log,$this->uid_field,$userid);
		}
		return true;
    }

    /**
     * Function to carry out the logout process
     *
     * It logs the user out and updates the log records in the various log tables
     *
     * @access Public
     * @param mixed UserID
     * @return True
     */
    function doLogout($userid) {
    	if ($userid =="") {
    		$this->displayerror("The user ID suplied seems incorrect or missing. Cannot perform Logout");
    	}
    	// CHECK IF USER HAS NOT ALREADY LOGGED OUT
    	 $firsta = $this->mydb->getrow($this->uid_field,$userid,$this->user_log);
    	if ($firsta[$this->u_field] !="") {
		// delete the user_logged in details
		$this->mydb->deleteval($this->user_log,$this->uid_field,$userid);
		// now transfer the temporary login record of this user to the Login record table
		// get all record from the temporary login record table, first
		$first = $this->mydb->getrow($this->uid_field,$userid,$this->temp_log);
		// then, secondly check if user already has a record in the Login record table
		$second = $this->mydb->getrow($this->uid_field,$userid,$this->rec_log);
		if ($second[$this->u_field] =="") {
			// no previous record
			$fs = "$this->uid_field,,$this->u_field,,$this->tlog_field";
            $v1 = $first[$this->uid_field];
            $v2 = $first[$this->u_field];
            $v3 = $first[$this->tlog_field];
            $vs = "'$v1',,'$v2',,'$v3'";
            // add the record
            $this->mydb->addlist($fs,$vs,$this->rec_log,1);
		}
		elseif ($second[$this->u_field] == $first[$this->u_field]) {
			// record already exists, so update the time
			$nfs = "$this->tlog_field";
			$va = $first[$this->tlog_field];
			$nvs = "$va";
			$this->mydb->updateval($nfs,$nvs,$this->rec_log,$this->uid_field,$userid);
		}
		
      }
      else {
      	$this->displayerror("User had already logged out or session had expired.");
      }
    	// user successfully logged out
		return true;
    }


    /**
     * Function to get user details from the database
     *
     * It gets all the users details from the users table and return
     * an array that holds the result
     *
     * @access Public
     * @param mixed userID
     * @return array user details
     */
    function guserdetail($userid){
    	if ($userid =="") {
    		$this->displayerror("The user ID suplied seems incorrect or missing. Can't obtain user details");
    	}
    	// get user details from the users table
    	$my_details = $this->mydb->getrow($this->uid_field,$userid,$this->u_table);
    	$this->user_det = $my_details;
    	return $this->user_det;
    }

    /**
     * Function to confirm if user is still Logged in or not
     *
     * This function checks if user is still logged on or has looged our
     * or whether or not his session has expired.
     *
     * @access Public
     * @param mixed UserID
     * @param mixed session hours (Optional) like 1, 2, 3, etc
     * @return True
     */
    function confirmLogin($userid,$sstime=''){
    	if ($userid =="") {
    		$this->displayerror("The user ID suplied seems incorrect or missing. Cannot confirm Login");
    	}
    	// set session time or use default
    	if ($sstime == "") {
    		$ttts = 8;
    	}
    	else {
    		$ttts = intval($sstime);
    	}
    	// we are now set
    	// first we check the user logged in record
    	$first = $this->mydb->getrow($this->uid_field,$userid,$this->user_log);
    	if ($first[$this->u_field] =="" || $first[$this->tim_field] < ( time() - 60*60*$ttts)) {
    		// user not logged in or session has expired
    		return false;
    	}
    	else {
    		// user still logged in
    		return true;
    	}

    }
    
 /**
     * Function to log user actions into the user activity's table
     *
     * This function logs into the database every activity carried out by a user
     * once logged in.
     *
     * @access Public
     * @param string activity details
     * @param string name of the activity table in the database
     * @return void
     */
    function logAction($activity,$table){
    	if ($activity =="" || $table =="") {
    		$this->displayerror("Please check that all required parameters are supplied.");
    	}
    	// make sure we have the user ID
    	if ($this->userID =="") {
    		// GET IT FROM SEESION
    		$theUserid = $_SESSION["UID"];
    	}
    	else {
    		$theUserid = $this->userID;
    	}
    	// we are now set
    	//datetime
		$acttime = $this->prep_date(time());
		// prepare datas
		$activity = mysql_real_escape_string($activity);
		$table = $this->doclean($table);
		// add activity record
		$vflds = "uid,,actname,,actdate";
		$vvlus = "'$theUserid',,'$activity',,'$acttime'";
		// add record
    	$this->mydb->addlist($vflds,$vvlus,$table,0);
    }


	 /**
	  * Function to prapare date for inserting to database
	  * The $dtval must be in the format time
	  *
	  * @access Private
	  * @param timestamp $dtval
	  * @return datetime $dbdate, YYYY-MM-DD HH:MM:SS
	  */
	 function prep_date($dtval){

	 	$dbdate = date("Y-m-d H:i:s", $dtval);

	 	$this->db_date = $dbdate;
	 	return $this->db_date;
	 }

	/**
	 * Function to clean submitted data for database input
	 *
	 * The duty of this function is to
	 * remove any traces of ';' '..' '--' or '?' "%nn%' to prevent sql injection.
	 */
	function doclean($samy)
	 {

	     $samyb = trim($samy);
	     $samyb = ereg_replace("%([A-Za-z]+)%","",$samyb);
	     $samyb = str_replace("=","",$samyb);
	     $samyb = str_replace("--","",$samyb);
	     $samyb = str_replace(";","",$samyb);
	     $samyb = str_replace("..","",$samyb);
	     $samyb = str_replace("?","",$samyb);
	     $samyb = str_replace(" OR ","",$samyb);
	     $samyb = str_replace(" LIKE ","",$samyb);

	    return $samyb;

	 }
	 
	 /**
  * Function name: docleanb
  * This function escapes the strings
  */
 function docleanb($sanm)
 {

     $samb = trim($sanm);
     $samb = ereg_replace("%([A-Za-z]+)%","",$samb);
     $samb = str_replace("--","",$samb);
     $samb = str_replace(";","",$samb);
     $samb = str_replace("..","",$samb);
     $samb = str_replace(" OR ","",$samb);
     $samb = str_replace(" LIKE ","",$samb);
   if (!get_magic_quotes_gpc()){
     	$samb = addslashes(cleana($samb));
     }

    return  $samb;
 }
	 /**
	  * Function to display fine error messages if it occurs
	  * in the process of doing anything in this program.
	  *
	  * @name showmyerror
	  * @param $terror, error message to display
	  * @return void
	  */
	 function displayerror($terror){
	 		// prepare the head
	 	   $myerr = <<<HEREDOC
	<html><head><title>Error!</title>
	<style type="text/css">
	.fm {background-color: #452F74; border-color: teal; border-style: outset; border: 1px; width: 450px}
	.fa {color: white; font-weight: bold; font-size: 14px}
	.fb {background-color: #FFFFFF; color: red; font-size: 14px; font-weight: bold;}
	</style>
	</head>
	<body style="font-family: sans-serif, verdana, arial; font-size: 11px">
	<br><br>
	<table align="center" class="fm">
	      <tr><td align="center" class="fa">INFORMATION</td> </tr>
	      <tr>
	        <td align="center" class="fb"><br><br><h3>Sorry!</h3> <br>  $terror <br><br><br>
			Click <b onclick="Javascript:history.back(-1);" style="color: blue; cursor: pointer">GO BACK</b> to return
	        to the previous page.<br><br></td>
	      </tr>
	 </table>
	 </body>
HEREDOC;

	 echo($myerr);
	 exit();
	 }
}
?>