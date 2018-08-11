<?php
/**
 * ************************************************************************************
 * 	               SAMPAGINATOR PHP CLASS 	v 0.9.1									  *
 * 			       copyright (c) 2015	 Ezekiel Aliyu
 * ************************************************************************************
 *  This program is free software; you can redistribute it and/or					  *
 *	modify it under the terms of the GNU Lesser General Public 						  *
 *	License as published by the Free Software Foundation; either					  *
 *	version 3 of the License, or (at your option) any later version.                  *
 *																					  *
 *	This program is distributed in the hope that it will be useful,				      *
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of                    *
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU                  *
 *	Lesser General Public License for more details.                                   *
 *                                                                                    *
 *  You should have received a copy of the GNU General Public License                 *
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>             *
 *                                                                                    *
 *																					  *
 * ************************************************************************************
 */
/**
 * A very good and simple to use pagination Class. <br />
 *
 * This is a very <b>simple</b> but <b>powerful</b> pagination Class. Whether you displaying
 * lists of articles, news or images for a photogallery (from a database), this pagination class will make
 * the displaying of your records easier than you think. <br />
 *
 *
 * EXAMPLES:
 * <code>
 * // We will require the Sammysql Class for this Class
 * // initialise SamMysql Class
 *  $mydb = new SamMysql($dbcon);
 *
 * // First of all, get the page number of the page to be displayed
 * // If page navigation has not been clicked, this is the initial or first page
 * if (!isset($_GET['pgn']) && !isset($_POST['pgn'])){
 * 		$p_number = 0;
 * }
 * elseif (isset($_GET['pgn'])) {
 * 		$p_number = $_GET['pgn'];
 * }
 * elseif (isset($_POST['pgn'])) {
 * 		$p_number = $_POST['pgn'];
 * }
 * // THEN INITIALIZE YOUR CLAS AND USE THE VALUE
 * $yourclass = new SamPaginator(..........); //
 * </code>
 *
 * @copyright 2008  Samoltech Studios Limited {http://www.samoltech.biz}
 * @author Ezekiel Aliyu {zinconewton2@samoltech.org}
 * @version 0.9A
 * @package SamPaginator_Class
 * @name SamPaginator
 */
/**
 * We will need one class to work with this class
 * (1) SamMysql Class
 *
 *
 */

// Begin Class Declaration
class SamPaginator {
/**
  * The instance of the SamMysql database class
  * @var object
  * @access private
  */
    var $mydb;

/**
  * The resouce id for the results from the database query/search
  * @var resource
  * @access private
  */
    var $tableResult;

/**
  * The total number of available records from the database query/search
  * @var integer
  * @access private
  */
    var $totalRecord;

/**
  * The total number of pages of records we have from the database query/search
  * @var integer
  * @access private
  */
    var $totalPages;


/**
  * The page number of the page to be displayed
  * @var integer
  * @access private
  */
    var $page_number;


/**
  * The url of the page using this class now
  * @var string
  * @access private
  */
    var $page_url;

/**
 * The name of the ID field of the table we are working on
 * @var string
 * @access private
 */
	var $ID_field_name;

/**
 * The name of the table in the database
 * @var string
 * @access private
 */
	var $table_name;

/**
 * The maximum number of records per page to be displayed
 * @var integer
 * @access private
 */
	var $rec_perPage;

/**
 * The number of rows to display per page
 * @var integer
 * @access private
 */
	var $rows_per_page;

/**
 * The number of columns to display per row on each page
 * @var integer
 * @access private
 */
	var $columns_per_row;

/**
 * The name of the first field we need to search for in the table
 * @var string
 * @access private
 */
	var $fieldA;

/**
 * The value of the first field we need to search for in the table
 * @var string
 * @access private
 */
	var $valueA;

/**
 * The name of the second field we need to search for in the table
 * @var string
 * @access private
 */
	var $fieldB;

/**
 * The value of the second field we need to search for in the table
 * @var string
 * @access private
 */
	var $valueB;

/**
 * The type of operation to perform, search or normal query
 * @var string
 * @access private
 */
	var $operation;

/**
 * The formatted date value
 * @var mixed
 * @access private
 */
	var $fdate;
/**
 * The field to sort results with
 * @var mixed
 * @access private
 */
   var $sort;
/**
 * The direction to sort results with
 * @var mixed
 * @access private
 */
   var $order;
/**
  * The resouce id for the child table results from the database query/search
  * @var resource
  * @access private
  */
    var $childResult;


/**
 * The Class Constructor
 *
 * Accepts your database connection object and other parameters.
 *
 * @param Object Instance of the SamMysql Class
 * @param String Name of Table in the Database
 * @param String Name of the id field on the table
 * @param String Type of operation to perform on the table, 'S' for SEARCH or 'Q' for QUERY
 * @param Integer Page_number of the page to be displayed
 * @param Integer Number of rows to display per page
 * @param Integer Number of columns to display per Row
 * @param String Name of the first field to search for in the table
 * @param Mixed Value of the first field to search for in the table
 * @param String Name of the second field to search for in the table
 * @param Mixed Value of the second field to search for in the table
 *
 */
	function SamPaginator(&$mydb,$recordTable,$Id_field,$Operation,$PageNumber,$maxRows=2,$maxColumns=1,$f_FieldName="None",$f_FieldValue="None",$s_FieldName="None",$s_FieldValue="None",$orderby="None",$order="None")
	{
		$this->mydb = &$mydb;
		$this->table_name = $recordTable;
		$this->ID_field_name = $Id_field;
		$this->operation = $Operation;
		$this->page_number = $PageNumber;
		$this->rows_per_page = $maxRows;
        $this->columns_per_row = $maxColumns;
        if($f_FieldName == "") 
        {
           $this->fieldA = "None";
        }
        else{
           $this->fieldA = $f_FieldName;
        }
        
         if($f_FieldValue == "") 
        {
           $this->valueA = "None";
        }
        else{
           $this->valueA = $f_FieldValue;
        } 
        
        if($s_FieldName == "") 
        {
           $this->fieldB = "None";
        }
        else{
           $this->fieldB = $s_FieldName;
        }
        
        if($s_FieldValue == "") 
        {
           $this->valueB = "None";
        }
        else{
           $this->valueB = $s_FieldValue;
        }
        
        
        
        $this->sort = $orderby; 
        $this->order = $order; 
        // perform check
        if ($this->sort =="None")
        {
            $this->sort = $this->ID_field_name;
        }
        // ORDER
        if ($this->order =="None")
        {
            $this->order = "D";
        }
        // calculate the maximum records per page
        $this->rec_perPage = ceil($this->rows_per_page * $this->columns_per_row);
        // get total records
        $this->totalRecord = $this->getTotalRecords();
        // get the total number of pages
        $this->totalPages = ceil($this->totalRecord / $this->rec_perPage);
	}


