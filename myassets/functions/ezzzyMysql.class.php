<?php
    /**
    * A simple but powerful general purpose MySql PHP-PDO Class. <br />
    *
    * This is a very <b>simple</b> but <b>powerful</b> general purpose MySql PHP-PDO Class
    * which can be used to add, update, delete, fetch records from a MySql database <br />
    * It can also be used to get total records from a table and when fetching records, <br />
    * the result can be arranged in any order depending on you <br />
    * It has now been updated to allow searching a database and most of the functions 
    * has been expanded to do much more.
    * You can actually do much more with this very simple and powerful Mysql Class. <br />
    * EXAMPLES:
    *  <code>
    * $yourclass = new EzzzyMysql($dsn, $db_username, $db_password); // Takes your database connection values as parameter
    * </code>
    *
    * @copyright 2017  Ezekiel Aliyu & Samoltech Studios Limited {http://www.samoltech.com.ng}
    * @author Ezekiel Aliyu {zinconewton2@yahoo.com}
    * @version 2.00
    * @package EzzzyMysql_Class
    * @name EzzzyMysql
    */


    class EzzzyMysql extends PDO{
        //put your code here
        private $error;
        private $sql;
        private $bind;
        private $errorCallbackFunction;
        private $errorMsgFormat;


        public function __construct($dsn, $user = "", $passwd = "") {
            $options = array(
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false
            );

            try {
                parent::__construct($dsn, $user, $passwd, $options);
            } 
            catch (PDOException $e) {
                trigger_error($e->getMessage());
                return false;
            }
        }

        /**
        * Function to get the id of the last database operation
        * 
        * @return int lastinsertid
        */
        public function getLastid() {
            return $this->lastInsertId(); 
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
        * $yourclass = new EzzzyMysql($db_connection_string,$db_username,$db_password);
        * $value_to_get = $yourclass->getval($where,$whereval,$table,$toget);
        * </code>
        * 
        * @param type $where
        * @param type $whereval
        * @param type $table
        * @param type $toget
        * @return the field value of $toget
        */
        public function getval($where,$whereval,$table,$toget) {
            // use prepare statement
            $bind = array(
                ":whereval" => "$whereval"
            );
            //
            $mwhere = $where ."=:whereval";
            $fres = $this->select($table,$mwhere,$bind,$toget);
            if(FALSE !== $fres){
                foreach ($fres as $ffv) {
                    $fieldval = $ffv[$toget];
                }
                return @$fieldval;
            }
            else{
                return false;
            }
        }

        /**
        *  Function to run a free SQL query
        * 
        * @param string $query
        * @param array $bind
        * @return int rowsaffected/result array or False on error
        */
        public function runQuery($query,$bind='') {
            return $this->run($query,$bind);
        }

        /**
        * Function to get a value data from the database when a minimum and maximum value is specified.
        * Special Function 
        * It will get a value data from a table between two maximum and minumum values
        * 
        * @param type $minval
        * @param type $maxval
        * @param type $checkval
        * @param type $table
        * @param type $toget
        * @return boolean
        */
        public function getPoint($minval,$maxval,$checkval,$table,$toget) {
            // use prepare statement
            $bind = array(
                ":checkval" => "$checkval"
            );
            //
            $mwhere = $minval ."<=:checkval AND ".$maxval .">=:checkval";
            $fres = $this->select($table,$mwhere,$bind,$toget);
            if(FALSE !== $fres){
                foreach ($fres as $ffv) {
                    $fieldval = $ffv[$toget];
                }
                return $fieldval;
            }
            else{
                return false;
            }
        }

        /**
        * Function to get values of rows that satisfies conditions $btw1 and $btw2
        * 
        * @param type $btw1
        * @param type $btw2
        * @param type $field
        * @param type $table
        * @param type $orderby
        * @param type $order
        * @param type $offset
        * @param type $total
        * @return boolean
        */
        public function getbtwrow($btw1,$btw2,$field,$table,$orderby='',$order="",$offset="",$total="") {
            // use prepare statement
            $bind = array(
                ":btw1" => "$btw1",
                ":btw2" => "$btw2"
            );
            //
            $mwhere = $field .">=:btw1 AND ".$field ."<=:btw2";
            // make chacks order and orderby
            if ($orderby !="" && $order !="") {
                $order = strtoupper($order);
                if ($order =="A") {
                    $AD = "ASC";
                }
                elseif ($order =="D") {
                    $AD = "DESC";
                }
                $morder ="ORDER BY ". $orderby ." ". $AD;
            }
            else{
                $morder ="";
            }
            // make checks offset
            if (!empty($total) && !empty($offset)){
                $morder .= " LIMIT " . $offset .",". $total;
            }
            elseif(!empty($total) && empty($offset)) {
                $morder .= " LIMIT ". $total;
            }
            else{
                $morder ="";
            }
            $morder = trim($morder);
            // run quesry
            if(!empty($morder)){
                $fres = $this->select($table,$mwhere,$bind,"*",$morder);
            }
            else{
                $fres = $this->select($table,$mwhere,$bind); 
            }
            //
            if(FALSE !== $fres){
                return $fres;
            }
            else{
                return false;
            }

        }

        /**
        * Function to get values of rows that satisfies conditions between $btw1 and $btw2 and $where = $whereval
        * 
        * @param type $btw1
        * @param type $btw2
        * @param type $field
        * @param type $table
        * @param type $where
        * @param type $whereval
        * @param type $orderby
        * @param type $order
        * @param type $offset
        * @param type $total
        * @return boolean
        */
        public function getbtwrowb($btw1,$btw2,$field,$table,$where,$whereval,$orderby="",$order="",$offset="",$total="") {
            // use prepare statement
            $bind = array(
                ":btw1" => "$btw1",
                ":btw2" => "$btw2",
                ":whereval" => "$whereval"
            );
            //
            $mwhere = $field .">=:btw1 AND ".$field ."<=:btw2 AND ".$where ."=:whereval";
            // make chacks order and orderby
            if ($orderby !="" && $order !="") {
                $order = strtoupper($order);
                if ($order =="A") {
                    $AD = "ASC";
                }
                elseif ($order =="D") {
                    $AD = "DESC";
                }
                $morder ="ORDER BY ". $orderby ." ". $AD;
            }
            else{
                $morder ="";
            }
            // make checks offset
            if (!empty($total) && !empty($offset)){
                $morder .= " LIMIT " . $offset .",". $total;
            }
            elseif(!empty($total) && empty($offset)) {
                $morder .= " LIMIT ". $total;
            }
            else{
                $morder ="";
            }
            $morder = trim($morder);
            // run quesry
            if(!empty($morder)){
                $fres = $this->select($table,$mwhere,$bind,"*",$morder);
            }
            else{
                $fres = $this->select($table,$mwhere,$bind);
            }
            if(FALSE !== $fres){
                return $fres;
            }
            else{
                return false;
            }
        }

        /**
        *Function to get a single row of values from a table in the database.
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
        * $yourclass = new EzzzyMysql($db_connection_string,$db_username,$db_password);
        * $array_value_to_get = $yourclass->getrow($where,$whereval,$table);
        * </code>
        *  
        * @param string $where
        * @param mixed $whereval
        * @param string $table
        * @return boolean
        */
        public function getrow($where,$whereval,$table) {
            // use prepare statement
            $bind = array(
                ":whereval" => "$whereval"
            );
            //
            $mwhere = $where ."=:whereval LIMIT 1";
            $fres = $this->select($table,$mwhere,$bind);
            $fieldval = array();
            if(FALSE !== $fres && !empty($fres)){
                foreach ($fres as $ffv) {
                    $fieldval = $ffv;
                }
                return $fieldval;
            }
            else{
                return false;
            }
        }

        /**
        * Function to get a single row of values from a table in the database when two different field matches are needed.
        *
        * @param string $where1
        * @param mixed $whereval1
        * @param string $where2
        * @param mixed $whereval2
        * @param string $table
        * @return boolean
        */
        public function getrow_two($where1,$whereval1,$where2,$whereval2,$table) {
            // use prepare statement
            $bind = array(
                ":whereval1" => "$whereval1",
                ":whereval2" => "$whereval2"
            );
            //
            $mwhere = $where1 ."=:whereval1 AND ".$where2 ."=:whereval2 LIMIT 1";
            $fres = $this->select($table,$mwhere,$bind);
            $fieldval = array();
            if(FALSE !== $fres && !empty($fres)){
                foreach ($fres as $ffv) {
                    $fieldval = $ffv;
                }
                return $fieldval;
            }
            else{
                return false;
            }
        }
        
        /***
        * Function to return just one row from a table
        * 
        * @param mixed $myquery
        * @return mixed Array of result row on success, FALSE on error
        */
        public function getOne($myquery,$bind=''){
            $mqry = $myquery .' LIMIT 1';             
            $fres = $this->runQuery($mqry,$bind);
            $fieldval = array();
            if(FALSE !== $fres && !empty($fres)){
                foreach ($fres as $ffv) {
                    $fieldval = $ffv;
                }
                return $fieldval;
            }
            else{
                return false;
            }
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
        *  $yourclass = new EzzzyMysql($db_connection_string,$db_username,$db_password);
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
        *  $resultarray = $myclass->getallrow($where,$whereval,$table,$order,"d","",5);
        * 
        * @param type $where
        * @param type $whereval
        * @param type $table
        * @param type $orderby
        * @param type $order
        * @param type $offset
        * @param type $total
        * @return boolean
        */
        public function getallrow($where,$whereval,$table,$orderby="",$order="",$offset="",$total=""){ 
            // use prepare statement
            $bind = array(
                ":whereval" => "$whereval"
            );
            //
            $mwhere = $where ."=:whereval";
            // make chacks order and orderby
            if ($orderby !="" && $order !="") {
                $order = strtoupper($order);
                if ($order =="A") {
                    $AD = "ASC";
                }
                elseif ($order =="D") {
                    $AD = "DESC";
                }
                $morder ="ORDER BY ". $orderby ." ". $AD;
            }
            else{
                $morder ="";
            }
            // make checks offset
            if (!empty($total) && !empty($offset)){
                $morder .= " LIMIT " . $offset .",". $total;
            }
            elseif(!empty($total) && empty($offset)) {
                $morder .= " LIMIT ". $total;
            }
            else{
                $morder ="";
            }
            $morder = trim($morder);
            // run query
            if(!empty($morder)){  
                $fres = $this->select($table,$mwhere,$bind,"*",$morder);
            }
            else{
                $fres = $this->select($table,$mwhere,$bind);
            }
            // result
            if(FALSE !== $fres){
                return $fres;
            }
            else{
                return false;
            }  
        }

        /**
        * Function to get all rows of values from a table in the database when two different field matches are needed.
        *
        * This should only be used if you need to get more than one row of
        * values from your table and two WHERES '$where1' AND '$where2' are
        * provided and have 'values' in your table fields. It can be sorted by $orderby and
        * arranged in $order (A for ascending , D for descending) orders.<br />
        * You can also specify how many rows to skip and how many rows to return <br />
        * 
        * @param type $where1
        * @param type $whereval1
        * @param type $where2
        * @param type $whereval2
        * @param type $table
        * @param type $orderby
        * @param type $order
        * @param type $offset
        * @param type $total
        * @return boolean
        */
        function getallrow_two($where1,$whereval1,$where2,$whereval2,$table,$orderby="",$order="",$offset="",$total="") {
            // use prepare statement
            $bind = array(
                ":whereval1" => "$whereval1",
                ":whereval2" => "$whereval2"
            );
            //
            $mwhere = $where1 ."=:whereval1 AND ".$where2 ."=:whereval2";
            // make chacks order and orderby
            if ($orderby !="" && $order !="") {
                $order = strtoupper($order);
                if ($order =="A") {
                    $AD = "ASC";
                }
                elseif ($order =="D") {
                    $AD = "DESC";
                }
                $morder ="ORDER BY ". $orderby ." ". $AD;
            }
            else{
                $morder ="";
            }
            // make checks offset
            if (!empty($total) && !empty($offset)){
                $morder .= " LIMIT " . $offset .",". $total;
            }
            elseif(!empty($total) && empty($offset)) {
                $morder .= " LIMIT ". $total;
            }
            else{
                $morder ="";
            }
            $morder = trim($morder);
            // run query
            if(!empty($morder)){  
                $fres = $this->select($table,$mwhere,$bind,"*",$morder);
            }
            else{
                $fres = $this->select($table,$mwhere,$bind);
            }
            // RESULT
            if(FALSE !== $fres){
                return $fres;
            }
            else{
                return false;
            }
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
        *   // To prevent checking for duplicates, set $n=""
        *   $yourclass = new EzzzyMysql($db_connection_string,$db_username,$db_password);
        *   // add entries to database
        *   $yourclass->addlist($fields,$values,$table,$n)
        * </code>
        * 
        * @param string $tfields
        * @param mixed $tvalues
        * @param string $table
        * @param integer $n
        * @return boolean
        */
        public function addlist($tfields,$tvalues,$table,$n="") {
            // define arrary
            $addrecs = array();
            //    
            if($this->havedcomma($tfields)){
                // more than 1 filed
                // create arrays of each field and value
                $flds = $this->makearray($tfields);
                $vals = $this->makearray($this->clnAppos($tvalues));
                //
                for($j=0; $j < count($flds); $j++){
                    $addrecs[$flds[$j]] = $vals[$j];
                }
            }
            else{
                $addrecs[$tfields] = $this->clnAppos($tvalues);
            }
            // check duplicate entry
            if(!empty($n)){
                if($this->recExist($tfields, $tvalues, $table, $n)){
                    exit("DUPLICATE RECORD! Cannot continue"); 
                    return false;                
                }
            }
            // perfor query
            $result = $this->insert($table,$addrecs);
            if(FALSE !== $result){
                return $result;
            }
            else{
                return false;
            }
        }
        
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
        $myresult = $this->runQuery("SELECT * FROM members WHERE ".$where."='".$whereval."'");
        if(count($myresult) == 0){
            foreach($myresult as $row){
            if($row['photo'] == ""){
          //let us get the sex of the member
          $sex = $this->getval("member_id",$whereval,"members","gender");
          if($sex == "Male"){
            $imgg = "../images/m.jpg";
          }
          elseif($sex == "Female"){
            $imgg = "../images/f.jpg";
          }
            }//end of the if statement that says there is no profile photo
        }
        }//end of the if statement that says there is no profile picture
        else if(count($myresult) > 1){
          $myresult = $this->runQuery("SELECT * FROM members WHERE ".$where."='".$whereval."'");
          foreach($myresult as $myrow){
            $imgg = "../admin/".$myrow['photo'];
          }
        }//end of the else statement that says the profile picture is more than one
        else{
          $myresult = $this->runQuery("SELECT * FROM members WHERE ".$where."='".$whereval."'");
          foreach($myresult as $myrow){
          $imgg = "../admin/".$myrow['photo'];
          }
        }//end of the else statement that says the profile picture is just one
        return $imgg;
    }//end of the function showpic

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
        *  $yourclass = new EzzzyMysql($db_connection_string,$db_username,$db_password);
        *  // update your table
        *  $yourclass->updateval($ufield,$uvalue,$table,$where,$whereval);
        * </code>
        * 
        * @param type $ufield
        * @param type $uvalue
        * @param type $table
        * @param type $where
        * @param type $whereval
        * @param type $whereb
        * @param type $wherevalb
        * @return boolean
        */
        public function updateval($ufield,$uvalue,$table,$where,$whereval,$whereb="",$wherevalb="") {         
            // prepare array
            $updrecs = array();
            //
            if($this->havedcomma($ufield)){
                // create arrays of each field and value
                $flds = $this->makearray($ufield);
                $vals = $this->makearray($uvalue);
                //
                for($j=0; $j < count($flds); $j++){
                    $updrecs[$flds[$j]] = $vals[$j];
                }
            }
            else{
                $updrecs[$ufield] = $uvalue;
            }

            // binders
            if(!empty($whereb) && !empty($wherevalb)){
                // use prepare statement
                $bind = array(
                    ":whereval" => "$whereval",
                    ":wherevalb" => "$wherevalb"
                );
                //
                $mwhere = $where ."=:whereval AND ".$whereb ."=:wherevalb";
            }
            else{
                // use prepare statement
                $bind = array(
                    ":whereval" => "$whereval"
                );
                //
                $mwhere = $where ."=:whereval";
            }
            // run query
            $result = $this->update($table, $updrecs, $mwhere, $bind);
            if(FALSE !== $result){
                return $result;
            }
            else {
                return false;
            }
        }

        /**
        * This function is used to delete any record from the database <br />
        *
        * It takes the table ($table), the field 'where' to delete ($where)
        * and the value of the filed 'where' to delete ($whereval). Can also be used for matching up
        * to two fields of the record to delete
        * 
        * @param type $table
        * @param type $where
        * @param type $whereval
        * @param type $whereb
        * @param type $wherevalb
        * @return boolean
        */
        public function deleteval($table,$where,$whereval,$whereb="",$wherevalb="") {
            // binders
            if(!empty($whereb) && !empty($wherevalb)){
                // use prepare statement
                $bind = array(
                    ":whereval" => "$whereval",
                    ":wherevalb" => "$wherevalb"
                );
                //
                $mwhere = $where ."=:whereval AND ".$whereb ."=:wherevalb";
            }
            else{
                // use prepare statement
                $bind = array(
                    ":whereval" => "$whereval"
                );
                //
                $mwhere = $where ."=:whereval";
            }
            // run query
            $result = $this->delete($table, $mwhere, $bind);
            if(FALSE !== $result){
                return $result;
            }
            else {
                return false;
            }
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
        *   $yourclass = new EzzzyMysql($db_connection_string,$db_username,$db_password);
        * 	 $table = "name_of_table";
        *   $orderby = "name_of_field";
        *   $order = "A"; // Note: A for Ascending, D for descending orders
        *   // get your results
        * 	 $allresult = $yourclass->getallresult($table,$orderby,"A");
        *   // loop through the result associative array to get your values
        * 	 foreach($allresult as $result)
        * 	 {
        *      // prepare your table
        *      echo "<td>$result['field1']</td> <td>$result['field2']</td>
        *      // etc
        *   }
        *
        *  </code>
        * 
        * @param type $table
        * @param type $orderby
        * @param type $order
        * @param type $offset
        * @param type $total
        * @return boolean
        */
        public function getallresult($table,$orderby="",$order="",$offset="",$total="") {
            // check
            // make chacks order and orderby
            if ($orderby !="" && $order !="") {
                $order = strtoupper($order);
                if ($order =="A") {
                    $AD = "ASC";
                }
                elseif ($order =="D") {
                    $AD = "DESC";
                }
                $morder ="ORDER BY ". $orderby ." ". $AD;
            }
            else{
                $morder ="";
            }
            // make checks offset
            if (!empty($total) && !empty($offset)){
                $morder .= " LIMIT " . $offset .",". $total;
            }
            elseif(!empty($total) && empty($offset)) {
                $morder .= " LIMIT ". $total;
            }
            else{
                $morder ="";
            }
            $morder = trim($morder);
            // run query
            if(!empty($morder)){  
                $fres = $this->select($table,"","","*",$morder);
            }
            else{
                $fres = $this->select($table);
            }
            //RESULT
            if(FALSE !== $fres){
                return $fres;
            }
            else{
                return false;
            }
        }

        /**
        * Function to get the total number of records in a table in a database <br />
        *
        * EXAMPLES:
        * <code>
        *   $yourclass = new EzzzyMysql($db_connection_string,$db_username,$db_password);
        * 	 $table = "name_of_table";
        *   // get total records from table and display it
        *   echo "Table has ". $yourclass->getrecords($table) . " records";
        *  </code>
        * @param type $table
        * @param type $where
        * @param type $whereval
        * @param type $whereb
        * @param type $wherevalb
        * @param type $wherec
        * @param type $wherevalc
        * @return boolean
        */
        public function getrecords($table,$where="",$whereval="",$whereb="",$wherevalb="",$wherec="",$wherevalc="") {
            // declare
            $binds = array();
            //checks
            if(!empty($where) && !empty($whereval)){
                $binds[":whereval"] = $whereval;
                $mqry = "SELECT COUNT(*) AS mycnt FROM " . $table ." WHERE ". $where ."=:whereval";
            }
            if(!empty($whereb) && !empty($wherevalb)){
                $binds[":wherevalb"] = $wherevalb;
                $mqry = "SELECT COUNT(*) AS mycnt FROM " . $table ." WHERE ". $where ."=:whereval AND ". $whereb ."=:wherevalb";
            }
            if(!empty($wherec) && !empty($wherevalc)){
                $binds[":wherevalc"] = $wherevalc;
                $mqry = "SELECT COUNT(*) AS mycnt FROM " . $table ." WHERE ". $where ."=:whereval AND ". $whereb ."=:wherevalb AND ". $wherec ."=:wherevalc";
            }
            // Any binds
            if(!empty($binds)){
                $mqry .= ";";
                $result = $this->run($mqry, $binds);
            }
            else{
                $mqry = "SELECT COUNT(*) AS mycnt FROM " . $table;
                $mqry .= ";";
                $result = $this->run($mqry);
            }
            // return result
            if(FALSE !== $result){
                foreach ($result as $ffv) {
                    $fieldval = $ffv['mycnt'];
                }
                return $fieldval;
            }
            else{
                return false;
            }        
        }//end of the function getrecords

        /**
        * Function to get the total number of records in a table in a database <br />
        *
        * EXAMPLES:
        * <code>
        *   $yourclass = new EzzzyMysql($db_connection_string,$db_username,$db_password);
        * 	 $table = "name_of_table";
        *   // get total records from table and display it
        *   echo "Table has ". $yourclass->getrecords($table) . " records";
        *  </code>
        * @param type $table
        * @param type $where
        * @param type $whereval
        * @param type $whereb
        * @param type $wherevalb
        * @param type $wherec
        * @param type $wherevalc
        * @return boolean
        */
        public function getsum($table,$sumfield="",$where="",$whereval="",$whereb="",$wherevalb="",$wherec="",$wherevalc="") {
            // declare
            $binds = array();
            //checks
            if(!empty($where) && !empty($whereval)){
                $binds[":whereval"] = $whereval;
                //$mqry = "SELECT COUNT(*) AS mycnt FROM " . $table ." WHERE ". $where ."=:whereval";
                $mqry = "SELECT SUM(".$sumfield.") AS mycnt FROM " . $table ." WHERE ". $where ."=:whereval";
            }
            if(!empty($whereb) && !empty($wherevalb)){
                $binds[":wherevalb"] = $wherevalb;
                //$mqry = "SELECT COUNT(*) AS mycnt FROM " . $table ." WHERE ". $where ."=:whereval AND ". $whereb ."=:wherevalb";
                $mqry = "SELECT SUM(".$sumfield.") AS mycnt FROM " . $table ." WHERE ". $where ."=:whereval AND ".$whereb."=:wherevalb";
            }
            if(!empty($wherec) && !empty($wherevalc)){
                $binds[":wherevalc"] = $wherevalc;
                $mqry = "SELECT SUM(".$sumfield.") AS mycnt FROM " . $table ." WHERE ". $where ."=:whereval AND ". $whereb ."=:wherevalb AND ". $wherec ."=:wherevalc";
            }
            // Any binds
            if(!empty($binds)){
                $mqry .= ";";
                $result = $this->run($mqry, $binds);
            }
            /*else{
                $mqry = "SELECT COUNT(*) AS mycnt FROM " . $table;
                $mqry .= ";";
                $result = $this->run($mqry);
            }*/
            // return result
            if(FALSE !== $result){
                foreach ($result as $ffv) {
                    $fieldval = $ffv['mycnt'];
                }
                return $fieldval;
            }
            else{
                return false;
            }        
        }//end of the function getsum


        /**
        * Function to perform a search for records in a table in a database <br />
        *  
        * This function can be used to perform a search for records in your database.
        * This has been included for those that wants to incorporate a search function <br />
        * into their database driven website. <br />
        * Remember, you can also specify how many rows of result to return which you can then 
        * manipulate with your php code. <br />
        * EXAMPLES:
        * <code>
        *   $yourclass = new EzzzyMysql($db_connection_string,$db_username,$db_password);
        * 	 $words = "words_to_search_for";
        *   $command = "ALL"; // or "ANY" Default is ANY
        *   $field = "field_to_search_for_the_words";
        *   $table = "name_of_table";
        *   // Perform the search
        *   $allsearch = $yourclass->dosearch($words,$table,$field,$command,...)";
        *   // show search results
        *   foreach ($allsearch as $search_result)
        *   {
        *     // display
        *      echo "<u>$search_result['field_title']</u> <br> $search_result['field_body']
        *     // etc
        *   }
        *  </code>
        * 
        * @param type $words
        * @param type $table
        * @param type $sfield
        * @param type $command
        * @param type $orderby
        * @param type $order
        * @param type $offset
        * @param type $total
        * @return boolean
        */
        public function dosearch($words, $table, $sfield, $command = "", $orderby = "", $order = "", $offset = "", $total = "") {
            // binds
            if($command == "ALL"){
                // WE SEARCH FOR ALL WORDS
                // use prepare statement
                $binds = array(
                    ":words" => "%$words%"
                );
                //
                $mwhere = $sfield ." LIKE :words";
            }
            elseif ($command == "ANY") {
                // WE SEARCH FOR ANY WORDS
                if(strpos($words, " ") !== FALSE){
                    // BREAK THE SEARCH WORDS
                    $word = explode(" ",$words);
                }
                // use prepare statement
                $mwhere ="";
                $binds = array();
                $tota = count($word);
                for($q=0; $q < $tota; $q++){
                    $binds[":$word[$q]"] = "%$word[$q]%";
                    if($q != 0){ $mwhere .= " OR ";}
                    $mwhere .= $sfield ." LIKE :$word[$q]";
                    //echo " word ".$q =  $word[$q];
                }

            }
            else{
                // WE SEARCH FOR ALL WORDS
                // use prepare statement
                $binds = array(
                    ":words" => "%$words%"
                );
                //
                $mwhere = $sfield ." LIKE :words";
            }
            //check 
            // make chacks order and orderby
            if ($orderby !="" && $order !="") {
                $order = strtoupper($order);
                if ($order =="A") {
                    $AD = "ASC";
                }
                elseif ($order =="D") {
                    $AD = "DESC";
                }
                $morder ="ORDER BY ". $orderby ." ". $AD;
            }
            else{
                $morder ="";
            }
            // make checks offset
            if (!empty($total) && !empty($offset)){
                $morder .= " LIMIT " . $offset .",". $total;
            }
            elseif(!empty($total) && empty($offset)) {
                $morder .= " LIMIT ". $total;
            }
            else{
                $morder ="";
            }
            $morder = trim($morder);

            // RUN QUESRY
            if(!empty($morder)){
                $result = $this->select($table, $mwhere, $binds,"*",$morder);
            }
            else{
                $result = $this->select($table, $mwhere, $binds);
            }
            // return result
            if(FALSE !== $result){
                return $result;
            }
            else{
                return false;
            } 
        }

        /**
        * Function to perform a search for records in a table in a database with other parameters <br />
        *  
        * This function can be used to perform a search for records in your database.
        * This has been included for those that wants to incorporate a search function <br />
        * into their database driven website. <br />
        * Remember, you can also specify how many rows of result to return which you can then 
        * manipulate with your php code. <br />
        * EXAMPLES:
        * <code>
        *   $yourclass = new EzzzyMysql($db_connection_string,$db_username,$db_password);
        * 	 $words = "words_to_search_for";
        *   $command = "ALL"; // or "ANY" Default is ALL
        *   $field = "field_to_search_for_the_words";
        *   $table = "name_of_table";
        *   // Perform the search
        *   $allsearch = $yourclass->dosearchb($words,$table,$field,$where,$whereval,$command,...)";
        *   // show search results
        *   foreach ($allsearch as $search_result)
        * 	 {
        *      // display
        *      echo "<u>$search_result['field_title']</u> <br> $search_result['field_body']
        *      // etc
        *   }
        *  </code>
        * 
        * @param type $words
        * @param type $table
        * @param type $sfield
        * @param type $where
        * @param type $whereval
        * @param type $command
        * @param type $orderby
        * @param type $order
        * @param type $offset
        * @param type $total
        * @return boolean
        */
        public function dosearchb($words, $table, $sfield, $where, $whereval, $command = "", $orderby = "", $order = "", $offset = "", $total = "") {
            // binds
            if($command == "ALL"){
                // WE SEARCH FOR ALL WORDS
                // use prepare statement
                $binds = array(
                    ":words" => "%$words%",
                    ":whereval" => "$whereval"
                );
                //
                //$mwhere = $sfield ." LIKE :words AND ". $where ."=:whereval";
                $mwhere = $where ."=:whereval AND ". $sfield ." LIKE :words";
            }
            elseif ($command == "ANY") {
                // WE SEARCH FOR ANY WORDS
                if(strpos($words, " ") !== FALSE){
                    // BREAK THE SEARCH WORDS
                    $word = explode(" ",$words);
                }
                // use prepare statement
                $mwhere ="";
                $binds = array();
                $binds[":whereval"] = $whereval;
                $mwhere .= $where ."=:whereval AND ";
                $tota = count($word);
                for($q=0; $q < $tota; $q++){
                    $binds[":$word[$q]"] = "%$word[$q]%";
                    if($q != 0){ $mwhere .= " OR ";}
                    $mwhere .= $sfield ." LIKE :$word[$q]";
                    //echo " word ".$q =  $word[$q];
                }
                //$binds[":whereval"] = $whereval;
                //$mwhere .= " AND ". $where ."=:whereval";
            }
            else{
                // WE SEARCH FOR ALL WORDS
                // use prepare statement
                $binds = array(
                    ":words" => "%$words%",
                    ":whereval" => "$whereval"
                );
                //
                //$mwhere = $sfield ." LIKE :words AND ". $where ."=:whereval";
                $mwhere = $where ."=:whereval AND ". $sfield ." LIKE :words";
            }
            //check 
            // make chacks order and orderby
            if ($orderby !="" && $order !="") {
                $order = strtoupper($order);
                if ($order =="A") {
                    $AD = "ASC";
                }
                elseif ($order =="D") {
                    $AD = "DESC";
                }
                $morder ="ORDER BY ". $orderby ." ". $AD;
            }
            else{
                $morder ="";
            }
            // make checks offset
            if (!empty($total) && !empty($offset)){
                $morder .= " LIMIT " . $offset .",". $total;
            }
            elseif(!empty($total) && empty($offset)) {
                $morder .= " LIMIT ". $total;
            }
            else{
                $morder ="";
            }
            $morder = trim($morder);

            // RUN QUESRY
            if(!empty($morder)){
                $result = $this->select($table, $mwhere, $binds,"*",$morder);
            }
            else{
                $result = $this->select($table, $mwhere, $binds);
            }
            // return result
            if(FALSE !== $result){
                return $result;
            }
            else{
                return false;
            } 
        }
        
        /**
         * This function will return the precentage growth of a PH
         * 
         * @param type $mid
         * @param type $phid
         * @return integer
         */
        public function getGrowth($mid,$phid,$days,$percent){
            $gdet = $this->getrow_two("mid", $mid, "phghid", $phid, "tempo");
            //$refd = strtotime($gdet['dates']);
            $refd = floor((time() - strtotime($gdet['dates']))/86400); //this is the number of days the growth has occured;
            if($refd <=$days){
                //return floor($refd * 3.575);
                return floor($refd * @($percent/$days));
            }
            else{
                //return floor(28 * 3.575);
                return floor($days * @($percent/$days));
            }
        }//end of the function that returns the growth
        
        
        
        /**
         * This function will return the precentage growth of a PH
         * 
         * @param type $mid
         * @param type $phid
         * @return integer
         */
        public function getGrowthDays($mid,$phid,$days){
            $gdet = $this->getrow_two("mid", $mid, "phghid", $phid, "tempo");
            //$refd = strtotime($gdet['dates']);
            $refd = floor((time() - strtotime($gdet['dates']))/86400); //this is the number of days the growth has occured;
            if($refd <=$days){
                return $refd;
            }
            else{
                return $days;
            }
        }//end of the function that returns the growth
        
        
        /**
         * This function will check the members referrals if they have paid out the PH
         * 
         * @param type $mid         
         * @return boolean
         */
        public function checkRef($mid){
            //let us get the ref
            $ref = $this->getval("referer",$mid,"members","member_id");
            //let us now check if the ref has paid out
            if($this->getrecords("phghdet","pher",$ref,"status","CONFIRMED") != 0){
                return true;                
            }
            else{
                return false;
            }
           
        }//end of the function that cheks the refs
        
        /**
         * This function will check the position of this member weather basic or premium
         * 
         * @param type $mid         
         * @return boolean
         */
        public function confirmedRef($mid){
            $nopo = 0; //this is the number of sucessful paid out
            //let us get the refs
            $ref = $this->getallrow("referer",$mid,"members");
            foreach($ref as $refs){
                //we shall check if this person has paid out
                if($this->getrecords("phghdet","pher",$refs['member_id'],"status","CONFIRMED") != 0 && $refs['status'] == "ACTIVE"){ $nopo++; }
            }
            //let us now check if the ref has paid out
            return $nopo;
           
        }//end of the function that cheks the refs

        /*
        * PRIVATE FUNCTIONS
        */

        private function debug() {
            if (!empty($this->errorCallbackFunction)) {
                $error = array("Error" => $this->error);
                if (!empty($this->sql))
                    $error["SQL Statement"] = $this->sql;
                if (!empty($this->bind))
                    $error["Bind Parameters"] = trim(print_r($this->bind, true));

                $backtrace = debug_backtrace();
                if (!empty($backtrace)) {
                    foreach ($backtrace as $info) {
                        if ($info["file"] != __FILE__)
                            $error["Backtrace"] = $info["file"] . " at line " . $info["line"];
                    }
                }

                $msg = "";
                if ($this->errorMsgFormat == "html") {
                    if (!empty($error["Bind Parameters"]))
                        $error["Bind Parameters"] = "<pre>" . $error["Bind Parameters"] . "</pre>";
                    $css = trim(file_get_contents(dirname(__FILE__) . "/error.css"));
                    $msg .= '<style type="text/css">' . "\n" . $css . "\n</style>";
                    $msg .= "\n" . '<div class="db-error">' . "\n\t<h3>SQL Error</h3>";
                    foreach ($error as $key => $val)
                        $msg .= "\n\t<label>" . $key . ":</label>" . $val;
                    $msg .= "\n\t</div>\n</div>";
                }
                elseif ($this->errorMsgFormat == "text") {
                    $msg .= "SQL Error\n" . str_repeat("-", 50);
                    foreach ($error as $key => $val)
                        $msg .= "\n\n$key:\n$val";
                }

                $func = $this->errorCallbackFunction;
                $func($msg);
            }
        }

        private function delete($table, $where, $bind = "") {
            $sql = "DELETE FROM " . $table . " WHERE " . $where . ";";
            $this->run($sql, $bind);
        }

        private function filter($table, $info) {
            $driver = $this->getAttribute(PDO::ATTR_DRIVER_NAME);
            if ($driver == 'sqlite') {
                $sql = "PRAGMA table_info('" . $table . "');";
                $key = "name";
            } elseif ($driver == 'mysql') {
                $sql = "DESCRIBE " . $table . ";";
                $key = "Field";
            } else {
                $sql = "SELECT column_name FROM information_schema.columns WHERE table_name = '" . $table . "';";
                $key = "column_name";
            }

            if (false !== ($list = $this->run($sql))) {
                $fields = array();
                foreach ($list as $record)
                    $fields[] = $record[$key];
                return array_values(array_intersect($fields, array_keys($info)));
            }
            return array();
        }

        private function cleanup($bind) {
            if (!is_array($bind)) {
                if (!empty($bind))
                    $bind = array($bind);
                else
                    $bind = array();
            }
            return $bind;
        }

        private function insert($table, $info) {
            $fields = $this->filter($table, $info);
            $sql = "INSERT INTO " . $table . " (" . implode($fields, ", ") . ") VALUES (:" . implode($fields, ", :") . ");";
            $bind = array();
            foreach ($fields as $field){
                $bind[":$field"] = $info[$field];
            }
            return $this->run($sql, $bind);
        }

        private function run($sql, $bind = "") {
            $this->sql = trim($sql);
            $this->bind = $this->cleanup($bind);
            $this->error = "";

            try {
                $pdostmt = $this->prepare($this->sql);
                if ($pdostmt->execute($this->bind) !== false) {
                    if (preg_match("/^(" . implode("|", array("select", "describe", "pragma", "show")) . ") /i", $this->sql)){
                        return $pdostmt->fetchAll(PDO::FETCH_ASSOC);
                    }
                    elseif (preg_match("/^(" . implode("|", array("delete", "insert", "update")) . ") /i", $this->sql)){
                        return $pdostmt->rowCount();
                    }
                }
            } catch (PDOException $e) {
                $this->error = $e->getMessage();
                $this->debug();
                return false;
            }
        }

        private function select($table, $where = "", $bind = "", $fields = "*", $order ="") {
            $sql = "SELECT " . $fields . " FROM " . $table;
            if (!empty($where)){
                $sql .= " WHERE " . $where;
            }
            if(!empty($order)){
                $sql .= " ".$order;
            }
            $sql .= ";";
            return $this->run($sql, $bind);
        }

        public function setErrorCallbackFunction($errorCallbackFunction, $errorMsgFormat = "html") {
            //Variable functions for won't work with language constructs such as echo and print, so these are replaced with print_r.
            if (in_array(strtolower($errorCallbackFunction), array("echo", "print"))){
                $errorCallbackFunction = "print_r";
            }
            if (function_exists($errorCallbackFunction)) {
                $this->errorCallbackFunction = $errorCallbackFunction;
                if (!in_array(strtolower($errorMsgFormat), array("html", "text"))){
                    $errorMsgFormat = "html";
                }
                $this->errorMsgFormat = $errorMsgFormat;
            }
        }

        /**
        * Function to prapare date for inserting to database
        * The $dtval must be in the format time
        *
        * @access Private
        * @param $datval, timestamp 
        * @return datetime $dbdate, YYYY-MM-DD HH:MM:SS
        */
        public function dbdate($datval){
            $dbdate = date("Y-m-d H:i:s", $datval);

            return $dbdate;
        }

        private function update($table, $info, $where, $bind = "") {
            $fields = $this->filter($table, $info);
            $fieldSize = sizeof($fields);

            $sql = "UPDATE " . $table . " SET ";
            for ($f = 0; $f < $fieldSize; ++$f) {
                if ($f > 0)
                    $sql .= ", ";
                $sql .= $fields[$f] . " = :update_" . $fields[$f];
            }
            $sql .= " WHERE " . $where . ";";

            $bind = $this->cleanup($bind);
            foreach ($fields as $field)
                $bind[":update_$field"] = $info[$field];

            return $this->run($sql, $bind);
        }

        private function recExist($ffld,$fval,$tab,$k="") {
            // check
            if ($this->havedcomma($ffld) && $this->havedcomma($fval)) {
                // split them
                $fld1 = $this->makearray($ffld);
                $val1 = $this->makearray($fval);
                if ($k =="") {
                    $val = $this->clnAppos($val1[0]);
                    $fld = $fld1[0];
                }
                else {
                    $val = $this->clnAppos($val1[$k-1]);
                    $fld = $fld1[$k-1];
                }
            }
            else {
                $val = $this->clnAppos($fval);
                $fld = $ffld;
            }
            // run command
            $oout = $this->getrow($fld, $val, $tab);
            if(FALSE !== $oout && !empty($oout)){
                return true;
            }
            else {
                return false;
            }
        }

        private function havedcomma($mystr) {
            if (strpos($mystr, ",,") !== FALSE)  {
                // more than one record, so it can be exploded
                return true;
            } else {
                return false;
            }
        }

        private function makearray($mstrg) {
            // check if values are separated by double comma ',,'.
            if ($this->havedcomma($mstrg)) {
                $marr = explode(",,", $mstrg);
            }
            return $marr;
        }

        private function clnAppos($mystr) {
            if (strpos($mystr, "'") !== FALSE)  {
                // it has appostrophe, so clean up
                $samyb = trim($mystr);
                $samyb = str_replace("'", "", $mystr);
                return $samyb;
            } else {
                return $mystr;
            }
        }

    }

?>