<?php
/**
 * ************************************************************************************
 * 	               SAMMYSQL PHP CLASS 	v2.0										  *
 * 			(c) 2014	 Ezekiel Aliyu                    							  *
 * ************************************************************************************ 
 */
/**
 * A simple but powerful general purpose MySql Class. <br />
 *
 * This is a very <b>simple</b> but <b>powerful</b> general purpose MySql Class
 * which can be used to add, update, delete, fetch records from a MySql database <br />
 * It can also be used to get total records from a table and when fetching records, <br />
 * the result can be arranged in any order depending on you <br />
 * It has now been updated to allow searching a database and most of the functions 
 * has been expanded to do much more.
 * You can actually do much more with this very simple and powerful Mysql Class. <br />
 * EXAMPLES:
 *  <code>
 * $yourclass = new SamMysql($dcon); // Takes your database connection link as parameter
 * </code>
 *
 * 
 * @version 2.00
 * @package SamMysql_Class
 * @name SamMysql
 */
class SamMysql {

 /**
  * The connection link to database
  * @var object
  * @access private
  */
    var $dcon;

 /**
  * The record to get from database
  * @var string
  * @access private
  */
    var $record;
/**
  * The grade point record to get from database
  * @var string
  * @access private
  */
    var $gp_record;

/**
  * Stores error messages
  * @var string
  * @access private
  */
    var $err;
    
 /**
  * Stores error messages
  * @var string
  * @access private
  */
    var $lastid;

/**
  * The results row from table query
  * @var array
  * @access private
  */
    var $mresult = array();

/**
  * The results row from getrow_two database query
  * @var array
  * @access private
  */
    var $mresult_two = array();

/**
  * The resouce id for the results from 'getallresult' database query
  * @var resource
  * @access private
  */
    var $allresult;

/**
  * The resouce id for the table rows results from 'getallrow' query
  * @var resource
  * @access private
  */
    var $allrows;

/**
  * The resouce id for the table rows results from 'getallrow_two' query
  * @var string
  * @access private
  */
    var $allrows_two;

/**
  * The total number of records in a particular table from database
  * @var int
  * @access private
  */
    var $numvals;

/**
  * The total sum of a particular field in a particular table from database
  * @var int
  * @access private
  */
    var $sumvals;

/**
  * The search result resource id holding the results
  * from a query on particular table from database
  * @var resource
  * @access private
  */
    var $s_results;

/**
  * The image result resource id holding the results
  * from a query on particular table from database
  * @var resource
  * @access private
  */
    var $imgg;

/**
 * The Class Constructor
 *
 * Accepts your database connection object.
 * @param  object, database connection object $dbcon
 * @return database connection link to use for all queries
 */
	function SamMysql($dbcon)
	{
		// get connection to database
		$this->dcon = $dbcon;
	}

	/**
	 * Function to get a single value data from the database
	 *
	 * It will get a single value data from a table if the field ($where), <br />
	 * field value ($whereval), the table name ($table) and the field_to_get_its_value ($toget)<br />
	 * are provided. If not provided, it will triger error <br />
	 * EXAMPLES:
	 * <code>
	 * $where = "field_to_search";
	 * $whereval = "value_of_field_to_search";
	 * $toget = "name_of_field_to_get";
	 * $table = "name_of_table";
	 * // now get your value
	 * $yourclass = new SamMysql($database_connection);
	 * $value_to_get = $yourclass->getval($where,$whereval,$table,$toget);
	 * </code>
	 *
	 * @access Public
	 * @param  string field_to_search
	 * @param  mixed value_of_field_to_search
	 * @param  string name_of_table
	 * @param  string name_of_field_to_get
	 * @return $record, the value of the field ($toget) from the database.
	 */
	function getval($where,$whereval,$table,$toget){
		if ($where == "" || $whereval =="" || $table =="" || $toget =="") {
			$this->showerror("There is an error with your Getval request, please try again");
		}
		// else no error, so continue process
		// be sure the field given and the value is single, no commas
		if ($this->havecomma($where) || $this->havecomma($whereval) || $this->havecomma($toget)) {
			// error occured
			$this->showerror("There is an error with your '$where' or '$whereval' values");
		}
		// get value from database
		$tquery = "SELECT ". $toget . " FROM ". $table ." WHERE ". $where ."='$whereval'";
		$mres = mysqli_query($this->dcon, $tquery);
		if (!$mres){
			$this->showerror("Could not get record from database");
		}
		$tresult = mysqli_fetch_array($mres);
		$this->record = $tresult[$toget];
		return $this->record;
	}


/**
	 * Function to get a single value data from the database
	 *
	 * It will get a single value data from a table if the field ($where), <br />
	 * field value ($whereval), the table name ($table) and the field_to_get_its_value ($toget)<br />
	 * are provided. If not provided, it will triger error <br />
	 * EXAMPLES:
	 * <code>
	 * $where = "field_to_search";
	 * $whereval = "value_of_field_to_search";
	 * $toget = "name_of_field_to_get";
	 * $table = "name_of_table";
	 * // now get your value
	 * $yourclass = new SamMysql($database_connection);
	 * $value_to_get = $yourclass->getval($where,$whereval,$table,$toget);
	 * </code>
	 *
	 * @access Public
	 * @param  string field_to_search
	 * @param  mixed value_of_field_to_search
	 * @param  string name_of_table
	 * @param  string name_of_field_to_get
	 * @return $record, the value of the field ($toget) from the database.
	 */
	function getvalb($where,$whereval,$wherea,$wherevala,$whereb,$wherevalb,$table,$toget){
		if ($where == "" || $whereval =="" || $table =="" || $toget =="") {
			$this->showerror("There is an error with your Getval request, please try again");
		}
        // make the second check
		if (($wherea =="" && $wherevala !="") || ($wherea !="" && $wherevala =="")) {
		   $this->showerror("There is an error with your optional fields, please try again");
		}
        // make the third check
		if (($whereb =="" && $wherevalb !="") || ($whereb !="" && $wherevalb =="")) {
		   $this->showerror("There is an error with your optional fields, please try again");
		}
		// else no error, so continue process
		// be sure the field given and the value is single, no commas
		if ($this->havecomma($where) || $this->havecomma($whereval) || $this->havecomma($toget)) {
			// error occured
			$this->showerror("There is an error with your '$where' or '$whereval' values");
		}
		// get value from database
         $tquery="";
        if($wherea != "" && $wherevala != ""){
		  $tquery = "SELECT ". $toget . " FROM ". $table ." WHERE ". $where ."='$whereval' and ". $wherea."='$wherevala'";
        }
        else if($whereb != "" && $wherevalb != ""){
          $tquery = "SELECT ". $toget . " FROM ". $table ." WHERE ". $where ."='$whereval' and ". $wherea."='$wherevala' and ". $whereb."='$wherevalb'";
        }
        else{
          $tquery = "SELECT ". $toget . " FROM ". $table ." WHERE ". $where ."='$whereval'";
        }
		$mres = mysqli_query($this->dcon, $tquery);
		if (!$mres){
			$this->showerror("Could not get record from database");
		}
		$tresult = mysqli_fetch_array($mres);
		$this->record = $tresult[$toget];
		return $this->record;
	}

/**
     * Function to LOCK SOME SET OF TABLES in the database
     * Special Function for use
     * EXAMPLES:
     * <code>
     * $tables = "table1 read, table2 write";
     *
     * // now get your value
     * $yourclass = new SamMysql($database_connection);
     * $yourclass->locktables($tables);
     * </code>
     *
     * @access Public
     * @param  string name_of_table and lock types
     * @return True, if successful.
     */
    function locktables($tables_and_locks){
        if ($tables_and_locks =="") {
            $this->showerror("There is an error with your Locktables Query, please try again");
        }
        // else no error, so continue process
        // Lock selected tables in database
        $tquery = "LOCK TABLES ". $tables_and_locks;
        $mres = mysqli_query($this->dcon, $tquery);
        if (!$mres){
            $this->showerror("Could not lock the selected tables in the database");
        }
        
        return true;
    }
 /**
     * Function to UNLOCK SET OF TABLES in the database
     * Special Function for use
     * EXAMPLES:
     * <code>
     * 
     * $yourclass = new SamMysql($database_connection);
     * $yourclass->unlocktables();
     * </code>
     *
     * @access Public
     * 
     * @return True, if successful.
     */
    function unlocktables(){
        // unlock previously locked tables in database
        $tquery = "UNLOCK TABLES";
        $mres = mysqli_query($this->dcon,$tquery);
        if (!$mres){
            $this->showerror("Could not unlock the tables in the database");
        }
        
        return true;
    }
    