	/**
	 * Function that get our records from the given table in the database
	 *
	 * It does not take any parameter. <br />
	 * You can use this if you want to display the records in your own way.<br />
	 * Otherwise, you don't need to call this function as there is another function
	 * that will display the records for you.
	 *
	 * EXAMPLE:
	 *
	 * <code>
	 * Usage
	 * =====
	 * $mypageclass = new SamPaginator(.......);
	 * // use this function only if you wish to display the
	 * // records in your own way, otherwise, you may not really
	 * // need this function
	 * $my_resource = $mypageclass->getResults();
	 * // loop through the result to display your records
	 * while ($my_result = mysql_fetch_array($my_resource) {
	 *   // Display your records here using your own style
	 * 	............
	 * }
	 * </code>
	 *
	 * @access Public
	 * @return resource $tableResult
	 */
	function getResults(){
		// Work on the values we need to use
		if ($this->fieldA =="None" && $this->fieldB != "None") {
			$this->displayerror("Sorry, can not jump the fields, If you want to serch only one field, then use the first field and first value instead.");
		}
		if (($this->fieldA =="None" && $this->valueA != "None") || ($this->fieldB =="None" && $this->valueB != "None")) {
			$this->displayerror("Sorry, A corresponding value for the field was not provided. Execution Can not continue.");
		}

		// 1. Are we doing a search or normal query
		if (strtoupper($this->operation) == "S") {
			// check if there is no mixup
			if (($this->fieldA != "None" && $this->fieldB != "None") || ($this->fieldA == "None" && $this->fieldB == "None")) {
				// tell user, this shouldn't be a search
				$this->displayerror("Sorry, This is a wrong call for this action. This shouldn't be a search. Can not continue.");
			}
			// we can go ahead
			// a. Do we have more tha one page record?
			if ($this->totalRecord <= $this->rec_perPage) {
				// we have only one page, so set page to first page
				$page = 1;
				// perform operation
				$the_result = $this->mydb->dosearch($this->valueA,$this->table_name,$this->fieldA,"",$this->sort,$this->order);
			}
			else {
				// The record spans more than one page
				// is this still the first page, i.e, page 1?
				if ($this->page_number == 0) {
					// no page was sent, so this is first page
					$page = 1;
					// get result
					$the_result = $this->mydb->dosearch($this->valueA,$this->table_name,$this->fieldA,"",$this->sort,$this->order,"0",$this->rec_perPage);
				}
				else {
					// a page id was sent , set this to the page
					$page = $this->page_number;
					// we have to start this record from where the initial page stops
					$start_r = ceil(($page -1) * $this->rec_perPage);
					// get result
					$the_result = $this->mydb->dosearch($this->valueA,$this->table_name,$this->fieldA,"",$this->sort,$this->order,$start_r,$this->rec_perPage);
				}

			}

		}
		elseif (strtoupper($this->operation) == "Q") {
			// this is norma query, so check instances
			if ($this->fieldA == "None" && $this->fieldB == "None") {
				// use getallresult
				if ($this->totalRecord <= $this->rec_perPage) {
					// we have only one page, so set page to first page
					$page = 1;
					// perform operation
					$the_result = $this->mydb->getallresult($this->table_name,$this->sort,$this->order);
				}
			   else {
					// The record spans more than one page
					// is this still the first page, i.e, page 1?
					if ($this->page_number == 0) {
						// no page was sent, so this first
						$page = 1;
						// get result
						$the_result = $this->mydb->getallresult($this->table_name,$this->sort,$this->order,"0",$this->rec_perPage);
					}
					elseif ($this->page_number == 1) { //***** NEW CHANGE *****//
						// we are back to page 1
						
						// get result
						
						$the_result = $this->mydb->getallresult($this->table_name,$this->sort,$this->order,"0",$this->rec_perPage);
					}
					else {
						// a page id was sent , set this to the page
						$page = $this->page_number;
						// we have to start this record from where the initial page stops
						$start_r = ceil(($page -1) * $this->rec_perPage);
						// get result
						$the_result = $this->mydb->getallresult($this->table_name,$this->sort,$this->order,$start_r,$this->rec_perPage);
					}
			    }

		    }
		    elseif ($this->fieldA != "None" && $this->fieldB == "None") {
		    	// use getallrow
		    	if ($this->totalRecord <= $this->rec_perPage) {
					// we have only one page, so set page to first page
					$page = 1;
					// perform operation
					$the_result = $this->mydb->getallrow($this->fieldA,$this->valueA,$this->table_name,$this->sort,$this->order);
				}
			   else {
					// The record spans more than one page
					// is this still the first page, i.e, page 1?
					if ($this->page_number == 0) {
						// no page was sent, so this first
						$page = 1;
						// get result
						$the_result = $this->mydb->getallrow($this->fieldA,$this->valueA,$this->table_name,$this->sort,$this->order,"",$this->rec_perPage);
					}
					else {
						// a page id was sent , set this to the page
						$page = $this->page_number;
						// we have to start this record from where the initial page stops
						$start_r = ceil(($page -1) * $this->rec_perPage);
						// get result
						$the_result = $this->mydb->getallrow($this->fieldA,$this->valueA,$this->table_name,$this->sort,$this->order,$start_r,$this->rec_perPage);
					}
			    }
		    }
		    elseif ($this->fieldA != "None" && $this->fieldB != "None") {
				// use getallrow_two
				if ($this->totalRecord <= $this->rec_perPage) {
					// we have only one page, so set page to first page
					$page = 1;
					// perform operation
				    $the_result = $this->mydb->getallrow_two($this->fieldA,$this->valueA,$this->fieldB,$this->valueB,$this->table_name,$this->sort,$this->order);
				}
			   else {
					// The record spans more than one page
					// is this still the first page, i.e, page 1?
					if ($this->page_number == 0) {
						// no page was sent, so this first
						$page = 1;
						// get result
						$the_result = $this->mydb->getallrow_two($this->fieldA,$this->valueA,$this->fieldB,$this->valueB,$this->table_name,$this->sort,$this->order,"",$this->rec_perPage);
					}
					else {
						// a page id was sent , set this to the page
						$page = $this->page_number;
						// we have to start this record from where the initial page stops
						$start_r = ceil(($page -1) * $this->rec_perPage);
						// get result
						$the_result = $this->mydb->getallrow_two($this->fieldA,$this->valueA,$this->fieldB,$this->valueB,$this->table_name,$this->sort,$this->order,$start_r,$this->rec_perPage);
					}
			    }
			}


	   }
	   // return our resourse identifier for this result
	   $this->tableResult = $the_result;
	   return $this->tableResult;

	}


  /**
	 * Function that returns the total records in the given table in the database
	 *
	 * This function returns the total records based on our query/search
	 * You can use this function to test for any result so that you can display a very
	 * neat error message for your visitors if no record is available yet in the table.
	 *
	 * EXAMPLE
	 *
	 * <code>
	 * Usage
	 * =====
	 * $mypageclass = new SamPaginator(.......);
	 * // check if there are records in the table
	 * if ($mypageclass->getTotalRecords() ==0) { // No records
	 *	?>
	 *	<!-- Dispaly message -->
	 *	 <div> Sorry, There are no Records in this Table yet. </div>
	 *
	 *	<?php
	 *	 }
	 * </code>
	 *
	 * @access Public
	 * @return integer $totalrec
	 */
	 function getTotalRecords(){

	 	// Work on the values we need to use
		if ($this->fieldA =="None" && $this->fieldB != "None") {
			$this->displayerror("Sorry, can not jump the fields, If you want to serch only one field, then use the first field and first value instead.");
		}
		if (($this->fieldA =="None" && $this->valueA != "None") || ($this->fieldB =="None" && $this->valueB != "None")) {
			$this->displayerror("Sorry, A corresponding value for the field was not provided. Execution Can not continue.");
		}

		// 1. Are we doing a search or normal query
		if (strtoupper($this->operation) == "S") {
			// check if there is no mixup
			if (($this->fieldA != "None" && $this->fieldB != "None") || ($this->fieldA == "None" && $this->fieldB == "None")) {
				// tell user, this shouldn't be a search
				$this->displayerror("Sorry, This is a wrong call for this action. This shouldn't be a search. Can not continue.");
			}

			// we can go ahead
			//A. Lets get all records first
		  $total_res = $this->mydb->dosearch($this->valueA,$this->table_name,$this->fieldA);

		}
		elseif (strtoupper($this->operation) == "Q") {
			// We have to check for some instances
			if ($this->fieldA == "None" && $this->fieldB == "None") {
				// use getallresult
				 $total_res = $this->mydb->getallresult($this->table_name);

			}
			elseif ($this->fieldA != "None" && $this->fieldB == "None") {
				// use getallrow
				$total_res = $this->mydb->getallrow($this->fieldA,$this->valueA,$this->table_name);
			}
			elseif ($this->fieldA != "None" && $this->fieldB != "None") {
				// use getallrow_two
				$total_res = $this->mydb->getallrow_two($this->fieldA,$this->valueA,$this->fieldB,$this->valueB,$this->table_name);
			}

		}
		// COUNT THE RECORD
		$totalrec = @mysql_numrows($total_res);
		return $totalrec;
	 }
 /**
 *  Function to get child table in directory display
 *  
 */
  function getChild($childtable,$odby,$catid,$total)
  {
     $cresult = $this->mydb->getallrow("cid",$catid,$childtable,$odby,$this->order,"0",$total);
     return $cresult;
  }

/**
	 * Function that display the records in a single lines with headings
	 *
	 * This function displays your records on the page using your supplied parameters.<br />
	 * The 'Type of Record' parameter is very important. This is what tells the function
	 * how the display of your records will be handled.<br />
	 *
	 * <code>
	 * Usage
	 * =====
	 * $mypageclass = new SamPaginator(.......);
	 * // check if there are records in the table
	 * if ($mypageclass->getTotalRecords() ==0) { // No records
	 *	?>
	 *	<!-- Dispaly message -->
	 *	 <div> Sorry, There are no Records in this Table yet. </div>
	 *
	 *	<?php
	 *	 }
	 *  else { // we have results
	 *    // Display our records
	 *   $mypageclass->showAllRecords();
	 *
	 * }
	 * </code>
	 *
	 * @param
	 *
	 * @access Public
	 * @return void
	 */
	 function showAllRecords(){
	 	// make sure necessary parameters are supplied
	 	// To be implemented in Next version

	 }
    
/**
    * Function that display the records in a single lines with headings
    *
    * This function displays your records on the page using your supplied parameters.<br />
    * The 'Type of Record' parameter is very important. This is what tells the function
    * how the display of your records will be handled.<br />
    *
    * <code>
    * Usage
    * =====
    * $mypageclass = new SamPaginator(.......);
    * // check if there are records in the table
    * if ($mypageclass->getTotalRecords() ==0) { // No records
    *   ?>
    *   <!-- Dispaly message -->
    *    <div> Sorry, There are no Records in this Table yet. </div>
    *
    *   <?php
    *    }
    *  else { // we have results
    *    // Display our records
    *   $mypageclass->showAllRecords();
    *
    * }
    * </code>
    *
    * @param
    *
    * @access Public
    * @return void
    */
    function showDirectory($titleField,$linkPageUrl="",$imageField="",$imagePathPrefix="",$BodycssClass="",$displaychild=0,$childtable="",$cat_id_field="",$childPageUrl="",$ctable_idfield="",$ctable_titlefield=""){
          // make sure necessary parameters are supplied
       if ($titleField =="" ) {
          // This is not allowed
          $this->displayerror("Sorry, The Title field must be supplied. Can not continue.");

       }
       // check child table
       if ($displaychild ==1 && $childtable == "" ) {
          // This is not allowed
          $this->displayerror("Sorry, The Childtable and the Childtable Id Field must be supplied. Can not continue.");

       }
       // get child table details
        if ($childtable == "" && $cat_id_field !="") {
          // This is not allowed
          $this->displayerror("Sorry, The Childtable and the Category ID Id Field in the Childtable must be supplied. Can not continue.");

        }
       // all set we can move on
       // So let's get all the record we are to display
          if (!$this->tableResult) {
             $my_resource = $this->getResults();
          }
          else {
             $my_resource = $this->tableResult;
          }
          // done, so begin the display
          echo "<table align=\"center\" border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=\"100%\">";
           // intialize our marker
         $n = 0;
         echo "<tr align=\"center\" valign=\"top\">";
         while ($display = @mysql_fetch_array($my_resource)) {
            // get child result for this Category
            if ($displaychild ==1 || $childtable != "" )
             {
                $chresource = $this->getChild($childtable,$ctable_idfield,$display[$this->ID_field_name],3);
             }
             //Get total child for this category
             if ($cat_id_field !="") {
                $tchild = $this->mydb->getrecords($childtable,$cat_id_field,$display[$this->ID_field_name]);
                $totalchild = "<small style=\"font-size: 10px; \"><i> (". $tchild .")</i></small> ";
             }
             
            if ($n % $this->columns_per_row == 0 && $n !=0) {
             // we already have the required number of columns
            // So close the row and start another row
               echo "</tr>\n";
               echo "<tr align=\"center\" valign=\"top\">";
            }
            // Do we need to display any associated picture/image?
            if ($imageField == "") {
              // We are not displaying any picture or image
               // begin columns
                   if ($linkPageUrl =="") {
                      echo "<td align=\"left\" width=\"".floatval(100/$this->columns_per_row)."%\"> <div style=\"font-family: HeliosCond, Arial, Verdana; font-size: 14px; color: #333366; font-weight: bolder \">".$display[$titleField] ." ". $totalchild ." </div>";
                   }
                   else {
                      echo "<td align=\"left\" width=\"".floatval(100/$this->columns_per_row)."%\"> <div style=\"font-family: HeliosCond, Arial, Verdana; font-size: 14px; color: #333366; font-weight: bolder \"><a href=\"". $linkPageUrl ."?$this->ID_field_name=". $display[$this->ID_field_name] ."\">".$display[$titleField] ." ". $totalchild ." </a></div>";
                   }
                   
                    // display child 
                   if ($displaychild ==1) { 
                     echo "<div class=\"$BodycssClass\">";
                     while ($mchild = @mysql_fetch_array($chresource)){ 
                        if ($childPageUrl ==""){  
                         echo $mchild[$ctable_titlefield] ." , <br /> ";
                        }
                        else {
                           echo "<a href=\"". $childPageUrl ."?$ctable_idfield=". $mchild[$ctable_idfield] ."\">". $mchild[$ctable_titlefield] ."</a> , <br /> ";
                        }
                     }
                     echo "... </div>"; 
                   }
                   echo " </td>"; 

            } // END IF - we are not displaying image
            elseif ($imageField != "") {
              // We are displaying the picture or image
                  // any image prefix path provided?
                  if ($imagePathPrefix == "") {
                       // begin columns
                      if ($linkPageUrl =="") {
                         echo "<td align=\"left\" width=\"".floatval(100/$this->columns_per_row)."%\"> <div style=\"font-family: HeliosCond, Arial, Verdana; font-size: 14px; color: #333366; font-weight: bolder \">".$display[$titleField] ." ". $totalchild ." </div>";
                      }
                      else {
                         echo "<td align=\"left\" width=\"".floatval(100/$this->columns_per_row)."%\"> <div style=\"font-family: HeliosCond, Arial, Verdana; font-size: 14px; color: #333366; font-weight: bolder \"><a href=\"". $linkPageUrl ."?$this->ID_field_name=". $display[$this->ID_field_name] ."\">".$display[$titleField] ." ". $totalchild ." </a></div>";
                      }
                     
                     // incase the particular article does not have image uploaded for it, 
                     // we should be able to handle this. 
                       if (!$display[$imageField]) {
                        // this particular article/title has no image
                        // display child 
                         if ($displaychild ==1) { 
                           echo "<div class=\"$BodycssClass\">";
                           while ($mchild = @mysql_fetch_array($chresource)){ 
                              if ($childPageUrl ==""){  
                               echo $mchild[$ctable_titlefield] ." , <br /> ";
                              }
                              else {
                                 echo "<a href=\"". $childPageUrl ."?$ctable_idfield=". $mchild[$ctable_idfield] ."\">". $mchild[$ctable_titlefield] ."</a> , <br />  ";
                              }
                           }
                           echo "... </div>"; 
                         }
                         echo " </td>"; 
                        }
                        else {
                           // it has image so display it
                           echo "<div class=\"$BodycssClass\"><img src=\"".$display[$imageField] ."\" align=\"left\" alt=\"" . stripslashes(strip_tags($display[$titleField]))."\" vspace=\"5\" hspace=\"5\"> </div><br />"; 
                           // display child 
                            if ($displaychild ==1) { 
                              echo "<div class=\"$BodycssClass\">";
                              while ($mchild = @mysql_fetch_array($chresource)){ 
                                 if ($childPageUrl ==""){  
                                  echo $mchild[$ctable_titlefield] ." , <br /> ";
                                 }
                                 else {
                                    echo "<a href=\"". $childPageUrl ."?$ctable_idfield=". $mchild[$ctable_idfield] ."\">". $mchild[$ctable_titlefield] ."</a> , <br /> ";
                                 }
                              }
                              echo "... </div>"; 
                            }
                            echo " </td>"; 
                         }
                  }
                  else { // image prefix not empty
                     // begin columns
                      if ($linkPageUrl =="") {
                         echo "<td align=\"left\" width=\"".floatval(100/$this->columns_per_row)."%\"> <div style=\"font-family: HeliosCond, Arial, Verdana; font-size: 14px; color: #333366; font-weight: bolder \">".$display[$titleField] ." ". $totalchild ." </div>";
                      }
                      else {
                         echo "<td align=\"left\" width=\"".floatval(100/$this->columns_per_row)."%\"> <div style=\"font-family: HeliosCond, Arial, Verdana; font-size: 14px; color: #333366; font-weight: bolder \"><a href=\"". $linkPageUrl ."?$this->ID_field_name=". $display[$this->ID_field_name] ."\">".$display[$titleField] ." ". $totalchild ." </a></div>";
                      }
                     // incase the particular article does not have image uploaded for it, 
                     // we should be able to handle this. 
                     if (!$display[$imageField]) {
                        // this particular article/title has no image
                        echo "<div class=\"$BodycssClass\"><img src=\"".$imagePathPrefix."/".$display[$imageField] ."\" align=\"left\" alt=\"" . stripslashes(strip_tags($display[$titleField]))."\" vspace=\"5\" hspace=\"5\"> <div> <br>";
                        if ($displaychild ==1) { 
                           echo "<div class=\"$BodycssClass\">";
                           while ($mchild = @mysql_fetch_array($chresource)){ 
                              if ($childPageUrl ==""){  
                               echo $mchild[$ctable_titlefield] ." , <br /> ";
                              }
                              else {
                                 echo "<a href=\"". $childPageUrl ."?$ctable_idfield=". $mchild[$ctable_idfield] ."\">". $mchild[$ctable_titlefield] ."</a> , <br /> ";
                              }
                           }
                           echo "... </div>"; 
                         }
                         echo " </td>";
                     }
                     else {
                        echo "<div class=\"$BodycssClass\"><img src=\"".$imagePathPrefix."/".$display[$imageField] ."\" align=\"left\" alt=\"" . stripslashes(strip_tags($display[$titleField]))."\" vspace=\"5\" hspace=\"5\"> </div></br />"; 
                         if ($displaychild ==1) { 
                           echo "<div class=\"$BodycssClass\">";
                           while ($mchild = @mysql_fetch_array($chresource)){ 
                              if ($childPageUrl ==""){  
                               echo $mchild[$ctable_titlefield] ." , <br /> ";
                              }
                              else {
                                 echo "<a href=\"". $childPageUrl ."?$ctable_idfield=". $mchild[$ctable_idfield] ."\">". $mchild[$ctable_titlefield] ."</a> , <br /> ";
                              }
                           }
                           echo "... </div>"; 
                         }
                         echo " </td>";
                     }
                   }


            } // END IF - we are displaying image

             $n++;
         } // END WHILE
         echo "</tr> </table>";

    }

/**
	 * Function that display the Artcles/News records in the desired format
	 *
	 * This function displays your Articles/News records on the page using your supplied parameters.<br />
	 * The parameter is very important. This is what tells the function
	 * how the display of your records will be handled.<br />
	 *
	 * <code>
	 * Usage
	 * =====
	 * $mypageclass = new SamPaginator(.......);
	 * // check if there are records in the table
	 * if ($mypageclass->getTotalRecords() ==0) { // No records
	 *	?>
	 *	<!-- Dispaly message -->
	 *	 <div> Sorry, There are no Records in this Table yet. </div>
	 *
	 *	<?php
	 *	 }
	 *  else { // we have results
	 *    // Display our records
	 *   $mypageclass->showArticles("articletitle","details",200,"viewarticle.php","","","mybodycss");
	 *
	 * }
	 * </code>
	 * 
	 * @param string Name of the Title/Topic field of the article/news records in the table
	 * @param string Name of the Content/Body field for the News/Article records in the table
	 * @param integer Number of characaters to display for the Content/Body field [Optional]. It will display full Article/News if not specified.
	 * @param string URL of the page to link the article/News to [OPTIONAL] (possibly to display full article)
	 * @param string Name of the Main Picture/Image field for the News/Article if it has a picture associated with it [OPTIONAL].
	 * @param String Name of the image path prefix [Folder] if you store the path to your image in the database and you need to prefix the path.
	 * @param string Name of CSS Class  defined for the Content/Body of the article/news
	 * @param string Name of the Time Posted/modified field of the article/news records in the table
	 * @param string Name of the Author field of the News/Article records in the table 
	 *
	 * @access Public
	 * @return void
	 */
	 function showArticles($titleField,$contentField,$totalCharaters="",$linkPageUrl="",$imageField="",$imagePathPrefix="",$BodycssClass="",$PostTimeField="",$Author=""){
	 	// make sure necessary parameters are supplied
	 	if ($titleField =="" || $contentField == "" ) {
	 		// This is not allowed
	 		$this->displayerror("Sorry, The Title field and the Content Field must be supplied. Can not continue.");

	 	}
	 	// all set we can move on
	 	// So let's get all the record we are to display
	 		if (!$this->tableResult) {
	 			$my_resource = $this->getResults();
	 		}
	 		else {
	 			$my_resource = $this->tableResult;
	 		}
	 		// done, so begin the display
	 		echo "<table align=\"center\" border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=\"100%\">";
	 		 // intialize our marker
			$n = 0;
			echo "<tr align=\"center\" valign=\"top\">";
			while ($display = @mysql_fetch_array($my_resource)) {

				if ($n % $this->columns_per_row == 0 && $n !=0) {
			    // we already have the required number of columns
				// So close the row and start another row
					echo "</tr>\n";
					echo "<tr align=\"center\" valign=\"top\">";
				}
				// Do we need to display any associated picture/image?
				if ($imageField == "") {
	 		    // We are not displaying any picture or image
			 		if ($totalCharaters !="") {
			 			// we will limit the content/body field by characters
			 			// begin columns
			 			if ($linkPageUrl =="" ) {
			 				echo "<td align=\"left\" width=\"".floatval(100/$this->columns_per_row)."%\"> <div style=\"font-family: HeliosCond, Arial, Verdana; font-size: 13px; color: #333366; font-weight: bold \">".$display[$titleField] ." </div>";
			 			}
			 			else {
			 				echo "<td align=\"left\" width=\"".floatval(100/$this->columns_per_row)."%\"> <div style=\"font-family: HeliosCond, Arial, Verdana; font-size: 13px; color: #333366; font-weight: bold \"><a href=\"". $linkPageUrl ."?$this->ID_field_name=". $display[$this->ID_field_name] ."\">".$display[$titleField] ." </a></div>";
			 			}
			 			//***** NEW CHANGE ****///
			 			//Do we show the Author field
			 			if ($Author !="") {
			 				if ($display[$Author]) {
				   			 	echo "<div align=\"left\" style=\"font-size: 10px; font-family: Tahoma; color: blue\"> Author: <b> $display[$Author] </b></div>";
				   			 } 
			 			}
			 			//Do we show the date modified field
			 			if ($PostTimeField !="") {
			 				 if ($display[$PostTimeField]) {
			 				 	$myfdate = $this->myDateformat($display[$PostTimeField]);
				   			 	echo "<div align=\"left\" style=\"font-size: 10px; font-family: Tahoma; color: blue\">Date Updated: <b> $myfdate </b></div>";
				   			 } 	
			 			}	
			 			//*** end new change ***//		
						echo "<div class=\"$BodycssClass\">".nl2br(substr(stripslashes(strip_tags($display[$contentField])),0,$totalCharaters)) ."... </div> </td>";
			 		}
			 		else {
			 			// No need to limit content/body display
			 			// begin columns
			 			if ($linkPageUrl =="") {
			 				echo "<td align=\"left\" width=\"".floatval(100/$this->columns_per_row)."%\"> <div style=\"font-family: HeliosCond, Arial, Verdana; font-size: 13px; color: #333366; font-weight: bold \">".$display[$titleField] ." </div>";
			 			}
			 			else {
			 				echo "<td align=\"left\" width=\"".floatval(100/$this->columns_per_row)."%\"> <div style=\"font-family: HeliosCond, Arial, Verdana; font-size: 13px; color: #333366; font-weight: bold \"><a href=\"". $linkPageUrl ."?$this->ID_field_name=". $display[$this->ID_field_name] ."\">".$display[$titleField] ." </a></div>";
			 			}
			 			//***** NEW CHANGE ****///
			 			//Do we show the Author field
			 			if ($Author !="") {
			 				if ($display[$Author]) {
				   			 	echo "<div align=\"left\" style=\"font-size: 10px; font-family: Tahoma; color: blue\"> Author: <b> $display[$Author] </b></div>";
				   			 } 
			 			}
			 			//Do we show the date modified field
			 			if ($PostTimeField !="") {
			 				 if ($display[$PostTimeField]) {
			 				 	$myfdate = $this->myDateformat($display[$PostTimeField]);
				   			 	echo "<div align=\"left\" style=\"font-size: 10px; font-family: Tahoma; color: blue\">Date Updated: <b> $myfdate </b></div>";
				   			 } 	
			 			}	
			 			//*** end new change ***//
						echo "<div class=\"$BodycssClass\">".nl2br(stripslashes(strip_tags($display[$contentField]))) ." </div> </td>";
			 		}

				} // END IF - we are not displaying image
				elseif ($imageField != "") {
	 		    // We are displaying the picture or image
	 		        // any image prefix path provided?
	 		        if ($imagePathPrefix == "") {
	 		        	if ($totalCharaters !="") {
			 			// we will limit the content/body field by characters
			 			// begin columns
				 			if ($linkPageUrl =="" ) {
				 				echo "<td align=\"left\" width=\"".floatval(100/$this->columns_per_row)."%\"> <div style=\"font-family: HeliosCond, Arial, Verdana; font-size: 13px; color: #333366; font-weight: bold \">".$display[$titleField] ." </div>";
				 			}
				 			else {
				 				echo "<td align=\"left\" width=\"".floatval(100/$this->columns_per_row)."%\"> <div style=\"font-family: HeliosCond, Arial, Verdana; font-size: 13px; color: #333366; font-weight: bold \"><a href=\"". $linkPageUrl ."?$this->ID_field_name=". $display[$this->ID_field_name] ."\">".$display[$titleField] ." </a></div>";
				 			}
				 			//***** NEW CHANGE ****///
				 			//Do we show the Author field
				 			if ($Author !="") {
				 				if ($display[$Author]) {
					   			 	echo "<div align=\"left\" style=\"font-size: 10px; font-family: Tahoma; color: blue\"> Author: <b> $display[$Author] </b></div>";
					   			 } 
				 			}
				 			//Do we show the date modified field
				 			if ($PostTimeField !="") {
				 				 if ($display[$PostTimeField]) {
				 				 	$myfdate = $this->myDateformat($display[$PostTimeField]);
					   			 	echo "<div align=\"left\" style=\"font-size: 10px; font-family: Tahoma; color: blue\">Date Updated: <b> $myfdate </b></div>";
					   			 } 	
				 			}	
				 			//*** end new change ***//
				 			//*** NEW CHANGE *******///
							// incase the particular article does not have image uploaded for it, 
							// we should be able to handle this. 
							if (!$display[$imageField]) {
								// this particular article/title has no image
								echo "<div class=\"$BodycssClass\">". nl2br(substr(stripslashes(strip_tags($display[$contentField])),0,$totalCharaters)) ."... </div> </td>";
							}
							else {
								// it has image so display it
								echo "<div class=\"$BodycssClass\"><img src=\"".$display[$imageField] ."\" align=\"left\" alt=\"" . stripslashes(strip_tags($display[$titleField]))."\" vspace=\"5\" hspace=\"5\"> ". nl2br(substr(stripslashes(strip_tags($display[$contentField])),0,$totalCharaters)) ."... </div> </td>";
							}
							
				 		}
				 		else {
				 			// No need to limit content/body display
				 			// begin columns
				 			if ($linkPageUrl =="") {
				 				echo "<td align=\"left\" width=\"".floatval(100/$this->columns_per_row)."%\"> <div style=\"font-family: HeliosCond, Arial, Verdana; font-size: 13px; color: #333366; font-weight: bold \">".$display[$titleField] ." </div>";
				 			}
				 			else {
				 				echo "<td align=\"left\" width=\"".floatval(100/$this->columns_per_row)."%\"> <div style=\"font-family: HeliosCond, Arial, Verdana; font-size: 13px; color: #333366; font-weight: bold \"><a href=\"". $linkPageUrl ."?$this->ID_field_name=". $display[$this->ID_field_name] ."\">".$display[$titleField] ." </a></div>";
				 			}
				 			//***** NEW CHANGE ****///
				 			//Do we show the Author field
				 			if ($Author !="") {
				 				if ($display[$Author]) {
					   			 	echo "<div align=\"left\" style=\"font-size: 10px; font-family: Tahoma; color: blue\"> Author: <b> $display[$Author] </b></div>";
					   			 } 
				 			}
				 			//Do we show the date modified field
				 			if ($PostTimeField !="") {
				 				 if ($display[$PostTimeField]) {
				 				 	$myfdate = $this->myDateformat($display[$PostTimeField]);
					   			 	echo "<div align=\"left\" style=\"font-size: 10px; font-family: Tahoma; color: blue\">Date Updated: <b> $myfdate </b></div>";
					   			 } 	
				 			}	
				 			//*** end new change ***//
				 			//*** NEW CHANGE *******///
							// incase the particular article does not have image uploaded for it, 
							// we should be able to handle this. 
							if (!$display[$imageField]) {
								// this particular article/title has no image
								echo "<div class=\"$BodycssClass\">". nl2br(stripslashes(strip_tags($display[$contentField]))) ." </div> </td>";
							}
							else {
								echo "<div class=\"$BodycssClass\"><img src=\"".$display[$imageField] ."\" align=\"left\" alt=\"" . stripslashes(strip_tags($display[$titleField]))."\" vspace=\"5\" hspace=\"5\"> ". nl2br(stripslashes(strip_tags($display[$contentField]))) ." </div> </td>";
							}
						}
	 		        }
	 		        else { // image prefix not empty
	 		        	if ($totalCharaters !="") {
			 			// we will limit the content/body field by characters
			 			// begin columns
				 			if ($linkPageUrl =="" ) {
				 				echo "<td align=\"left\" width=\"".floatval(100/$this->columns_per_row)."%\"> <div style=\"font-family: HeliosCond, Arial, Verdana; font-size: 13px; color: #333366; font-weight: bold \">".$display[$titleField] ." </div>";
				 			}
				 			else {
				 				echo "<td align=\"left\" width=\"".floatval(100/$this->columns_per_row)."%\"> <div style=\"font-family: HeliosCond, Arial, Verdana; font-size: 13px; color: #333366; font-weight: bold \"><a href=\"". $linkPageUrl ."?$this->ID_field_name=". $display[$this->ID_field_name] ."\">".$display[$titleField] ." </a></div>";
				 			}
				 			//***** NEW CHANGE ****///
				 			//Do we show the Author field
				 			if ($Author !="") {
				 				if ($display[$Author]) {
					   			 	echo "<div align=\"left\" style=\"font-size: 10px; font-family: Tahoma; color: blue\"> Author: <b> $display[$Author] </b></div>";
					   			 } 
				 			}
				 			//Do we show the date modified field
				 			if ($PostTimeField !="") {
				 				 if ($display[$PostTimeField]) {
				 				 	$myfdate = $this->myDateformat($display[$PostTimeField]);
					   			 	echo "<div align=\"left\" style=\"font-size: 10px; font-family: Tahoma; color: blue\">Date Updated: <b> $myfdate </b></div>";
					   			 } 	
				 			}	
				 			//*** end new change ***//
							//*** NEW CHANGE *******///
							// incase the particular article does not have image uploaded for it, 
							// we should be able to handle this. 
							if (!$display[$imageField]) {
								// this particular article/title has no image
								echo " <div class=\"$BodycssClass\">". nl2br(substr(stripslashes(strip_tags($display[$contentField])),0,$totalCharaters)) ."... </div> </td>";
							}
							else {
								echo " <div class=\"$BodycssClass\"><img src=\"".$imagePathPrefix."/".$display[$imageField] ."\" align=\"left\" alt=\"" . stripslashes(strip_tags($display[$titleField]))."\" vspace=\"5\" hspace=\"5\"> ". nl2br(substr(stripslashes(strip_tags($display[$contentField])),0,$totalCharaters)) ."... </div> </td>";
							}
						}
				 		else {
				 			// No need to limit content/body display
				 			// begin columns
				 			if ($linkPageUrl =="") {
				 				echo "<td align=\"left\" width=\"".floatval(100/$this->columns_per_row)."%\"> <div style=\"font-family: HeliosCond, Arial, Verdana; font-size: 13px; color: #333366; font-weight: bold \">".$display[$titleField] ." </div>";
				 			}
				 			else {
				 				echo "<td align=\"left\" width=\"".floatval(100/$this->columns_per_row)."%\"> <div style=\"font-family: HeliosCond, Arial, Verdana; font-size: 13px; color: #333366; font-weight: bold \"><a href=\"". $linkPageUrl ."?$this->ID_field_name=". $display[$this->ID_field_name] ."\">".$display[$titleField] ." </a></div>";
				 			}
				 			//***** NEW CHANGE ****///
				 			//Do we show the Author field
				 			if ($Author !="") {
				 				if ($display[$Author]) {
					   			 	echo "<div align=\"left\" style=\"font-size: 10px; font-family: Tahoma; color: blue\"> Author: <b> $display[$Author] </b></div>";
					   			 } 
				 			}
				 			//Do we show the date modified field
				 			if ($PostTimeField !="") {
				 				 if ($display[$PostTimeField]) {
				 				 	$myfdate = $this->myDateformat($display[$PostTimeField]);
					   			 	echo "<div align=\"left\" style=\"font-size: 10px; font-family: Tahoma; color: blue\">Date Updated: <b> $myfdate </b></div>";
					   			 } 	
				 			}	
				 			//*** end new change ***//
				 			//*** NEW CHANGE *******///
							// incase the particular article does not have image uploaded for it, 
							// we should be able to handle this. 
							if (!$display[$imageField]) {
								// this particular article/title has no image
								echo "<div class=\"$BodycssClass\"><img src=\"".$imagePathPrefix."/".$display[$imageField] ."\" align=\"left\" alt=\"" . stripslashes(strip_tags($display[$titleField]))."\" vspace=\"5\" hspace=\"5\"> ". nl2br(stripslashes(strip_tags($display[$contentField]))) ." </div> </td>";
							}
							else {
								echo "<div class=\"$BodycssClass\"><img src=\"".$imagePathPrefix."/".$display[$imageField] ."\" align=\"left\" alt=\"" . stripslashes(strip_tags($display[$titleField]))."\" vspace=\"5\" hspace=\"5\"> ". nl2br(stripslashes(strip_tags($display[$contentField]))) ." </div> </td>";
							}
				 		}
	 		        }


				} // END IF - we are displaying image

				 $n++;
			} // END WHILE
			echo "</tr> </table>";

	 }

/**
	 * Function that display the Picture Gallery records in the desired format
	 *
	 * This function displays your Photo Gallery records on the page using your supplied parameters.<br />
	 * The parameter is very important. This is what tells the function
	 * how the display of your records will be handled.<br />
	 *
	 * <code>
	 * Usage
	 * =====
	 * $mypageclass = new SamPaginator(.......);
	 * // check if there are records in the table
	 * if ($mypageclass->getTotalRecords() ==0) { // No records
	 *	?>
	 *	<!-- Dispaly message -->
	 *	 <div> Sorry, There are no Records in this Table yet. </div>
	 *
	 *	<?php
	 *	 }
	 *  else { // we have results
	 *    // Display our records
	 *   $mypageclass->ShowPhotoGallery("picaddress","imagetitle","showimage.php","photos","mycssid");
	 *
	 * }
	 * </code>
	 *
	 * @param string Name of the image/picture field in the table
	 * @param string Name of the Image title/description field in the table
	 * @param string URL of the page to link the picture/image to [OPTIONAL] (possibly to display bigger picture with more details or dscription)
	 * @param String Name of the image path prefix [Folder] if you store the path to your image in the database and you need to prefix the path.
	 * @param string Name of CSS ID defined for the display of the Image Title/description
	 *
	 * @access Public
	 * @return void
	 */
	 function showPhotoGallery($imageField,$imageTitleField,$linkPageUrl="",$imagePathPrefix="",$cssID=""){
	 	// make sure necessary parameters are supplied
	 	if ($imageField =="" || $imageTitleField == "" ) {
	 		// This is not allowed
	 		$this->displayerror("Sorry, The Image field and the Image Title Field must be supplied. Can not continue.");

	 	}
	 	// all set we can move on
	 	// So let's get all the record we are to display
	 		if (!$this->tableResult) {
	 			$my_resource = $this->getResults();
	 		}
	 		else {
	 			$my_resource = $this->tableResult;
	 		}
	 		// done, so begin the display
	 		echo "<table align=\"center\" border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=\"100%\">";
	 		 // intialize our marker
			$n = 0;
			echo "<tr align=\"center\" valign=\"top\">";
			while ($display = @mysql_fetch_array($my_resource)) {

				if ($n % $this->columns_per_row == 0 && $n !=0) {
			    // we already have the required number of columns
				// So close the row and start another row
					echo "</tr>\n";
					echo "<tr align=\"center\" valign=\"top\">";
				}

			 	if ($imagePathPrefix !="") {
			 		// we have image path prefix for this image/pictures
			 		// begin columns
			 		if ($linkPageUrl =="" ) {
			 			echo "<td align=\"center\" width=\"".floatval(100/$this->columns_per_row)."%\"><table><tr><td> <div><img src=\"".$imagePathPrefix."/".$display[$imageField] ."\" align=\"left\" alt=\"" . stripslashes(strip_tags($display[$imageTitleField]))."\"> </div></td></tr>";
			 		}
			 		else {
			 			echo "<td align=\"center\" width=\"".floatval(100/$this->columns_per_row)."%\"><table><tr><td> <div><a href=\"". $linkPageUrl ."?id=". $display[$this->ID_field_name] ."\"><img src=\"".$imagePathPrefix."/".$display[$imageField] ."\" align=\"left\" alt=\"" . stripslashes(strip_tags($display[$imageTitleField]))."\"> </a></div></td></tr>";
			 		}

					   echo "<tr><td><div id=\"$cssID\">".nl2br(stripslashes(strip_tags($display[$imageTitleField]))) ." </div></td></tr></table> </td>";
			 	}
			 	else {
			 		// We don't need image prefix
			 		// begin columns
			 		if ($linkPageUrl =="") {
			 			echo "<td align=\"center\" width=\"".floatval(100/$this->columns_per_row)."%\"><table><tr><td> <div><img src=\"".$display[$imageField] ."\" align=\"left\" alt=\"" . stripslashes(strip_tags($display[$imageTitleField]))."\">  </div></td></tr>";
			 		}
			 		else {
			 			echo "<td align=\"center\" width=\"".floatval(100/$this->columns_per_row)."%\"><table><tr><td> <div><a href=\"". $linkPageUrl ."?id=". $display[$this->ID_field_name] ."\"><img src=\"".$display[$imageField] ."\" align=\"left\" alt=\"" . stripslashes(strip_tags($display[$imageTitleField]))."\"> </a></div></td></tr>";
			 		}
						echo "<tr><td><div id=\"$cssID\">".nl2br(stripslashes(strip_tags($display[$imageTitleField]))) ." </div></td></tr></table> </td>";
			 	}


				 $n++;
			} // END WHILE
			echo "</tr> </table>";

	 }


/**
	 * Function that display the page numbers at the lower part of the page
	 *
	 * This function will display the page numbers using the style you define in the classes
	 * for both the present page and other pages.
	 *
	 * <code>
	 * Usage
	 * =====
	 * $mypageclass = new SamPaginator(.......);
	 * // check if there are records in the table
	 * if ($mypageclass->getTotalRecords() ==0) { // No records
	 *	?>
	 *	<!-- Dispaly message -->
	 *	 <div> Sorry, There are no Records in this Table yet. </div>
	 *
	 *	<?php
	 *	 }
	 *  else { // we have results
	 *    // Display our records
	 *    .........
	 *    // display page numbers
	 *   $mypageclass->showPagenumber("photogallery.php","","cid","","p_navb","p_nava");
	 *  }
	 * </code>
	 * @param string Url of the present page using this class
	 * @param string Method to use for submission of page POST or GET [OPTIONAL] Default is GET
	 * @param string Label for the firstfield value you use in loading this page [OPTIONAL]
	 * @param string Value of the Label for the firstfield value you use in loading this page [OPTIONAL]
	 * @param string Label for the secondfield value you use in loading this page [OPTIONAL]
	 * @param string Value of the Label for the secondfield value you use in loading this page [OPTIONAL]
	 * @param string css_class name for the display of present page number [OPTIONAL]
	 * @param string css_class name for the display of other page numbers [OPTIONAL]
	 *
	 * @access Public
	 * @return integer $totalrec
	 */
	 function showPagenumber($pageurl,$sub_method="",$labelA="",$labelA_val="",$labelB="",$labelB_val="",$cssClassA="",$cssClassB=""){
	 	// Hope the url of this page is supplied?
	 	if ($pageurl =="") {
	 		// This is not allowed
	 		$this->displayerror("Sorry, The URL of this page was not supplied. Can not continue.");

	 	}
	 	// Don't allow jumping of optional fields
	 	if ($labelA =="" && $labelB != "") {
	 		// This is not allowed
	 		$this->displayerror("Sorry, You must not jump over the Optional fields. Can not continue.");

	 	}
	 	if ($sub_method =="") {
	 		// set it to GET
	 		$sub_method = "GET";
	 	}
	 	// we are okay so far
	 	// use this as url joiner in case user is passing  a url with pair value already
	 	if (strpos($pageurl,"?") !== false) {
	 		$jn = "&";
	 	}
	 	else {
	 		$jn = "?";
	 	}
	 	// let's settle page numbering
	 	// Get the correct page identity
	 	if ($this->page_number == 0) {
	 		// This should be page 1
	 		$thispage = 1;
	 	}
	 	else {
	 		$thispage = $this->page_number;
	 	}
	 	$minuspage = $thispage -1;
	 	$pluspage = $thispage +1;
	 	echo "<br />";
	 	echo "<p align=\"center\">";
	 	//DO WE HAVE THE LABELS PROVIDED
	 	if ($labelA != "" && $labelB !="") {
	 		// do we have more than one page record? then show page navigation
		 	if ($this->totalRecord > $this->rec_perPage) {
		 		// if we are not on page 1, show previous
				if ($thispage > 1) {
					echo "<b style=\"margin-right: 15px;\"><a href=\"".$pageurl.$jn ."pgn=".$minuspage ."&".$labelA ."=" .$labelA_val ."&".$labelB ."=" .$labelB_val ."\"> Previous </a> </b>";

			    }
			    // prepare the pages index
				for ($b=1; $b <= $this->totalPages; $b++){
					// display index pages
					if ($b == $thispage) { // Don't link the present page
						echo "<b class=\"$cssClassA\"> $b </b>";
			   	 	}
					else { // link other pages
					    echo "<b class=\"$cssClassB\"><a href=\"".$pageurl.$jn ."pgn=".$b ."&".$labelA ."=" .$labelA_val ."&".$labelB ."=" .$labelB_val ."\"> $b </a> </b>";

					}

			   }
			   // if we are not on last page, show next
			   if ($thispage < $this->totalPages) {
					echo "<b style=\"margin-left: 15px;\"><a href=\"".$pageurl.$jn ."pgn=".$pluspage ."&".$labelA ."=" .$labelA_val ."&".$labelB ."=" .$labelB_val ."\"> Next </a> </b>";

			   }
		 	}

	 	}
	 	elseif ($labelA != "" && $labelB =="") { // only on label provided

	 		// do we have more than one page record? then show page navigation
		 	if ($this->totalRecord > $this->rec_perPage) {
		 		// if we are not on page 1, show previous
				if ($thispage > 1) {
					echo "<b style=\"margin-right: 15px;\"><a href=\"".$pageurl.$jn ."pgn=".$minuspage ."&".$labelA ."=" .$labelA_val ."\"> Previous </a> </b>";

			    }
			    // prepare the pages index

				for ($b=1; $b <= $this->totalPages; $b++){
					// display index pages
					if ($b == $thispage) { // Don't link the present page
						echo "<b class=\"$cssClassA\"> $b </b>";
			   	 	}
					else { // link other pages
					    echo "<b class=\"$cssClassB\"><a href=\"".$pageurl.$jn ."pgn=".$b ."&".$labelA ."=" .$labelA_val ."\"> $b </a> </b>";

					}

			   }
			   // if we are not on last page, show next
			   if ($thispage < $this->totalPages) {
					echo "<b style=\"margin-left: 15px;\"><a href=\"".$pageurl.$jn ."pgn=".$pluspage ."&".$labelA ."=" .$labelA_val ."\"> Next </a> </b>";

			   }
		 	}
	 	}
	 	elseif ($labelA == "" && $labelB =="") { // No label provided
	 		// do we have more than one page record? then show page navigation
		 	if ($this->totalRecord > $this->rec_perPage) {
		 		// if we are not on page 1, show previous
				if ($thispage > 1) {
					echo "<b style=\"margin-right: 15px;\"><a href=\"".$pageurl.$jn ."pgn=".$minuspage ."\"> Previous </a> </b>";

			    }
			    // prepare the pages index
				for ($b=1; $b <= $this->totalPages; $b++){
					// display index pages
					if ($b == $thispage) { // Don't link the present page
						echo "<b class=\"$cssClassA\"> $b </b>";
			   	 	}
					else { // link other pages
					    echo "<b class=\"$cssClassB\"><a href=\"".$pageurl.$jn ."pgn=".$b ."\"> $b </a> </b>";

					}

			   }
			   // if we are not on last page, show next
			   if ($thispage < $this->totalPages) {
					echo "<b style=\"margin-left: 15px;\"><a href=\"".$pageurl.$jn ."pgn=".$pluspage ."\"> Next </a> </b>";

			   }
		 	}

	 	}

	 	     echo "</p>";

}

/**
	 * Function that display the title or header section for the records on each page
	 *
	 * This function can be used to display the title or header section of the display
	 * It takes the title of the record and the css id defined for the display as parameters
	 *
	 * <code>
	 * Usage
	 * =====
	 * $mypageclass = new SamPaginator(.......);
	 * // check if there are records in the table
	 * if ($mypageclass->getTotalRecords() ==0) { // No records
	 *	?>
	 *	<!-- Dispaly message -->
	 *	 <div> Sorry, There are no Records in this Table yet. </div>
	 *
	 *	<?php
	 *	 }
	 *  else { // we have results
	 *    // You can define the name of the your rcord or get it from database
	 *    $recordtitle = "Business Club";
	 * 	  // Displat Record header/Title
	 *   $mypageclass->showTitle($recordtitle,"s_result")
	 *    // Display our records
	 *    .........
	 *    // display page numbers
	 *    ..........
	 *  }
	 * </code>
	 * @param string Title of the Record we are displaying [OPTIONAL]
	 * @param string CSS ID for the display of the title [OPTIONAL]
	 *
	 * @access Public
	 * @return void
	 */
	 function showTitle($r_title="",$css_id=""){
	 	// Get the correct page identity
	 	if ($this->page_number == 0) {
	 		// This should be page 1
	 		$thispage = 1;
	 	}
	 	else {
	 		$thispage = $this->page_number;
	 	}
	 	// show supplied title
	 	if ($r_title == "") {
	 		echo "<div id=\"$css_id\">Displaying page <b> $thispage  </b> of  <b> $this->totalPages </b> pages. ( <b> $this->totalRecord </b>  Records)</div>";
	 	}
	 	else {
	 		echo "<div id=\"$css_id\">Displaying page <b> $thispage  </b> of  <b> $this->totalPages </b> pages for <b><i>$r_title</i></b>. ( <b> $this->totalRecord </b>  Records)</div>";
	 	}

	 }
	 