 /**
     * Function to get grade point value data from the database
     * Special Function for use
     * It will get grade point value data from a table if the field ($where), <br />
     * field value ($whereval), the table name ($table) and the field_to_get_its_value ($toget)<br />
     * are provided. If not provided, it will triger error <br />
     * EXAMPLES:
     * <code>
     * $minval = "minimum value";
     * $maxval = "maximum value"; 
     * $checkval = "value_to_check";
     * $toget = "name_of_field_to_get";
     * $table = "name_of_table";
     * // now get your value
     * $yourclass = new SamMysql($database_connection);
     * $value_to_get = $yourclass->getval($where1,where2,$whereval,$table,$toget);
     * </code>
     *
     * @access Public
     * @param  string min_value
     * @param  string max_value
     * @param  mixed value_to_check
     * @param  string name_of_table
     * @param  string name_of_field_to_get
     * @return $record, the value of the field ($toget) from the database.
     */
    function getPoint($minval,$maxval,$checkval,$table,$toget){
        if ($minval == "" || $maxval == "" || $checkval =="" || $table =="" || $toget =="") {
            $this->showerror("There is an error with your GetPoint request, please try again");
        }
        // else no error, so continue process
        // be sure the field given and the value is single, no commas
        if ($this->havecomma($minval) || $this->havecomma($maxval) || $this->havecomma($checkval) || $this->havecomma($toget)) {
            // error occured
            $this->showerror("There is an error with your '$minval' or '$checkval' values");
        }
        // get value from database
        $tquery = "SELECT ". $toget . " FROM ". $table ." WHERE ". $minval ."<='$checkval' AND ". $maxval .">='$checkval'";
        $mres = mysqli_query($this->dcon, $tquery);
        if (!$mres){
            $this->showerror("Could not get record from database");
        }
        $tresult = mysqli_fetch_array($mres);
        $this->gp_record = $tresult[$toget];
        return $this->gp_record;
    }
	/**
	 * Function to get a single row of values from a table in the database.
	 *
	 * This should also be used if you need to get more than a field <br />
	 * from a single row in the table The '$where' should be a unique 'id' or <br />
	 * 'value' in your table fields. It must not be able to be duplicated. Possibly
	 * the 'primary key'. <br />
	 * EXAMPLES:
	 * <code>
	 * $where = "field_to_search"; // possibly a unique field
	 * $whereval = "value_of_field_to_search";
	 * $table = "name_of_table";
	 * // now get your value
	 * $yourclass = new SamMysql($database_connection);
	 * $array_value_to_get = $yourclass->getrow($where,$whereval,$table);
	 * </code>
	 *
	 * @access Public
	 * @param string field_to_search
	 * @param mixed value_of_field_to_search
	 * @param string name_of_table
	 * @return $mresult, an array of the field row.
	 *
	 */
	function getrow($where,$whereval,$table){
		// no empty parameters
		if ($where == "" || $whereval =="" || $table =="") {
			$this->showerror("There is an error with your request, please try again");
		}
		//  should not contain more than one values
		if ($this->havecomma($where) || $this->havecomma($whereval) || $this->havecomma($table)) {
			// error occured
			$this->showerror("There is an error with your values, please try again");
		}
		$myqry = "SELECT * FROM " . $table . " WHERE ". $where ."='$whereval'";
		$mqres = mysqli_query($this->dcon, $myqry);
		if (!$mqres){
			$this->showerror("Could not get record from database");
		}
		$mrest = mysqli_fetch_array($mqres);
		$this->mresult = $mrest;
		return $this->mresult;
	}

	/**
	 * Function to get a single row of values from a table in the database when two different field matches are needed.
	 *
	 * (NEW FUNCTION)
	 * This should also be used if you need to get more than a field <br />
	 * from a single row in the table The '$where1' should be a unique 'id' or <br />
	 * 'value' in your table fields and '$where2' need not be unique.
	 * 'where1' must not be able to be duplicated, Possibly the 'primary key'. <br />
	 * but '$where2' may be.
	 * EXAMPLES:
	 * <code>
	 * $where1 = "first_field_to_search"; // possibly a unique field
	 * $whereval1 = "value_of_field_to_search";
	 * $where2 = "second_field_to_search";
	 * $whereval2 = "value_of_Second_field_to_search";
	 * $table = "name_of_table";
	 * // now get your value
	 * $yourclass = new SamMysql($database_connection);
	 * $array_value_to_get = $yourclass->getrow($where1,$whereval1,$where2,$whereval2,$table);
	 * </code>
	 *
	 * @access Public
	 * @param string first_field_to_search
	 * @param mixed value_of_first_field_to_search
	 * @param string second_field_to_search
	 * @param mixed value_of_second_field_to_search
	 * @param string name_of_table
	 * @return $mresult_two, an array of the field row.
	 *
	 */
	function getrow_two($where1,$whereval1,$where2,$whereval2,$table){
		// no empty parameters
		if ($where1 == "" || $whereval1 =="" || $where2 == "" || $whereval2 =="" || $table =="") {
			$this->showerror("There is an error with your getrow_two request, pls try again");
		}
		//  should not contain more than one values
		if ($this->havecomma($where1) || $this->havecomma($whereval1) || $this->havecomma($where2) || $this->havecomma($whereval2) || $this->havecomma($table)) {
			// error occured
			$this->showerror("There is an error with your values, please try again");
		}
		$myqry = "SELECT * FROM " . $table . " WHERE ". $where1 ."='$whereval1' AND ". $where2 ."='$whereval2'";
		$mqres = mysqli_query($this->dcon ,$myqry);
		if (!$mqres){
			$this->showerror("Could not get record from database");
		}
		$mrest = mysqli_fetch_array($mqres);
		$this->mresult_two = $mrest;
		return $this->mresult_two;
	}



	/**
	 * Function to get all rows of values from a table in the database.
	 *
	 * This should only be used if you need to get more than one row of <br />
	 * values from your table. The '$where' should not be a unique 'id' or <br />
	 * 'value' in your table fields. It must not be the 'primary key'. The result can <br />
	 * be sorted by $orderby in $order (A for ascending, D for descending) orders <br />
	 * You can now specified how many rows of result to be returned <br />
	 * EXAMPLES:
	 * <code>
	 *  $yourclass = new SamMysql($database_connection);
	 * 	$table = "Table_name";
	 *  $where = "my_date";
	 *  $whereval = "2006-05-20";
	 *  $orderby = "my_date"; // orderby $where [Optional parameter]
	 *  $order = "d" // Descending order, you can type "d" or "D", "a" or "A"
	 *  // Note: if $orderby and $order are not provided, result array will not be sorted
	 *  // however, if $orderby is provided without $order, the default is "d", descending
	 *  // order.
	 *  $offset = ""; // Rows to skip
	 *  $total = "5" // return only five rows
	 *  $resultidentifier = $myclass->getallrow($where,$whereval,$table,$where,"d","",5);
	 *    // loop through the result array to get out your values
	 *    // in the order specified by $orderby
	 * 		while ($result = @mysql_fetch_array($resultidentifier))
	 * 			{
	 *            // prepare your table
	 * 			echo "<td>$result['field0']</td> <td>$result['field1']</td>
	 *
	 *             // etc
	 *          }
	 *
	 * </code>
	 *
	 * @access Public
	 * @param string field_to_search
	 * @param mixed value_of_field_to_search
	 * @param string name_of_table
	 * @param string field_to_order_by [optional]
	 * @param string order, Ascending (A) or Descending(D) [optional]
	 * @param mixed offset (rows to skip)[Optional]
	 * @param mixed Total_rows_to_return [Optional]
	 * @return $allrows, a resouce identifier for the result
	 *
	 */
	function getallrow($where,$whereval,$table,$orderby="",$order="",$offset="",$total=""){
		// no empty parameters
		if ($where == "" || $whereval =="" || $table =="") {
			$this->showerror("There is an error with your request, pls try again");
		}
		//  should not contain more than one values
		if ($this->havecomma($where) || $this->havecomma($whereval) || $this->havecomma($table)) {
			// error occured
			$this->showerror("There is an error with your values, please try again");
		}
		if ($orderby !="" && $order !="") {
		  $order = strtoupper($order);
			if ($order =="A") {
				$AD = "ASC";
			}
			elseif ($order =="D") {
				$AD = "DESC";
			}
			else {
				$this->showerror("Please, " . $order ." can only be A or D. Please correct your mistake.");
			}
			// do we have $total and/or $offset values
			if ($offset =="" && $total =="") {
				$myqry = "SELECT * FROM " . $table . " WHERE ". $where ."='$whereval' ORDER BY ". $orderby . " " . $AD;
			}
			elseif ($total !="" && $offset !="") {

				$myqry = "SELECT * FROM " . $table . " WHERE ". $where ."='$whereval' ORDER BY ". $orderby . " " . $AD ." LIMIT " . $offset .",". $total;
			}
			else{
				$myqry = "SELECT * FROM " . $table . " WHERE ". $where ."='$whereval' ORDER BY ". $orderby . " " . $AD ." LIMIT ". $total;
			}

		}
		elseif ($orderby !="" && $order=="") {
			$AD = "DESC";
			if ($offset =="" && $total =="") {
			     $myqry = "SELECT * FROM " . $table . " WHERE ". $where ."='$whereval' ORDER BY ". $orderby . " " . $AD;
			}
			elseif ($total !="" && $offset !="") {

				$myqry = "SELECT * FROM " . $table . " WHERE ". $where ."='$whereval' ORDER BY ". $orderby . " " . $AD ." LIMIT " . $offset .",". $total;
			}
			else{
				$myqry = "SELECT * FROM " . $table . " WHERE ". $where ."='$whereval' ORDER BY ". $orderby . " " . $AD ." LIMIT ". $total;
			}
		}
		else {
			if ($offset =="" && $total =="") {
			    $myqry = "SELECT * FROM " . $table . " WHERE ". $where ."='$whereval'";
			}
			elseif ($total !="" && $offset !="") {
			    $myqry = "SELECT * FROM " . $table . " WHERE ". $where ."='$whereval' LIMIT " . $offset .",".$total;
			}
			else {
				$myqry = "SELECT * FROM " . $table . " WHERE ". $where ."='$whereval' LIMIT ". $total;
			}
		}
		 $mqres = mysqli_query( $this->dcon,$myqry);
		if (!$mqres){
			$this->showerror("Could not get record from database");
		}
		$this->allrows = $mqres;
		return $this->allrows;
	}

	/**
	 * Function to get all rows of values from a table in the database when two different field matches are needed.
	 *
	 * This should only be used if you need to get more than one row of
	 * values from your table and two WHERES '$where1' AND '$where2' are
	 * provided and have 'values' in your table fields. It can be sorted by $orderby and
	 * arranged in $order (A for ascending , D for descending) orders.<br />
	 * You can also specify how many rows to skip and how many rows to return <br />
	 * EXAMPLES:
	 * <code>
	 *   $yourclass = new SamMysql($database_connection);
	 * 			  $table = "Table_name";
	 *            $where1 = "my_date";
	 *            $whereval1 = "2006-05-20";
	 *            $where2 = "your_name";
	 *            $whereval2 = "The_name";
	 *            $orderby = "one_field_value";
	 * 			  $resultidentifier = $yourclass->getallrow_two($where1,$whereval1,$where2,$whereval2,$table,$orderby,"A");
	 * 			    // loop through the result array to get out your values
	 *              // in the order specified by $orderby
	 *            while ($result = @mysql_fetch_array(resultidentifier))
	 * 				{
	 *            // prepare your table
	 * 				echo "<td>$result['field0']</td> <td>$result['field1']</td>
	 *                // etc
	 *              }
	 *
	 *  </code>
	 * @access Public
	 * @param string first_field_to_search
	 * @param mixed value_of_first_field_to_search
	 * @param string second_field_to_search
	 * @param mixed value_of_second_field_to_search
	 * @param string name_of_table
	 * @param string field_to_order_by [optional]
	 * @param string order [Ascending or Descending]
	 * @param mixed offset (rows to skip)[Optional]
	 * @param mixed Total_rows_to_return [Optional]
	 * @return $allrows_two, a resource identifier for the result
	 *
	 */
	function getallrow_two($where1,$whereval1,$where2,$whereval2,$table,$orderby="",$order="",$offset="",$total=""){
		// no empty parameters
		if ($where1 == "" || $whereval1 =="" || $where2 == "" || $whereval2 =="" || $table =="") {
			$this->showerror("There is an error with your request, pls try again");
		}
		//  should not contain more than one values
		if ($this->havecomma($where1) || $this->havecomma($whereval1) || $this->havecomma($where2) || $this->havecomma($whereval2) ||  $this->havecomma($table)) {
			// error occured
			$this->showerror("There is an error with your values, please try again");
		}
		if ($orderby !="" && $order !="") {
		  $order = strtoupper($order);
			if ($order =="A") {
				$AD = "ASC";
			}
			elseif ($order =="D") {
				$AD = "DESC";
			}
			else {
				$this->showerror("Please, " . $order ." can only be A or D. Please correct your mistake.");
			}
			// do we have $total and/or $offset values
			  if ($offset =="" && $total =="") {
		         $myqry = "SELECT * FROM " . $table . " WHERE ". $where1 ."='$whereval1' AND ". $where2 ."='$whereval2' ORDER BY ". $orderby . " " . $AD;
			  }
			  else {
				if ($offset == "") {
					$ofs =0;
				}
				else {
					$ofs = $offset;
				}
			      $myqry = "SELECT * FROM " . $table . " WHERE ". $where1 ."='$whereval1' AND ". $where2 ."='$whereval2' ORDER BY ". $orderby . " " . $AD ." LIMIT " . $ofs .",".$total;
			  }
		}
		elseif ($orderby !="" && $order=="") {
				$AD = "DESC";
				if ($offset =="" && $total =="") {
			     $myqry = "SELECT * FROM " . $table . " WHERE ". $where1 ."='$whereval1' AND ". $where2 ."='$whereval2' ORDER BY ". $orderby . " " . $AD;
				}
				else {
					if ($offset == "") {
						$ofs =0;
					}
					else {
						$ofs = $offset;
					}
				  $myqry = "SELECT * FROM " . $table . " WHERE ". $where1 ."='$whereval1' AND ". $where2 ."='$whereval2' ORDER BY ". $orderby . " " . $AD ." LIMIT " . $ofs .",".$total;
			  }
		}
		else {
		    if ($offset =="" && $total =="") {
		      $myqry = "SELECT * FROM " . $table . " WHERE ". $where1 ."='$whereval1' AND ". $where2 ."='$whereval2'";
		    }
		    else {
					if ($offset == "") {
						$ofs =0;
					}
					else {
						$ofs = $offset;
					}
			   $myqry = "SELECT * FROM " . $table . " WHERE ". $where1 ."='$whereval1' AND ". $where2 ."='$whereval2' LIMIT " . $ofs .",".$total;
		    }
		}
		$mqres = mysqli_query($this->dcon, $myqry);
		if (!$mqres){
			$this->showerror("Could not get record from database");
		}
		$this->allrows_two = $mqres;
		return $this->allrows_two;
	}



	
	/**
	 * Function to get all rows of values from a table in the database when three different field matches are needed.
	 *
	 * This should only be used if you need to get more than one row of
	 * values from your table and two WHERES '$where1' AND '$where2' are
	 * provided and have 'values' in your table fields. It can be sorted by $orderby and
	 * arranged in $order (A for ascending , D for descending) orders.<br />
	 * You can also specify how many rows to skip and how many rows to return <br />
	 * EXAMPLES:
	 * <code>
	 *   $yourclass = new SamMysql($database_connection);
	 * 			  $table = "Table_name";
	 *            $where1 = "my_date";
	 *            $whereval1 = "2006-05-20";
	 *            $where2 = "your_name";
	 *            $whereval2 = "The_name";
	 *            $orderby = "one_field_value";
	 * 			  $resultidentifier = $yourclass->getallrow_two($where1,$whereval1,$where2,$whereval2,$table,$orderby,"A");
	 * 			    // loop through the result array to get out your values
	 *              // in the order specified by $orderby
	 *            while ($result = @mysql_fetch_array(resultidentifier))
	 * 				{
	 *            // prepare your table
	 * 				echo "<td>$result['field0']</td> <td>$result['field1']</td>
	 *                // etc
	 *              }
	 *
	 *  </code>
	 * @access Public
	 * @param string first_field_to_search
	 * @param mixed value_of_first_field_to_search
	 * @param string second_field_to_search
	 * @param mixed value_of_second_field_to_search
	 * @param string name_of_table
	 * @param string field_to_order_by [optional]
	 * @param string order [Ascending or Descending]
	 * @param mixed offset (rows to skip)[Optional]
	 * @param mixed Total_rows_to_return [Optional]
	 * @return $allrows_two, a resource identifier for the result
	 *
	 */
	function getallrow_three($where1,$whereval1,$where2,$whereval2,$where3,$whereval3,$table,$orderby="",$order="",$offset="",$total=""){
		// no empty parameters
		if ($where1 == "" || $whereval1 =="" || $where2 == "" || $whereval2 =="" ||$where3 == "" || $whereval3 =="" || $table =="") {
			$this->showerror("There is an error with your request, pls try again");
		}
		//  should not contain more than one values
		if ($this->havecomma($where1) || $this->havecomma($whereval1) || $this->havecomma($where2) || $this->havecomma($whereval2) || $this->havecomma($where3) || $this->havecomma($whereval3) ||  $this->havecomma($table)) {
			// error occured
			$this->showerror("There is an error with your values, please try again");
		}
		if ($orderby !="" && $order !="") {
		  $order = strtoupper($order);
			if ($order =="A") {
				$AD = "ASC";
			}
			elseif ($order =="D") {
				$AD = "DESC";
			}
			else {
				$this->showerror("Please, " . $order ." can only be A or D. Please correct your mistake.");
			}
			// do we have $total and/or $offset values
			  if ($offset =="" && $total =="") {
		         $myqry = "SELECT * FROM " . $table . " WHERE ". $where1 ."='$whereval1' AND ". $where2 ."='$whereval2' AND ". $where3 ."='$whereval3' ORDER BY ". $orderby . " " . $AD;
			  }
			  else {
				if ($offset == "") {
					$ofs =0;
				}
				else {
					$ofs = $offset;
				}
			      $myqry = "SELECT * FROM " . $table . " WHERE ". $where1 ."='$whereval1' AND ". $where2 ."='$whereval2' AND ". $where3 ."='$whereval3' ORDER BY ". $orderby . " " . $AD ." LIMIT " . $ofs .",".$total;
			  }
		}
		elseif ($orderby !="" && $order=="") {
				$AD = "DESC";
				if ($offset =="" && $total =="") {
			     $myqry = "SELECT * FROM " . $table . " WHERE ". $where1 ."='$whereval1' AND ". $where2 ."='$whereval2' AND ". $where3 ."='$whereval3' ORDER BY ". $orderby . " " . $AD;
				}
				else {
					if ($offset == "") {
						$ofs =0;
					}
					else {
						$ofs = $offset;
					}
				  $myqry = "SELECT * FROM " . $table . " WHERE ". $where1 ."='$whereval1' AND ". $where2 ."='$whereval2' AND ". $where3 ."='$whereval3'  ORDER BY ". $orderby . " " . $AD ." LIMIT " . $ofs .",".$total;
			  }
		}
		else {
		    if ($offset =="" && $total =="") {
		      $myqry = "SELECT * FROM " . $table . " WHERE ". $where1 ."='$whereval1' AND ". $where2 ."='$whereval2' AND ". $where3 ."='$whereval3'";
		    }
		    else {
					if ($offset == "") {
						$ofs =0;
					}
					else {
						$ofs = $offset;
					}
			   $myqry = "SELECT * FROM " . $table . " WHERE ". $where1 ."='$whereval1' AND ". $where2 ."='$whereval2' AND ". $where3 ."='$whereval3' LIMIT " . $ofs .",".$total;
		    }
		}
		$mqres = mysqli_query($this->dcon, $myqry);
		if (!$mqres){
			$this->showerror("Could not get record from database");
		}
		$this->allrows_two = $mqres;
		return $this->allrows_two;
	}