	 /**
	  * Function to format date values to a nice format with time
	  * The $dtval must be in the format 'yyyy-mm-dd' or 'yyyy-mm-dd hh:mm:ss'
	  *
	  * @name domydate_b
	  * @param $mddat, in the format 'yyyy-mm-dd'
	  *
	  * @access Private
	  * @return $mdval, in the format 'Wednesday, May 5th 2006' or 'Wednesday, May 5th 2006, 10:30PM'
	  */
	 function myDateformat($mddat){
	 	// check if the value id date or datetime
	 	if (ereg(":",$mddat) && ereg("-",$mddat)) {
	 		// this is datetime format
	 		if (ereg(" ",$mddat)) {
	 		// e.g 2006-05-20 19:23:30 (YYYY-MM-DD HH:MM:SS)
			$mflda = explode(" ",$mddat);
			// $mflda[0] = 2006-05-20; $mflda[1]= 19:23:20
			// break each one again
			$mf1 = explode("-", $mflda[0]);
			$mf2 = explode(":", $mflda[1]);
			// convert to fanciful date format
			$mdval = date("D, F jS Y, h:iA", mktime($mf2[0], $mf2[1], $mf2[2], $mf1[1], $mf1[2], $mf1[0]));
			}
		   return $mdval;
	 	}
	 	else {
	 		// this is just date format
	 		if (ereg("-",$mddat)) {
	 		// e.g 2006-05-20 (YYYY-MM-DD)
			$mfld = explode("-",$mddat);
			// $mfld[0] = 2006; $mfld[1]=05; $mfld[2]=20
			// convert to fanciful date format
			$mdval = date("l, F jS Y", mktime(0, 0, 0, $mfld[1], $mfld[2], $mfld[0]));
		   }

		   return $mdval;
	 	}

	 }

	 /**
	  * Function to display fine error messages if it occurs
	  * in the process of doing anything in this program.
	  *
	  * @name showmyerror
	  * @param string $terror, error message to display
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
	        <td align="center" class="fb"><br><br><h3>Sorry!</h3> <br> $terror <br><br><br>
			Click <b onclick="Javascript:history.back(-1);" style="color: blue; cursor: hand">GO BACK</b> to return
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