	/**
	 * Function to Add records to the database <br />
	 *
	 * It takes the list of fields ($tfields) and values ($tvalues) to be added
	 * and the table name ($table) and the field number ($n) to prevent duplicate
	 * entry into the database as parameters.<br />
	 * EXAMPLES:
	 * <code>
	 *     $value1 = "Some values";
	 *     $value2 = "Some other datas";
	 *     $value3 = "Some more values";
	 *     $table = "Name_of_table";
	 *    // prepare your datas ready for the class
	 *     $fields ="filed1,,field2,,field3";
	 *   // fields names separated by 'double comma'
	 *     $values ="'$value1',,'$value2',,'$value3'";
	 *   // values are quoted with single quotes ' '
	 *   // and also separated by 'double commas'
	 *     $n = 2;
	 *   // uses the second field/value I provided to check for duplicate entry
	 *   // Note: if $n is not provided, it uses the first value in your $tvalues
	 *   // to check for any duplicate entry
	 *   // To prevent checking for duplicates, set $n=0
	 *   $yourclass = new SamMysql($database_connection);
	 *   // add entries to database
	 *   $yourclass->addlist($fields,$values,$table,$n)
	 * </code>
	 * @access Public
	 * @param string list_of_fields separated by double commas
	 * @param mixed list_of_values_variables in single quotes separated by double commas
	 * @param string name_of_table
	 * @param int supplied field_name number or value number on the list to check for duplicate entry
	 * @return TRUE, if successful and FALSE if otherwise
	 *
	 */
		function addlist($tfields,$tvalues,$table,$n=""){

		if ($tfields=="" || $tvalues=="" || $table=="") {
			$this->showerror("There is an error with your request, pls try again");
		}
		// else no error, so continue process
		// Check if need to prevent duplicate entry
		if ($n == 0) {
			// no checking so aadd the records
			// check if fields equals to values
			if ($this->havecomma($tfields) || $this->havecomma($tvalues)) {
				// make the value arrays
				$afield = $this->makearray($tfields);
				$avalue = $this->makearray($tvalues);
				// count the values in each arrays
		  	 if (count($afield) != count($avalue)) {
		  		// arrays does not match, show error
		  		$this->showerror("The values in your \'fields\' does not match that of \'values\'. Please check your fields and values");
		  	 }

			}
			// add values to the database
			$tfields = $this->clncomma($tfields);
			$tvalues = $this->clncomma($tvalues);
			$tquery = "INSERT INTO ". $table . " (". $tfields .") VALUES (" . $tvalues .")";
			$mres = mysqli_query($this->dcon, $tquery);
			if (!$mres){
			  echo "Error Number :". mysqli_errno($this->dcon) ." -- Error :". mysqli_error($this->dcon);
				$this->showerror("Could not add record to the database");
			}
		}
		else {
		   if (!($this->rec_exist($tfields,$tvalues,$table,$n))) {
			// check if fields equals to values
			if ($this->havecomma($tfields) || $this->havecomma($tvalues)) {
				// make the value arrays
				$afield = $this->makearray($tfields);
				$avalue = $this->makearray($tvalues);
				// count the values in each arrays
		  	 if (count($afield) != count($avalue)) {
		  		// arrays does not match, show error
		  		$this->showerror("The values in your \'fields\' does not match that of \'values\'. Please check your fields and values");
		  	 }

			}
			// add values to the database
			$tfields = $this->clncomma($tfields);
			$tvalues = $this->clncomma($tvalues);
			$tquery = "INSERT INTO ". $table . " (". $tfields .") VALUES (" . $tvalues .")";
			$mres = mysqli_query($this->dcon, $tquery);
			if (!$mres){
            echo "Error Number :". mysqli_errno($this->dcon) ." -- Error :". mysqli_error($this->dcon);
				$this->showerror("Could not add record to the database ");
			}
		}
		else {
			$this->showerror("The record already exists in Database, Please edit instead");
		}

	   }
		// else all went well. return true
		return true;
	}
        
        
        /**
         * Function to return th Id of the last inserted data from the mysql table
         * @return type int
         */
        
        
        function getLastid(){
            $this->lastid = mysqli_insert_id($this->dcon);
            return $this->lastid;
        }


	/**
	 * The function for updating the database records after editing them <br />
	 *
	 * It takes the list of fields and values as parameters <br />
	 * The fields to update ($ufield), the values to update ($uvalue),
	 * the table to update ($table), the where_field ($where) and the where_value
	 * ($whereval)  or the optional_second_where_field ($where) and the optional_second_where_value
	 * ($whereval) of the record to be updated. <br />
	 * EXAMPLES:
	 * <code>
	 *  $value1 = "Some edited values";
	 *  $value2 = "Some other edited datas";
	 *  $value3 = "Some more edited values";
	 *  $table = "Name_of_table";
	 *  $where = "field_to_search";
	 *  $whereval = "value_of_field_to_search";
	 *  $whereb = "second_field_to_search"; // optional
	 *  $wherevalb = "value_of_second_field_to_search"; // optional
	 *  $ufield ="field1,,field2,,field3"; // etc
	 *  $uvalue ="$value1,,$value2,,$value3"; // Note, no single quotes here
	 *  // initiate the class
	 *  $yourclass = new SamMysql($database_connection);
	 *  // update your table
	 *  $yourclass->updateval($ufield,$uvalue,$table,$where,$whereval);
	 * </code>
	 * @access Public
	 * @param string list_of_fields separated by double commas
	 * @param mixed list_of_values_variables separated by double commas
	 * @param string name_of_table
	 * @param string field_to_search for updating
	 * @param mixed value_of_field_to_search for updating
	 * @param string second_field_to_search for updating
	 * @param mixed value_of_second_field_to_search for updating
	 * @return TRUE, If operation is successful
	 */
	function updateval($ufield,$uvalue,$table,$where,$whereval,$whereb="",$wherevalb=""){

		if ($ufield =="" || $uvalue=="" || $table=="" || $where=="" || $whereval=="") {
			$this->showerror("There is an error with your Update request, please try again");
		}
		// make the second check
		if (($whereb =="" && $wherevalb !="") || ($whereb !="" && $wherevalb =="")) {
		   $this->showerror("There is an error with your optional fields, please try again");
		}
		// else no error, so continue process
		// check if the record exists
	    // Are we updation to match two fields?
	    if ($whereb =="" && $wherevalb =="") {
	   	// we are only updation  to match one fileld
	   	  // check if record already exist in the database
		    if ($this->rec_exist($where,$whereval,$table,$n="")){
			// check if value is more than one
			  if ($this->havecomma($ufield) || $this->havecomma($uvalue)) {
			  	// make the value arrays
			  	$afield = $this->makearray($ufield);
			  	$avalue = $this->makearray($uvalue);
			  	// count the values in each arrays
			  	if (count($afield) != count($avalue)) {
			  		// arrays does not match, show error
			  		$this->showerror("The values in your \'field\' does not match that of \'value\'. Please check your fields and values");
			  	}
			  	// they are equal, so get the number and update
			  	$c = count($afield);
			  	$d = $c - 1;
			  	for ($i=0; $i<=$d; $i++){
			  	  $tquery = "UPDATE ". $table . " SET ". $afield[$i] ."='" .$avalue[$i] ."' WHERE ". $where ."='$whereval'";
			  	  $mres = mysqli_query($this->dcon, $tquery);
			  	  if (!$mres){
				     $this->showerror("Could not update record to the database");
			       }
			  	}

			 }
		   else {
			  	// add single field and value to the database
				$tquery = "UPDATE ". $table . " SET ". $ufield ."='$uvalue' WHERE ". $where ."='$whereval'";
				$mres = mysqli_query($this->dcon, $tquery);
				if (!$mres){				
					$this->showerror("Could not update record to the database");
				 }
			  }

			}
			else {
				$this->showerror("There is no such record or table in the database");
			}
	    }
	   else { // we are updating to match two fields
	   	   // check if record already exist in the database
		    if ($this->rec_existb($where,$whereval,$whereb,$wherevalb,$table)){
			// check if value is more than one
			  if ($this->havecomma($ufield) || $this->havecomma($uvalue)) {
			  	// make the value arrays
			  	$afield = $this->makearray($ufield);
			  	$avalue = $this->makearray($uvalue);
			  	// count the values in each arrays
			  	if (count($afield) != count($avalue)) {
			  		// arrays does not match, show error
			  		$this->showerror("The values in your \'field\' does not match that of \'value\'. Please check your fields and values");
			  	}
			  	// they are equal, so get the number and update
			  	$c = count($afield);
			  	$d = $c - 1;
			  	for ($i=0; $i<=$d; $i++){
			  	  $tquery = "UPDATE ". $table . " SET ". $afield[$i] ."='" .$avalue[$i] ."' WHERE ". $where ."='$whereval' AND ". $whereb ."='$wherevalb'";
			  	  $mres = mysqli_query($this->dcon, $tquery);
			  	  if (!$mres){
				     $this->showerror("Could not update record to the database");
			       }
			  	}

			 }
		   else {
			  	// add single field and value to the database
				$tquery = "UPDATE ". $table . " SET ". $ufield ."='$uvalue' WHERE ". $where ."='$whereval' AND ". $whereb ."='$wherevalb'";
				$mres = mysqli_query($this->dcon, $tquery);
				if (!$mres){
					$this->showerror("Could not update record to the database");
				 }
			  }

			}
			else {
				$this->showerror("There is no such record or table in the database");
			}
	    }
		// else all went well. return true
		return  true;
	}

	/**
	 * This function is used to delete any record from the database <br />
	 *
	 * It takes the table ($table), the field 'where' to delete ($where)
	 * and the value of the filed 'where' to delete ($whereval). Can also be used for matching up
	 * to two fields of the record to delete
	 * @param string name_of_table
	 * @param string field_to_search for deleting
	 * @param mixed value_of_field_to_search for deleting
	 * @param string second_field_to_search for deleting
	 * @param mixed value_of_second_field_to_search for deleting
	 * @return TRUE, if operation is successful.
	 *
	 */
	function deleteval($table,$where,$whereval,$whereb="",$wherevalb=""){
		if ($table =="" || $where =="" || $whereval =="") {
			$this->showerror("There is an error with your request, pls try again");
		}
		if (($whereb =="" && $wherevalb !="") || ($whereb !="" && $wherevalb =="")) {
		   $this->showerror("There is an error with your optional fields, please thry again");
		}
		// else no error, so continue process
		// are we deleting records that match two conditions?
		if ($whereb =="" && $wherevalb =="") {
		  // only deleting records that match one condition
		 // check that field or value is a single field or value
			if ($this->havecomma($where) || $this->havecomma($whereval)) {
				// error occured
				$this->showerror("There is an error with your values");
			}
			// check if field already exists
			if ($this->rec_exist($where,$whereval,$table,$n="")){
			// delete values from the database
				$mqry = "DELETE FROM ". $table ." WHERE ". $where ."='$whereval'";
				$xqury = mysql_query($this->dcon, $mqry);
				if (!$xqury){
					$this->showerror("Could not delete record from database");
				}
			}
			else {
				$this->showerror("This record you want to delete does not exists in the Database");
			}
		}
		else {
			// we are deleting records that satisfy two conditions
			// check that field or value is a single field or value
			if ($this->havecomma($where) || $this->havecomma($whereval) || $this->havecomma($whereb) || $this->havecomma($wherevalb)) {
				// error occured
				$this->showerror("There is an error with your values");
			}
			// check if field already exists
			if ($this->rec_existb($where,$whereval,$whereb,$wherevalb,$table)){
			// delete values from the database
				$mqry = "DELETE FROM ". $table ." WHERE ". $where ."='$whereval' AND ". $whereb ."='$wherevalb'";
				$xqury = mysqli_query($this->dcon, $mqry);
				if (!$xqury){
					$this->showerror("Could not delete record from database");
				}
			}
			else {
				$this->showerror("This record you want to delete does not exists in the Database");
			}
		}
		// else all went well. return true
		return  true;
	}


	/**
	 * Function to fetch all result from the database table
	 *
	 * Takes the table name as parameter. It returns an array of the
	 * results from the database sorted by $orderby,  and arranged in $oder (A for
	 * Ascending order, D for Descending order). <br />
	 * You can also specify the nober of rows to return <br />
	 * EXAMPLES:
	 * <code>
	 *   // initiate the class
	 *   $yourclass = new SamMysql($database_connection);
	 * 	 $table = "name_of_table";
	 *   $orderby = "name_of_field";
	 *   $order = "A"; // Note: A for Ascending, D for descending orders
	 *   // get your results
	 * 	 $resource = $yourclass->getallresult($table,$orderby,"A");
	 *   // loop through the result array to get your values
	 * 	 while ($result = @mysql_fetch_array($resource))
	 * 		{
	 *      // prepare your table
	 * 		echo "<td>$result['field1']</td> <td>$result['field2']</td>
	 *       // etc
	 *       }
	 *
	 *  </code>
	 * @access Public
	 * @param string name_of_table
	 * @param string field_to_order_by [optional]
	 * @param string order, Ascending(A) or Descending(D) [optional]
	 * @param mixed offset (rows to skip)[Optional]
	 * @param mixed Total_rows_to_return [Optional]
	 * @return $allresult, the resource identifier for the database query;
	 *
	 */
	function getallresult($mtable,$orderby="",$order="",$offset="",$total=""){
		// check if value is more than one
        if ($this->havecomma($mtable)) {
        	$this->showerror("There is an error with the table name supplied, please correct and try again");
        }
		// set query parameters
			if ($orderby !="" && $order !="") {
				$order = strtoupper($order);
				if ($order =="A") {
					$AD = "ASC";
				}
				elseif ($order =="D") {
					$AD = "DESC";
				}
				else {
					$this->showerror("Please, " . $order ." can only be A or D. Please correct your mistake.");
				}
				  if ($offset=="" || $total=="") {
				  	$mqry = "SELECT * FROM ". $mtable ." ORDER BY ". $orderby . " " . $AD;
				  }
				  elseif ($offset !="" || $total !=""){
			        $mqry = "SELECT * FROM ". $mtable ." ORDER BY ". $orderby . " " . $AD ." LIMIT " . $offset .",".$total;
				  }
				  else {
				  	$mqry = "SELECT * FROM ". $mtable ." ORDER BY ". $orderby . " " . $AD ." LIMIT ". $total;
				  }
			}
			elseif ($orderby !="" && $order=="") {
				$AD = "DESC";
				if ($offset =="" || $total =="") {
				$mqry = "SELECT * FROM ". $mtable ." ORDER BY ". $orderby . " " . $AD;
				}
				else {
					if ($offset == "") {
						$ofs =0;
					}
					else {
						$ofs = $offset;
					}
				 $mqry = "SELECT * FROM ". $mtable ." ORDER BY ". $orderby . " " . $AD ." LIMIT " . $ofs .",".$total;
				  }
			}
			else {
				if ($offset =="" || $total =="") {
				$mqry = "SELECT * FROM ". $mtable;
				}
				else {
					if ($offset == "") {
						$ofs =0;
					}
					else {
						$ofs = $offset;
					}
				$mqry = "SELECT * FROM ". $mtable . " LIMIT " . $ofs .",".$total;
				}
			}

		// EXECUTE
		$trest = mysqli_query($this->dcon, $mqry);
		if (!$trest){
			$this->showerror("Could not perform query for the database");
		}
		// else all went well, return the resource identifier
		$this->allresult = $trest;
		return $this->allresult;

	}

	/**
	 * Function to get the total number of records in a table in a database <br />
	 *
	 * EXAMPLES:
	 * <code>
	 *   $yourclass = new SamMysql($database_connection);
	 * 	 $table = "name_of_table";
	 *   // get total records from table and display it
	 *   echo "Table has ". $yourclass->getrecords($table) . " records";
	 *  </code>
	 * @access Public
	 * @param string name_of_table
	 * @param string field_to_search_for_record
	 * @param mixed value_of_field_to_search_for_record
     * @param string Second field_to_search_for_record
     * @param mixed value_of_Second_field_to_search_for_record
	 * @return $numvals, the total number of records in the table.
	 *
	 */
	function getrecords($table,$where="",$whereval="",$whereb="",$wherevalb="",$wherec="",$wherevalc=""){
		if ($table =="") {
			$this->showerror("There is an error with your Getrecords request, Please try again.");
		}
		if (($where =="" && $whereval !="") || ($where !="" && $whereval =="")) {
		   $this->showerror("There is an error with your Getrecords optional fields, please try again");
		}
        // check the second options
      if (($whereb =="" && $wherevalb !="") || ($whereb !="" && $wherevalb =="")) {
           $this->showerror("There is an error with your Second optional fields, please try again");
      }
      // check the second options
        if (($wherec =="" && $wherevalc !="") || ($wherec !="" && $wherevalc =="")) {
           $this->showerror("There is an error with your Third optional fields, please try again");
        }
        // make another check, no jumping
        if ($where =="" && $whereb !="")  {
           $this->showerror("This is not allowed, please try again");
        }
		// else all is well, so get the records we need
		if ($where =="" && $whereb =="" && $wherec =="") {
			//we are getting all records
			$mqry = "SELECT COUNT(*) AS mycnt FROM " . $table;
			$reslt = mysqli_query($this->dcon, $mqry);
			if (!$reslt) {
				$this->showerror("Could not perform your query, there was an error.");
			}
		}
		elseif ($where !="" && $whereb =="" && $wherec =="") {
			// we are getting total records that satisfy a condition
			$mqry = "SELECT COUNT(*) AS mycnt FROM " . $table ." WHERE ". $where ."='$whereval'";
			$reslt = mysqli_query($this->dcon, $mqry);
			if (!$reslt) {
				$this->showerror("Could not perform your query, there was an error.");
			}
		}
      elseif($where !="" && $whereb !="" && $wherec =="") {
            $mqry = "SELECT COUNT(*) AS mycnt FROM " . $table ." WHERE ". $where ."='$whereval' AND ". $whereb ."='$wherevalb'";
            $reslt = mysqli_query($this->dcon, $mqry);
            if (!$reslt) {
                $this->showerror("Could not perform your query, there was an error.");
            }
        }
      else {
            $mqry = "SELECT COUNT(*) AS mycnt FROM " . $table ." WHERE ". $where ."='$whereval' AND ". $whereb ."='$wherevalb' AND ". $wherec ."='$wherevalc'";
            $reslt = mysqli_query($this->dcon, $mqry);
            if (!$reslt) {
                $this->showerror("Could not perform your query, there was an error.");
            }
        }
		// else all went well, return our result
		$threst = mysqli_fetch_array($reslt);
		$this->numvals = $threst['mycnt'];
		return $this->numvals;
	}

    /**
	 * Function to get the total number of records in a table in a database <br />
	 *
	 * EXAMPLES:
	 * <code>
	 *   $yourclass = new SamMysql($database_connection);
	 * 	 $table = "name_of_table";
	 *   // get total records from table and display it
	 *   echo "Table has ". $yourclass->getsum($table) . " records";
	 *  </code>
	 * @access Public
	 * @param string name_of_table
	 * @param string field_to_search_for_record
	 * @param mixed value_of_field_to_search_for_record
     * @param string Second field_to_search_for_record
     * @param mixed value_of_Second_field_to_search_for_record
	 * @return $numvals, the total number of records in the table.
	 *
	 */
	function getsum($table,$sumfield="",$where="",$whereval="",$whereb="",$wherevalb="",$wherec="",$wherevalc=""){
		if ($table =="" && $sumfield == "") {
			$this->showerror("There is an error with your Getsum request, Please try again.");
		}
		if (($where =="" && $whereval !="") || ($where !="" && $whereval =="")) {
		   $this->showerror("There is an error with your Getsum optional fields, please try again");
		}
        // check the second options
      if (($whereb =="" && $wherevalb !="") || ($whereb !="" && $wherevalb =="")) {
           $this->showerror("There is an error with your Second optional fields, please try again");
      }
      // check the second options
        if (($wherec =="" && $wherevalc !="") || ($wherec !="" && $wherevalc =="")) {
           $this->showerror("There is an error with your Third optional fields, please try again");
        }
        // make another check, no jumping
        if ($where =="" && $whereb !="")  {
           $this->showerror("This is not allowed, please try again");
        }
		// else all is well, so get the records we need
		if ($where =="" && $whereb =="" && $wherec =="") {
			//we are getting all records
			$mqry = "SELECT SUM(".$sumfield.") AS mycnt FROM " . $table;
			$reslt = mysqli_query($this->dcon, $mqry);
			if (!$reslt) {
				$this->showerror("Could not perform your query, there was an error.");
			}
		}
		elseif ($where !="" && $whereb =="" && $wherec =="") {
			// we are getting total records that satisfy a condition
			$mqry = "SELECT SUM(".$sumfield.") AS mycnt FROM " . $table ." WHERE ". $where ."='$whereval'";
			$reslt = mysqli_query($this->dcon, $mqry);
			if (!$reslt) {
				$this->showerror("Could not perform your query, there was an error.");
			}
		}
      elseif($where !="" && $whereb !="" && $wherec =="") {
            $mqry = "SELECT SUM(".$sumfield.") AS mycnt FROM " . $table ." WHERE ". $where ."='$whereval' AND ". $whereb ."='$wherevalb'";
            $reslt = mysqli_query($this->dcon, $mqry);
            if (!$reslt) {
                $this->showerror("Could not perform your query, there was an error.");
            }
        }
      else {
            $mqry = "SELECT SUM(".$sumfield.") AS mycnt FROM " . $table ." WHERE ". $where ."='$whereval' AND ". $whereb ."='$wherevalb' AND ". $wherec ."='$wherevalc'";
            $reslt = mysql_query($this->dcon, $mqry);
            if (!$reslt) {
                $this->showerror("Could not perform your query, there was an error.");
            }
        }
		// else all went well, return our result
		$threst = mysqli_fetch_array($reslt);
		$this->sumvals = $threst['mycnt'];
		return $this->sumvals;
	}//end of function getsum

  /**
	 * Function to perform a search for records in a table in a database <br />
	 *
	 * (NEW FUNCTION)
	 * This function can be used to perform a search for records in your database.
	 * This has been included for those that wants to incorporate a search function <br />
	 * into their database driven website. <br />
	 * Remember, you can also specify how many rows of result to return which you can then
	 * manipulate with your php code. <br />
	 * EXAMPLES:
	 * <code>
	 *   $yourclass = new SamMysql($database_connection);
	 * 	 $words = "words_to_search_for";
	 *   $command = "ALL"; // or "ANY" Default is ANY
	 *   $field = "field_to_search_for_the_words";
	 *   $table = "name_of_table";
	 *   // Perform the search
	 *   $result_identifier = $yourclass->dosearch($words,$table,$field,$command,)";
	 *   // show search results
	 *   while ($search_result = @mysql_fetch_array($result_identifier))
	 * 		{
	 *      // display
	 * 		echo "<u>$search_result['field_title']</u> <br> $search_result['field_body']
	 *       // etc
	 *       }
	 *  </code>
	 * @access Public
	 * @param string words_to_search_for
	 * @param string name_of_table_to_search
	 * @param string field_to_search from the table
	 * @param string command_for_search (whether ALL the words or ANY)
	 * @param string field_to_order_by [optional]
	 * @param string order [Ascending or Descending]
	 * @param mixed offset (rows to skip)[Optional]
	 * @param mixed Total_rows_to_return [Optional]
	 * @return $s_results, the link identifier to the search results in the table.
	 *
	 */
	function dosearch($words,$table,$sfield,$command="",$orderby="",$order="",$offset="",$total=""){
		if ($words =="" || $table =="" || $sfield=="") {
			$this->showerror("There is an error with your search request, Please correct and try again.");
		}
		// Clean the words to be searched for
        $new_words = $this->doclean($words);
        // Are we searching for All WORDS
        if ($command == "ALL") { //1. SEARCH FOR ANY WORD
        	// PERFORM SEARCH ON TABLE
        	 if ($orderby !="" && $order !="") {
				$order = strtoupper($order);
				if ($order =="A") {
					$AD = "ASC";
				}
				elseif ($order =="D") {
					$AD = "DESC";
				}
				else {
					$this->showerror("Please, " . $order ." can only be A or D. Please correct your mistake.");
				}
				if ($offset=="" || $total=="") {
        	        $myqry = "SELECT * FROM ". $table . " WHERE ". $sfield ." LIKE '%$new_words%' ORDER BY ". $orderby . " " . $AD;
        	    }
				elseif ($offset !="" || $total !=""){
					 $myqry = "SELECT * FROM ". $table . " WHERE ". $sfield ." LIKE '%$new_words%' ORDER BY ". $orderby . " " . $AD ." LIMIT " . $offset .",".$total;
				}
			    else {
				 	 $myqry = "SELECT * FROM ". $table . " WHERE ". $sfield ." LIKE '%$new_words%' ORDER BY ". $orderby . " " . $AD ." LIMIT ". $total;
				}
        	 }
        	 elseif ($orderby !="" && $order=="") {
				$AD = "DESC";
				if ($offset =="" || $total =="") {
        	         $myqry = "SELECT * FROM ". $table . " WHERE ". $sfield ." LIKE '%$new_words%' ORDER BY ". $orderby . " " . $AD;
        	     }
				else {
					if ($offset == "") {
						$ofs =0;
					}
					else {
						$ofs = $offset;
					}
					$myqry = "SELECT * FROM ". $table . " WHERE ". $sfield ." LIKE '%$new_words%' ORDER BY ". $orderby . " " . $AD ." LIMIT " . $ofs .",".$total;
				}
			}
			else {
				if ($offset =="" || $total =="") {
					$myqry = "SELECT * FROM ". $table . " WHERE ". $sfield ." LIKE '%$new_words%'";
				}
				else {
					if ($offset == "") {
						$ofs =0;
					}
					else {
						$ofs = $offset;
					}
					$myqry = "SELECT * FROM ". $table . " WHERE ". $sfield ." LIKE '%$new_words%' LIMIT " . $ofs .",".$total;
				}
			}
        }
        else{ //2. SEARCH FOR ANY WORD
        	if ($command =="" || $command =="ANY") {
        		
        	// split the words by space into arrays
        	if (@ereg(" ", $new_words)) { //2.1 IS WORDS SPLITTABLE
        		$wlist = explode(" ",$new_words);
        		// If we have not more than two words
        	  if (count($wlist) < 3) { //2.2 YES IT IS SPLITTABLE
        	  	 if ($orderby !="" && $order !="") {
					$order = strtoupper($order);
					if ($order =="A") {
						$AD = "ASC";
					}
					elseif ($order =="D") {
						$AD = "DESC";
					}
					else {
						$this->showerror("Please, " . $order ." can only be A or D. Please correct your mistake.");
					}
					if ($offset=="" || $total=="") {
	        	 		$myqry = "SELECT * FROM ". $table . " WHERE ". $sfield ." LIKE '%".$wlist[0]."%' OR ". $sfield . " LIKE '%".$wlist[1]."%' ORDER BY ". $orderby . " " . $AD;
					}
	        	 	elseif ($offset !="" || $total !=""){
						$myqry = "SELECT * FROM ". $table . " WHERE ". $sfield ." LIKE '%".$wlist[0]."%' OR ". $sfield . " LIKE '%".$wlist[1]."%' ORDER BY ". $orderby . " " . $AD ." LIMIT " . $offset .",".$total;
	        	 	}
	        	 	else {
	        	 		$myqry = "SELECT * FROM ". $table . " WHERE ". $sfield ." LIKE '%".$wlist[0]."%' OR ". $sfield . " LIKE '%".$wlist[1]."%' ORDER BY ". $orderby . " " . $AD ." LIMIT ". $total;
	        	 	}
	        	  }
	        	 elseif ($orderby !="" && $order=="") {
					$AD = "DESC";
					if ($offset =="" || $total =="") {
						$myqry = "SELECT * FROM ". $table . " WHERE ". $sfield ." LIKE '%".$wlist[0]."%' OR ". $sfield . " LIKE '%".$wlist[1]."%' ORDER BY ". $orderby . " " . $AD;
					}
				   else {
					  if ($offset == "") {
						$ofs =0;
					  }
					  else {
						$ofs = $offset;
					  }
					   $myqry = "SELECT * FROM ". $table . " WHERE ". $sfield ." LIKE '%".$wlist[0]."%' OR ". $sfield . " LIKE '%".$wlist[1]."%' ORDER BY ". $orderby . " " . $AD ." LIMIT " . $ofs .",".$total;
				    }
				 }
			   else {
				  if ($offset =="" || $total =="") {
					 $myqry = "SELECT * FROM ". $table . " WHERE ". $sfield ." LIKE '%".$wlist[0]."%' OR ". $sfield . " LIKE '%".$wlist[1]."%'";
				   }
				 else {
					if ($offset == "") {
						$ofs =0;
					}
					else {
						$ofs = $offset;
					}
					$myqry = "SELECT * FROM ". $table . " WHERE ". $sfield ." LIKE '%".$wlist[0]."%' OR ". $sfield . " LIKE '%".$wlist[1]."%' LIMIT " . $ofs .",".$total;
				 }
			   }   
        	  }
        	  else { //2.3 WORDS MORE THAT TWO, SEARCH FOR FIRST THREE
        	  	   if ($orderby !="" && $order !="") {
					$order = strtoupper($order);
					if ($order =="A") {
						$AD = "ASC";
					}
					elseif ($order =="D") {
						$AD = "DESC";
					}
					else {
						$this->showerror("Please, " . $order ." can only be A or D. Please correct your mistake.");
					}
					if ($offset=="" || $total=="") {
	        	 		$myqry = "SELECT * FROM ". $table . " WHERE ". $sfield ." LIKE '%".$wlist[0]."%' OR ". $sfield . " LIKE '%".$wlist[1]."%' OR ". $sfield . " LIKE '%".$wlist[2]."%' ORDER BY ". $orderby . " " . $AD;
					}
	        	 	elseif ($offset !="" || $total !=""){
						$myqry = "SELECT * FROM ". $table . " WHERE ". $sfield ." LIKE '%".$wlist[0]."%' OR ". $sfield . " LIKE '%".$wlist[1]."%' OR ". $sfield . " LIKE '%".$wlist[2]."%' ORDER BY ". $orderby . " " . $AD ." LIMIT " . $offset .",".$total;
	        	 	}
	        	 	else {
	        	 		$myqry = "SELECT * FROM ". $table . " WHERE ". $sfield ." LIKE '%".$wlist[0]."%' OR ". $sfield . " LIKE '%".$wlist[1]."%' OR ". $sfield . " LIKE '%".$wlist[2]."%' ORDER BY ". $orderby . " " . $AD ." LIMIT ". $total;
	        	 	}
	        	  }
	        	 elseif ($orderby !="" && $order=="") {
					$AD = "DESC";
					if ($offset =="" || $total =="") {
						$myqry = "SELECT * FROM ". $table . " WHERE ". $sfield ." LIKE '%".$wlist[0]."%' OR ". $sfield . " LIKE '%".$wlist[1]."%' OR ". $sfield . " LIKE '%".$wlist[2]."%' ORDER BY ". $orderby . " " . $AD;
					}
				   else {
					  if ($offset == "") {
						$ofs =0;
					  }
					  else {
						$ofs = $offset;
					  }
					   $myqry = "SELECT * FROM ". $table . " WHERE ". $sfield ." LIKE '%".$wlist[0]."%' OR ". $sfield . " LIKE '%".$wlist[1]."%' OR ". $sfield . " LIKE '%".$wlist[2]."%' ORDER BY ". $orderby . " " . $AD ." LIMIT " . $ofs .",".$total;
				    }
				 }
			   else {
				  if ($offset =="" || $total =="") {
					 $myqry = "SELECT * FROM ". $table . " WHERE ". $sfield ." LIKE '%".$wlist[0]."%' OR ". $sfield . " LIKE '%".$wlist[1]."%' OR ". $sfield . " LIKE '%".$wlist[2]."%'";
				   }
				 else {
					if ($offset == "") {
						$ofs =0;
					}
					else {
						$ofs = $offset;
					}
					$myqry = "SELECT * FROM ". $table . " WHERE ". $sfield ." LIKE '%".$wlist[0]."%' OR ". $sfield . " LIKE '%".$wlist[1]."%' OR ". $sfield . " LIKE '%".$wlist[2]."%' LIMIT " . $ofs .",".$total;
				 }
			   }   
        	  	
        	  }

        	}
        	else { // 2.1.2 WORDS NOT SPLITTABLE
        		// only one word, not breakable
        		if ($orderby !="" && $order !="") {
				$order = strtoupper($order);
				if ($order =="A") {
					$AD = "ASC";
				}
				elseif ($order =="D") {
					$AD = "DESC";
				}
				else {
					$this->showerror("Please, " . $order ." can only be A or D. Please correct your mistake.");
				}
				if ($offset=="" || $total=="") {
        	        $myqry = "SELECT * FROM ". $table . " WHERE ". $sfield ." LIKE '%$new_words%' ORDER BY ". $orderby . " " . $AD;;
        	    }
				elseif ($offset !="" || $total !=""){
					 $myqry = "SELECT * FROM ". $table . " WHERE ". $sfield ." LIKE '%$new_words%' ORDER BY ". $orderby . " " . $AD ." LIMIT " . $offset .",".$total;
				}
			    else {
				 	 $myqry = "SELECT * FROM ". $table . " WHERE ". $sfield ." LIKE '%$new_words%' ORDER BY ". $orderby . " " . $AD ." LIMIT ". $total;
				}
        	 }
        	 elseif ($orderby !="" && $order=="") {
				$AD = "DESC";
				if ($offset =="" || $total =="") {
        	         $myqry = "SELECT * FROM ". $table . " WHERE ". $sfield ." LIKE '%$new_words%' ORDER BY ". $orderby . " " . $AD;
        	     }
				else {
					if ($offset == "") {
						$ofs =0;
					}
					else {
						$ofs = $offset;
					}
					$myqry = "SELECT * FROM ". $table . " WHERE ". $sfield ." LIKE '%$new_words%' ORDER BY ". $orderby . " " . $AD ." LIMIT " . $ofs .",".$total;
				}
			}
			else {
				if ($offset =="" || $total =="") {
					$myqry = "SELECT * FROM ". $table . " WHERE ". $sfield ." LIKE '%$new_words%'";
				}
				else {
					if ($offset == "") {
						$ofs =0;
					}
					else {
						$ofs = $offset;
					}
					$myqry = "SELECT * FROM ". $table . " WHERE ". $sfield ." LIKE '%$new_words%' LIMIT " . $ofs .",".$total;
				}
			 } // END NO offset, NO total
			 
           } // end WORDS NOT SPLITTABLE
           
         } // end IF COMMAND IS ANY or ""
         
       }// end SEARCH FOR ANY WORD
       // perform query now
        $outcome = mysqli_query($this->dcon, $myqry);
        if (!$outcome) {
        	$this->showerror("There is an error with your search request. Could not perform search.");
        }
        // else search was successful
        $this->s_results = $outcome;
        return $this->s_results;
	}
 
  //////////////////////////////// PRIVATE FUNCTIONS //////////////////////////////////////////////////
  
   /**
	 * Function to check if the value already exist in the database.<br />
	 *
	 * The field ($ffld), the field value ($fval) to check, the table to access
	 * are provided.
	 *
	 * @access Private
	 * @param string field_name
	 * @param mixed field_value
	 * @param string table_name
	 * @param int field_number_to_use for the check
	 * @return TRUE, if value already exists
	 *
	 */
	function rec_exist($ffld,$fval,$tab,$k=""){
		// do some check
		//let's check if $ffld has more that one value,
		if ($this->havecomma($ffld) && $this->havecomma($fval)) {
			// split them
			$fld1 = $this->makearray($ffld);
			$val1 = $this->makearray($fval);
			if ($k =="") {
				$val = $this->clappo($val1[0]);
				$fld = $fld1[0];
			}
			else {
				$val = $this->clappo($val1[$k-1]);
				$fld = $fld1[$k-1];

			}
		}
		else {
			$val = $this->clappo($fval);
			$fld = $ffld;
		}

	   $mqry = "SELECT * FROM ". $tab ." WHERE ". $fld ."='$val'";
	   $trest = mysqli_query($this->dcon, $mqry);
	   if (mysqli_num_rows($trest) ==1 || mysqli_num_rows($trest) > 0) {
	   	// there is a row

	   	return true;
	   }
	}

	/**
	 * Second Function to check if the value already exist in the database.<br />
	 *
	 * The fields ($ffld), ($ffldb) and the fields values ($fval), ($fvalb) to check, the table to access
	 * are provided. This is used by deleting and update functions for precheck purposes.
	 *
	 * @access Private
	 * @param string fielda_name
	 * @param mixed fielda_value
	 * @param string fieldb_name
	 * @param mixed fieldb_value
	 * @param string table_name
	 * @param int field_number_to_use for the check
	 * @return TRUE, if value already exists
	 *
	 */
	function rec_existb($ffld,$fval,$ffldb,$fvalb,$tab){
		// do some check
		//No field has more than one value, so do some cleanup
		$val = $this->clappo($fval);
		$fld = $ffld;
		$val1 = $this->clappo($fvalb);
		$fld1 = $ffldb;

	   $mqry = "SELECT * FROM ". $tab ." WHERE ". $fld ."='$val' AND ". $fld1 ."='$val1'";
	   $trest = mysqli_query($this->dcon, $mqry);
	   if (mysqli_num_rows($trest) ==1 || mysqli_num_rows($trest) > 0) {
	   	// there is a row

	   	return true;
	   }
	}//end of the function rec_existb

   /**
	 * Function to show images from the db
	 *
	 * @access Private
	 * @param string data_to_convert to array.
	 * @return $imgg, array of values from data
	 */
    function showpic($where,$whereval,$typ){
        $imgg = "";
      //let us get the image of this user
        $myresult = mysqli_query($this->dcon,"SELECT * FROM members WHERE ".$where."='".$whereval."'") or die("Cannot get the profile picture");
        if(mysqli_fetch_array($myresult) == 0){
            $row = mysqli_fetch_array($myresult);
            if($row['photo'] == ""){
          //let us get the sex of the member
          $sex = $this->getval("member_id",$whereval,"members","gender");
          if($sex == "Male"){
            $imgg = "../images/m.jpg";
          }
          elseif($sex == "Female"){
            $imgg = "../images/f.jpg";
          }
            }
        }//end of the if statement that says there is no profile picture
        else if(mysqli_fetch_array($myresult) > 1){
          $myresult = mysqli_query($this->dcon,"SELECT * FROM members WHERE ".$where."='".$whereval."'") or die("Cannot get the profile picture");
          while($myrow = mysqli_fetch_array($myresult)){
            $imgg = "../admin/".$myrow['photo'];
          }
        }//end of the else statement that says the profile picture is more than one
        else{
          $myresult = mysqli_query($this->dcon,"SELECT * FROM members WHERE ".$where."='".$whereval."'") or die("Cannot get the profile picture");
          $myrow = mysqli_fetch_array($myresult);
          $imgg = "../admin/".$myrow['photo'];
        }//end of the else statement that says the profile picture is just one
        return $imgg;
    }//end of the function showpic

	/**
	 * Function to convert a string of values to arrays
	 *
	 * @access Private
	 * @param string data_to_convert to array.
	 * @return $marr, array of values from data
	 */
	function makearray($mstrg) {
		// check if values are separated by comma ','.
	    if ($this->havecomma($mstrg)) {
	    	$marr = explode(",,", $mstrg);
	    }
		return $marr;
	}

	/**
	 * Function to check if a string can be splited to arrays
	 *
	 * @access Private
	 * @param string value_to_check
	 * @return TRUE, if it is explodable and FALSE if otherwise
	 *
	 */
	function havecomma($mystr) {
		if (@ereg(",,", $mystr)) {
			// more than one record, so it can be exploded
			return true;
		}
		else {
			return false;
		}
	}


    /**
	 * Function to check if a string can be splited to arrays
	 *
	 * @access Private
	 * @param string value_to_check
	 * @return TRUE, if it is explodable and FALSE if otherwise
	 *
	 */
	function havecomma1($mystr) {
		if (ereg(",", $mystr)) {
			// more than one record, so it can be exploded
			return true;
		}
		else {
			return false;
		}
	}


	/**
	 * Function to clean if a string has appostrophe or single quote '
	 *
	 * @access Private
	 * @param string String_to_clean
	 * @return $samyb , the cleaned string
	 *
	 */
	function clappo($mystr) {
		if (@ereg("'", $mystr)) {
			// it has appostrophe, so clean up
			$samyb = trim($mystr);
            $samyb = str_replace("'","",$mystr);
            return $samyb;
		}
		else {
			return $mystr;
		}

	}


	/**
	 * Function to remove any traces of double comma ',,' from any values submitted 
	 * Converts ',,' to ',' before inputing to database.'
	 *
	 * @access Private
	 * @param string to check for double comma
	 * @return $myfdval, the converted string
	 */
	function clncomma($myfdval) {
		if (@ereg(",,", $myfdval)) {
			// it has double comma, so clean up
			$samyf = trim($myfdval);
            $samyf = str_replace(",,",",",$myfdval);
            return $samyf;
		}
		else {
			return $myfdval;
		}

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
	     $samyb = @ereg_replace("%([A-Za-z]+)%","",$samyb);
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
	 * Function to display error messages
	 *
	 * Displays errors if any occurs in the process
	 *
	 * @access Private
	 * @param string error_message to be displayed.
	 *
	 */
	function showerror($err) {
		echo "<script language='javascript'> alert('Sorry! " . $err . "'); history.go(-1);</script>";
		exit();
	}
}
?>