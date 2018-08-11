<?php
////////////////////////////// BEGIN FUNCTIONS DECLARATIONS //////////////////////////////////////////
/**
 * Function name: cleana
 * The duty of this function is to
 * remove any traces of ';' '..' '--' or '?' to prevent sql injection.
 */
function cleana($samy)
 {

     $samyb = trim($samy);
     $samyb = @_match("%([A-Za-z]+)%","",$samyb);
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
 * Function name: getAbbr
 * The duty of this function is to
 * return the abbreviation of any sentence supplied
 */
function getAbbr($samy)
 {
    $strtwn = "";
    $atac = explode(" ", $samy);
    if(count($atac) > 1){
        for($i = 0; $i < count($atac); $i++){
            $temp = trim($atac[$i]);
            if(strlen($temp) != 0){
                $strtwn.=substr($temp,0,1);
            }
        }
    }
    else{
        $strtwn.=substr($strtwn,0,1);
    }
    return strtolower($strtwn);
 }

 /**
  * Function name: cleanb
  * This function removes any traces of PHP scripts tags form text
  */
 function cleanb($sany)
 {

    $sanyb = @ereg_replace("%([A-Za-z]+)%","",$sany);
    $sanyb = convertHTM($sanyb);
    if (!@get_magic_quotes_gpc()){
 		$sanyb = addslashes($sanyb);
 	}

    return $sanyb;

 }

/**
  * Function name: cln_addslash
  * This function escapes the strings
  */
 function cleansp($sanm)
 {

     $samb = trim($sanm);
     $samb = @ereg_replace("%([A-Za-z]+)%","",$samb);
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
  * Function to remove slashes from results from database
  * checks if magic quotes is on or off
  * @name remslash
  *
  */
 function remslash($thevalue){
  	if (!get_magic_quotes_gpc()){
 		$myvalue = stripslashes($thevalue);
 	}
 	else {
 		$myvalue = $thevalue;
 	}
 	return $myvalue;
 }

 /**
  * Function to transfer a user to another page
  * Using the header function
  *
  * @name gotoPage
  *
  */
 function gotoPage($page,$msg="",$retURL=""){
  	// Check if page is not NULL
  	if ($page =="") {
  		echo "Please supply the page to navigate to";
  		exit;
  	}
  	if ($msg =="" && $retURL =="") {
  		$thelocation = $page;
  	}
  	elseif ($msg !="" && $retURL =="") {
	    $thelocation = $page."?msg=".$msg;
	}
	elseif ($msg =="" && $retURL !="") {
		$thelocation = $page."?r_url=".$retURL;
	}
	else {
		// both are supplied
		$thelocation = $page."?msg=".$msg ."&r_url=".$retURL;
	}
  	header("Location: $thelocation");
  	exit;
 }

 /**
  * Function to replace PHP STRIP_TAGS
  *
  * @name convertHTM
  * @param $htmdocument, the HTML document
  * @return $theHTM, the cleaned document
  */
 function convertHTM($htmdocument){
   $itmsearch = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript
                 '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
                 '@<[/!]*?[^<>]*?>@si',            // Strip out HTML tags
                 '@<![sS]*?--[ tnr]*>@'         // Strip multi-line comments including CDATA
  );
  $theHTM = @preg_replace($itmsearch, '', $htmdocument);
  return $theHTM;
 }



 /**
  * Function to remove slashes and also all forms
  * of php and html tags from results from database
  * checks if magic quotes is on or off
  * @name remclean
  *
  */
 function remclean($tvalue){
 	global $mytvalue;

 	if (!get_magic_quotes_gpc()){
 		$mytvalue = stripslashes($tvalue);
 		$mytvalue = convertHTM($mytvalue);
 	}
 	else {
 		$mytvalue = convertHTM($tvalue);
 	}

 	return $mytvalue;
 }

/**
	  * Function to format date values to a nice format with time
	  * The $dtval must be in the format 'yyyy-mm-dd' or 'yyyy-mm-dd hh:mm:ss'
	  *
	  * @name myformatDate
	  * @param $mddat, in the format 'yyyy-mm-dd' or 'yyyy-mm-dd hh:mm:ss'
	  *
	  * @access Private
	  * @return $mdval, in the format 'Wednesday, May 5th 2006' or 'Wednesday, May 5th 2006, 10:30PM'
	  */
	 function myformatDate($mddat){
	 	// check if the value id date or datetime
	 	if (@preg_match(":",$mddat) && ereg("-",$mddat)) {
	 		// this is datetime format
	 		if (@preg_match(" ",$mddat)) {
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
	 		if (@preg_match("-",$mddat)) {
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
      * Function to break date values from database in a nice format for forms
      * The $dtval must be in the format 'yyyy-mm-dd' 
      *
      * @name formDate
      * @param $ddat, in the format 'yyyy-mm-dd'
      * @access Private
      * @return array $fdat
      */
     function formDate($ddat){
         //$fdat = "";
         // check if the value is date or datetime
         if (@preg_match(":",$ddat) && @preg_match("-",$ddat)) {
             // this is datetime format
             if (@preg_match(" ",$ddat)) {
             // e.g 2006-05-20 19:23:30 (YYYY-MM-DD HH:MM:SS)
            $mfld = explode(" ",$ddat);
            // $mflda[0] = 2006-05-20; $mflda[1]= 19:23:20
            // break each one again
            $mf1 = explode("-", $mfld[0]);
            $mf2 = explode(":", $mfld[1]);
            // merge the two arrays to one
            @$fdat = array_merge($mf1,$mf2);
            }
           return @$fdat;
         }
         else {
             // this is just date format
             if (@preg_match("-",$ddat)) {
             // e.g 2006-05-20 (YYYY-MM-DD)
            @$fdat = explode("-",$ddat);
            // $mfld[0] = 2006; $mfld[1]=05; $mfld[2]=20
             }

           return @$fdat;
         }

     }


  /**
  * Function name: dcomma
  * This function removes any traces of double commas from text
  * Change it to only one comma
  */
 function dcomma($ttext)
 {
   global $nctext;

    if (@preg_match(",,", $ttext)){
 		$nctext = str_replace(",,",",",$ttext);
		return $nctext;
 	}
	else {
	  return $ttext;
     }
 }

 /**
  * Function name: rcomma
  * This function removes any traces of single commas from text // to fix a present problem 
  * noticed in city name for registered comapanies
  * Change it to only one comma
  */
 function rcomma($ttext)
 {
    if (@preg_match(",", $ttext)){
         $nctext = str_replace(",","",$ttext);
        return $nctext;
     }
    else {
      return $ttext;
     }
 }

/**
  * Function to verify if an email is correct
  *
  */
 function trueemail($email){
 	if (@preg_match("/^[^@\s<&>]+@([-a-z0-9]+\.)+[a-z]{2,}$/i" ,$email)) {
 		return true;
 	}
 	else {
 		return false;
 	}
 }


 /**
  * Function to prapare date for inserting to database
  * The $dtval must be in the format time
  *
  * @access Private
  * @param $datval, in the format 'timestamp'
  * @return datetime $dbdate, YYYY-MM-DD HH:MM:SS
  */
 function prepdate($datval){
 	 $dbdate = date("Y-m-d H:i:s", $datval);

 	return $dbdate;
 }

 /**
  * Function to break a file name into two and rename the file
  * It also replaces spaces with underscore'_'
  * a. the name
  * b. the extension
  *
  * @name renamefile
  * @param string name of the file
  * @return new name for the file
  */
 function renamefile($filename,$a='') {
 	// break the document name into two
	 $pic = explode(".",$filename);
	 // get time
	 $mn = time();
	 // add  time
	 $newp = @$pic[0] . $mn;
	 // replace any traces of spaces with '_'
	 if (@preg_match(" ",$newp)) {
	 	$newp = str_replace(" ","_",$newp);
	 }
	 // if $a is not empty, so add it
	 if ($a !="") {
	 	//$newp = $newp."_".$a;
	 	$newp = $a;
	 }
	 // combine name
	 @$imgarr = array($newp,$pic[1]);
	 $newname = implode(".",$imgarr);

	 return $newname;
 }
 
 /**
  * Function to encrypt password before adding it to database
  * 
  * @name do_crypt
  * @param string password to encrypt
  * @return string crypted password
  */
 function do_crypt($pword) {
 	// generate the characters to use as salt for encryption
    $ch = "67zB7ua@K";
 	$str = md5($ch);
    // do encrypt now
    $newpass = crypt($pword, $str);

	return $newpass;
 }

  /**
  * Function to generate temporary password for reseting passwords
  * 
  * @name generate_pass
  * 
  * @return string generated password
  */
 function generate_pass() {
 	//set of characters to use
 	$myv = "ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghijkmnpqrstuvwxyz";
    
     $str = $myv{rand(0, strlen($myv)-1)}; 
     $str .= $myv{rand(0, strlen($myv)-1)}; 
     $str .= $myv{rand(0, strlen($myv)-1)}; 
     $str .= $myv{rand(0, strlen($myv)-1)}; 
     $str .= $myv{rand(0, strlen($myv)-1)}; 
     $str .= $myv{rand(0, strlen($myv)-1)};
     $str .= $myv{rand(0, strlen($myv)-1)}; 
     $str .= $myv{rand(0, strlen($myv)-1)}; 
     $str .= $myv{rand(0, strlen($myv)-1)};
     $str .= $myv{rand(0, strlen($myv)-1)};
          
     $genpass = $str; 

	return $genpass;
 }
  /**
  * Function to generate temporary password for reseting passwords
  * 
  * @name generate_pass
  * 
  * @return string generated password
  */
 function generateID() {
 	//set of characters to use
 	$myv = "23456789";
    
     $str = $myv{rand(0, strlen($myv)-1)}; 
     $str .= $myv{rand(0, strlen($myv)-1)}; 
     $str .= $myv{rand(0, strlen($myv)-1)}; 
     $str .= $myv{rand(0, strlen($myv)-1)}; 
     $str .= $myv{rand(0, strlen($myv)-1)}; 
     $str .= $myv{rand(0, strlen($myv)-1)};
     $str .= $myv{rand(0, strlen($myv)-1)}; 
     $str .= $myv{rand(0, strlen($myv)-1)}; 
     $str .= $myv{rand(0, strlen($myv)-1)};
          
     $genpass = $str; 

	return $genpass;
 }
 
 /**
  * Function to generate 14 Digits PIN Code
  * 
  * @name generate_code
  * 
  * @return string generated code
  */
 function generate_code() {
 	//set of characters to use
 	$myv = "23456789ABCDEFGHJKLMNPQRSTUVWXYZ";

     $str = $myv{rand(0, strlen($myv)-1)};
     $str .= $myv{rand(0, strlen($myv)-1)}; 
     $str .= $myv{rand(0, strlen($myv)-1)}; 
     $str .= $myv{rand(0, strlen($myv)-1)}; 
     $str .= $myv{rand(0, strlen($myv)-1)}; 
     $str .= $myv{rand(0, strlen($myv)-1)};
     $str .= $myv{rand(0, strlen($myv)-1)}; 
     $str .= $myv{rand(0, strlen($myv)-1)}; 
     $str .= $myv{rand(0, strlen($myv)-1)};
     $str .= $myv{rand(0, strlen($myv)-1)}; 
     $str .= $myv{rand(0, strlen($myv)-1)}; 
     $str .= $myv{rand(0, strlen($myv)-1)};
     $str .= $myv{rand(0, strlen($myv)-1)};
     $str .= $myv{rand(0, strlen($myv)-1)};
          
     $gencode = $str; 

	return $gencode;
 }
 
 /*
 * This is a function to get the student grade
 * @author Ezekiel Aliyu
 * @return String
 * @param $vari The score of the student
 * @param $cat The category of the selected class
 */
function getPoint($grade){
    //$rem = "";
    //let us get the existing grade from the database
    if($grade == "A1"){
        return 10;       
    }
    else if($grade == "B2"){
        return 9;       
    }
    else if($grade == "B3"){
        return 8;       
    }
    else if($grade == "C4"){
        return 7;       
    }
    else if($grade == "C5"){
        return 6;       
    }
    else if($grade == "C6"){
        return 5;       
    }
    else {
        return 0;       
    }
	//return $rem;
}//end of the function grade
 
 /**
  * Function to display fine error messages if it occurs
  * in the process of doing anything in this program.
  *
  * @name show_error
  * @param $errmsg, error message to display
  * @return void
  */
 function show_error($errmsg){
 		// prepare the head
 	   $myerr = <<<HEREDOC
	<div id="error">
     <h2>ERROR</h2>
     <b> $errmsg </b> <br />
     </div>
HEREDOC;

 echo($myerr);
 }

 /**
  * Function to display fine success messages
  * in the process of doing anything in this program.
  *
  * @name show_success
  * @param $sucmsg, error message to display
  * @return void
  */
 function show_success($sucmsg){
 		// prepare the head
 	   $mysucc = <<<HEREDOC1
	<div id="success">
     <h2> SUCCESS </h2>
     <b> $sucmsg </b> <br />
     </div>
HEREDOC1;

 echo($mysucc);
 }
 
 /**
  * Function to display other information messages
  * in the process of doing anything in this program.
  *
  * @name show_info
  * @param $infomsg, error message to display
  * @return void
  */
 function show_info($infomsg){
 		// prepare the head
 	   $myinfo = <<<HEREDOC2
	<div id="info">
     <h2> INFORMATION </h2>
     <b> $infomsg </b> <br />
     </div>
HEREDOC2;

 echo($myinfo);
 }
 
 
 /**
  * Function to display years from 1970 to 2025
  * @param mixed $xx Selected Year
  * @param string $my Filed name
  * @param string $class Class Name
  */
 function selectyear($xx='',$my,$class='',$id=''){
 $shoy = <<<MYDOC
   <select name="$my" class="$class" id="$id">
   <option value="$xx">$xx</option>
                                <option value="1900">1900</option>
                                <option value="1901">1901</option>
                                <option value="1902">1902</option>
                                <option value="1903">1903</option>
                                <option value="1904">1904</option>
                                <option value="1905">1905</option>
                                <option value="1906">1906</option>
                                <option value="1907">1907</option>
                                <option value="1908">1908</option>
                                <option value="1909">1909</option>
                                <option value="1910">1910</option>
                                <option value="1911">1911</option>
                                <option value="1912">1912</option>
                                <option value="1913">1913</option>
                                <option value="1914">1914</option>
                                <option value="1915">1915</option>
                                <option value="1916">1916</option>
                                <option value="1917">1917</option>
                                <option value="1918">1918</option>
                                <option value="1919">1919</option>
                                <option value="1920">1920</option>
                                <option value="1921">1921</option>
                                <option value="1922">1922</option>
                                <option value="1923">1923</option>
                                <option value="1924">1924</option>
                                <option value="1925">1925</option>
                                <option value="1926">1926</option>
                                <option value="1927">1927</option>
                                <option value="1928">1928</option>
                                <option value="1929">1929</option>
                                <option value="1930">1930</option>
                                <option value="1931">1931</option>
                                <option value="1932">1932</option>
                                <option value="1933">1933</option>
                                <option value="1934">1934</option>
                                <option value="1935">1935</option>
                                <option value="1936">1936</option>
                                <option value="1937">1937</option>
                                <option value="1938">1938</option>
                                <option value="1939">1939</option>
                                <option value="1940">1940</option>
                                <option value="1941">1941</option>
                                <option value="1942">1942</option>
                                <option value="1943">1943</option>
                                <option value="1944">1944</option>
                                <option value="1945">1945</option>
                                <option value="1946">1946</option>
                                <option value="1947">1947</option>
                                <option value="1948">1948</option>
                                <option value="1949">1949</option>
 						        <option value="1950">1950</option>
                                <option value="1951">1951</option>
                                <option value="1952">1952</option>
                                <option value="1953">1953</option>
                                <option value="1954">1954</option>
                                <option value="1955">1955</option>
                                <option value="1956">1956</option>
                                <option value="1957">1957</option>
                                <option value="1958">1958</option>
                                <option value="1959">1959</option>
                                <option value="1960">1960</option>
                                <option value="1961">1961</option>
                                <option value="1962">1962</option>
                                <option value="1963">1963</option>
                                <option value="1964">1964</option>
                                <option value="1965">1965</option>
                                <option value="1966">1966</option>
                                <option value="1967">1967</option>
                                <option value="1968">1968</option>
                                <option value="1969">1969</option>
                                <option value="1970">1970</option>
								<option value="1971">1971</option>
								<option value="1972">1972</option>
								<option value="1973">1973</option>
								<option value="1974">1974</option>
								<option value="1975">1975</option>
								<option value="1976">1976</option>
								<option value="1977">1977</option>
								<option value="1978">1978</option>
								<option value="1979">1979</option>
   								<option value="1980">1980</option>
								<option value="1981">1981</option>
								<option value="1982">1982</option>
								<option value="1983">1983</option>
								<option value="1984">1984</option>
								<option value="1985">1985</option>
								<option value="1986">1986</option>
								<option value="1987">1987</option>
								<option value="1988">1988</option>
								<option value="1989">1989</option>
   								<option value="1990">1990</option>
								<option value="1991">1991</option>
								<option value="1992">1992</option>
								<option value="1993">1993</option>
								<option value="1994">1994</option>
								<option value="1995">1995</option>
								<option value="1996">1996</option>
								<option value="1997">1997</option>
								<option value="1998">1998</option>
								<option value="1999">1999</option>
								<option value="2000">2000</option>
								<option value="2001">2001</option>
								<option value="2002">2002</option>
								<option value="2003">2003</option>
								<option value="2004">2004</option>
								<option value="2005">2005</option>
								<option value="2006">2006</option>
								<option value="2007">2007</option>
								<option value="2008">2008</option>
								<option value="2009">2009</option>
								<option value="2010">2010</option>
								<option value="2011">2011</option>
								<option value="2012">2012</option>
								<option value="2013">2013</option>
								<option value="2014">2014</option>
								<option value="2015">2015</option>
								<option value="2016">2016</option>
								<option value="2017">2017</option>
								<option value="2018">2018</option>
								<option value="2019">2019</option>
								<option value="2020">2020</option>
								<option value="2021">2021</option>
								<option value="2022">2022</option>
								<option value="2023">2023</option>
								<option value="2024">2024</option>
								<option value="2025">2025</option>
                                <option value="2026">2026</option>
	</select>
MYDOC;

 echo $shoy;
 }

 /**
  * Function to display session from 2013/2014 to 2039/2040
  * @param mixed $xx Selected Year
  * @param string $my Filed name
  * @param string $class Class Name
  */
 function selectsession($xx='',$my,$class='',$id=''){
 $shoy = <<<MYDOC
   <select name="$my" class="$class" id="$id">
   <option value="$xx">$xx</option>
								<option value="2013/2014">2013/2014</option>
								<option value="2014/2015">2014/2015</option>
								<option value="2015/2016">2015/2016</option>
								<option value="2016/2017">2016/2017</option>
								<option value="2017/2018">2017/2018</option>
								<option value="2018/2019">2018/2019</option>
								<option value="2019/2020">2019/2020</option>
								<option value="2020/2021">2020/2021</option>
								<option value="2021/2022">2021/2022</option>
								<option value="2022/2023">2022/2023</option>
								<option value="2023/2024">2023/2024</option>
								<option value="2024/2025">2024/2025</option>
								<option value="2025/2026">2025/2026</option>
                                <option value="2026/2027">2026/2027</option>
                                <option value="2027/2028">2027/2028</option>
                                <option value="2028/2029">2028/2029</option>
                                <option value="2029/2030">2029/2030</option>
                                <option value="2030/2031">2030/2031</option>
                                <option value="2031/2032">2031/2032</option>
                                <option value="2032/2033">2032/2033</option>
                                <option value="2033/2034">2033/2034</option>
                                <option value="2034/2035">2034/2035</option>
                                <option value="2035/2036">2035/2036</option>
                                <option value="2036/2037">2036/2037</option>
                                <option value="2037/2038">2037/2038</option>
                                <option value="2038/2039">2038/2039</option>
                                <option value="2039/2040">2039/2040</option>
	</select>
MYDOC;

 echo $shoy;
 }

 /**
  * Function to generate the YEAR-MON-DAY selection for a form.
  * This can be used in forms for which date needs to be selected
  * Display the select tags section for embedding into the form.
  * It takes as parameters the names to be used for the three
  * selections (YEAR, MONTH, DAY)
  * @param $yy, $mm, $dd
  */
 function selectdate($yy,$mm,$dd,$yya='',$mma='',$dda='',$class=''){
 $toshow = <<<MYDOC
 Day  <select name="$dd" class="$class">
								<option value="$dda">$dda</option>
								<option value="01">1</option>
								<option value="02">2</option>
								<option value="03">3</option>
								<option value="04">4</option>
								<option value="05">5</option>
								<option value="06">6</option>
								<option value="07">7</option>
								<option value="08">8</option>
								<option value="09">9</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>
								<option value="13">13</option>
								<option value="14">14</option>
								<option value="15">15</option>
								<option value="16">16</option>
								<option value="17">17</option>
								<option value="18">18</option>
								<option value="19">19</option>
								<option value="20">20</option>
								<option value="21">21</option>
								<option value="22">22</option>
								<option value="23">23</option>
								<option value="24">24</option>
								<option value="25">25</option>
								<option value="26">26</option>
								<option value="27">27</option>
								<option value="28">28</option>
								<option value="29">29</option>
								<option value="30">30</option>
								<option value="31">31</option>
								</select> Month
								<select name="$mm" class="$class">
								<option selected value="$mma">$mma</option>
								<option  value="01">January</option>
								<option value="02">February</option>
								<option value="03">March</option>
								<option value="04">April</option>
								<option value="05">May</option>
								<option value="06">June</option>
								<option value="07">July</option>
								<option value="08">August</option>
								<option value="09">September</option>
								<option value="10">October</option>
								<option value="11">November</option>
								<option value="12">December</option>
								</select> Year
								<select name="$yy" class="$class">
								<option selected value="$yya">$yya</option>
								<option value="1900">1900</option>
                                <option value="1901">1901</option>
                                <option value="1902">1902</option>
                                <option value="1903">1903</option>
                                <option value="1904">1904</option>
                                <option value="1905">1905</option>
                                <option value="1906">1906</option>
                                <option value="1907">1907</option>
                                <option value="1908">1908</option>
                                <option value="1909">1909</option>
                                <option value="1910">1910</option>
                                <option value="1911">1911</option>
                                <option value="1912">1912</option>
                                <option value="1913">1913</option>
                                <option value="1914">1914</option>
                                <option value="1915">1915</option>
                                <option value="1916">1916</option>
                                <option value="1917">1917</option>
                                <option value="1918">1918</option>
                                <option value="1919">1919</option>
                                <option value="1920">1920</option>
                                <option value="1921">1921</option>
                                <option value="1922">1922</option>
                                <option value="1923">1923</option>
                                <option value="1924">1924</option>
                                <option value="1925">1925</option>
                                <option value="1926">1926</option>
                                <option value="1927">1927</option>
                                <option value="1928">1928</option>
                                <option value="1929">1929</option>
                                <option value="1930">1930</option>
                                <option value="1931">1931</option>
                                <option value="1932">1932</option>
                                <option value="1933">1933</option>
                                <option value="1934">1934</option>
                                <option value="1935">1935</option>
                                <option value="1936">1936</option>
                                <option value="1937">1937</option>
                                <option value="1938">1938</option>
                                <option value="1939">1939</option>
                                <option value="1940">1940</option>
                                <option value="1941">1941</option>
                                <option value="1942">1942</option>
                                <option value="1943">1943</option>
                                <option value="1944">1944</option>
                                <option value="1945">1945</option>
                                <option value="1946">1946</option>
                                <option value="1947">1947</option>
                                <option value="1948">1948</option>
                                <option value="1949">1949</option>
                                <option value="1950">1950</option>
                                <option value="1951">1951</option>
                                <option value="1952">1952</option>
                                <option value="1953">1953</option>
                                <option value="1954">1954</option>
                                <option value="1955">1955</option>
                                <option value="1956">1956</option>
                                <option value="1957">1957</option>
                                <option value="1958">1958</option>
                                <option value="1959">1959</option>
                                <option value="1960">1960</option>
                                <option value="1961">1961</option>
                                <option value="1962">1962</option>
                                <option value="1963">1963</option>
                                <option value="1964">1964</option>
                                <option value="1965">1965</option>
                                <option value="1966">1966</option>
                                <option value="1967">1967</option>
                                <option value="1968">1968</option>
                                <option value="1969">1969</option>
                                <option value="1970">1970</option>
                                <option value="1971">1971</option>
                                <option value="1972">1972</option>
                                <option value="1973">1973</option>
                                <option value="1974">1974</option>
                                <option value="1975">1975</option>
                                <option value="1976">1976</option>
                                <option value="1977">1977</option>
                                <option value="1978">1978</option>
                                <option value="1979">1979</option>
                                <option value="1980">1980</option>
                                <option value="1981">1981</option>
                                <option value="1982">1982</option>
                                <option value="1983">1983</option>
                                <option value="1984">1984</option>
                                <option value="1985">1985</option>
                                <option value="1986">1986</option>
                                <option value="1987">1987</option>
                                <option value="1988">1988</option>
                                <option value="1989">1989</option>
                                <option value="1990">1990</option>
                                <option value="1991">1991</option>
                                <option value="1992">1992</option>
                                <option value="1993">1993</option>
                                <option value="1994">1994</option>
                                <option value="1995">1995</option>
                                <option value="1996">1996</option>
                                <option value="1997">1997</option>
                                <option value="1998">1998</option>
                                <option value="1999">1999</option>
                                <option value="2000">2000</option>
                                <option value="2001">2001</option>
                                <option value="2002">2002</option>
                                <option value="2003">2003</option>
                                <option value="2004">2004</option>
                                <option value="2005">2005</option>
                                <option value="2006">2006</option>
                                <option value="2007">2007</option>
                                <option value="2008">2008</option>
                                <option value="2009">2009</option>
                                <option value="2010">2010</option>
                                <option value="2011">2011</option>
                                <option value="2012">2012</option>
                                <option value="2013">2013</option>
                                <option value="2014">2014</option>
                                <option value="2015">2015</option>
                                <option value="2016">2016</option>
                                <option value="2017">2017</option>
                                <option value="2018">2018</option>
                                <option value="2019">2019</option>
                                <option value="2020">2020</option>
                                <option value="2021">2021</option>
                                <option value="2022">2022</option>
                                <option value="2023">2023</option>
                                <option value="2024">2024</option>
                                <option value="2025">2025</option>
								</select>
MYDOC;

 echo $toshow;
 }

/**
  * Function to generate the HOUR:MIN selection for a form.
  * This can be used in forms for which Time needs to be selected
  * Display the select tags section for embedding into the form.
  * It takes as parameters the names to be used for the three
  * selections (HOUR, MIN)
  * @param $hh, $mm
  */
 function selecttime($hh,$mm,$hha='',$mma='',$class=''){
 $showit = <<<MYDOC2
 Hour  <select name="$hh" class="$class">
                                <option value="$hha">$hha</option>
                                <option value="01">1</option>
                                <option value="02">2</option>
                                <option value="03">3</option>
                                <option value="04">4</option>
                                <option value="05">5</option>
                                <option value="06">6</option>
                                <option value="07">7</option>
                                <option value="08">8</option>
                                <option value="09">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                               </select>
                                Mins
                                <select name="$mm" class="$class">
                                <option selected value="$mma">$mma</option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                                <option value="24">24</option>
                                <option value="25">25</option>
                                <option value="26">26</option>
                                <option value="27">27</option>
                                <option value="28">28</option>
                                <option value="29">29</option>
                                <option value="30">30</option>
                                <option value="31">31</option>
                                <option value="32">32</option>
                                <option value="33">33</option>
                                <option value="34">34</option>
                                <option value="35">35</option>
                                <option value="36">36</option>
                                <option value="37">37</option>
                                <option value="38">38</option>
                                <option value="39">39</option>
                                <option value="40">40</option>
                                <option value="41">41</option>
                                <option value="42">42</option>
                                <option value="43">43</option>
                                <option value="44">44</option>
                                <option value="45">45</option>
                                <option value="46">46</option>
                                <option value="47">47</option>
                                <option value="48">48</option>
                                <option value="49">49</option>
                                <option value="50">50</option>
                                <option value="51">51</option>
                                <option value="52">52</option>
                                <option value="53">53</option>
                                <option value="54">54</option>
                                <option value="55">55</option>
                                <option value="56">56</option>
                                <option value="57">57</option>
                                <option value="58">58</option>
                                <option value="59">59</option>
                                </select>
MYDOC2;

 echo $showit;
 }
 
 /**
  * Function to display selection of numbers from 1 to 100
  * To be used with forms
  * @param $number, value already selected. Usually '' if nothing is selected
  * @param $num, value of the name field in the select tag
  * @return $html, HTML cod efor the select tag
  */
  function selectnum($number='', $num, $class=''){
  	$html = <<<MYDOC1
<select name="$num" class="$class">
<option selected value="$number">$number</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
								<option value="9">9</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>
								<option value="13">13</option>
								<option value="14">14</option>
								<option value="15">15</option>
								<option value="16">16</option>
								<option value="17">17</option>
								<option value="18">18</option>
								<option value="19">19</option>
								<option value="20">20</option>
								<option value="21">21</option>
								<option value="22">22</option>
								<option value="23">23</option>
								<option value="24">24</option>
								<option value="25">25</option>
								<option value="26">26</option>
								<option value="27">27</option>
								<option value="28">28</option>
								<option value="29">29</option>
								<option value="30">30</option>
								<option value="31">31</option>
								<option value="32">32</option>
								<option value="33">33</option>
								<option value="34">34</option>
								<option value="35">35</option>
								<option value="36">36</option>
								<option value="37">37</option>
								<option value="38">38</option>
								<option value="39">39</option>
								<option value="40">40</option>
								<option value="41">41</option>
								<option value="42">42</option>
								<option value="43">43</option>
								<option value="44">44</option>
								<option value="45">45</option>
								<option value="46">46</option>
								<option value="47">47</option>
								<option value="48">48</option>
								<option value="49">49</option>
								<option value="50">50</option>
								<option value="51">51</option>
								<option value="52">52</option>
								<option value="53">53</option>
								<option value="54">54</option>
								<option value="55">55</option>
								<option value="56">56</option>
								<option value="57">57</option>
								<option value="58">58</option>
								<option value="59">59</option>
								<option value="60">60</option>
								<option value="71">71</option>
								<option value="72">72</option>
								<option value="73">73</option>
								<option value="74">74</option>
								<option value="75">75</option>
								<option value="76">76</option>
								<option value="77">77</option>
								<option value="78">78</option>
								<option value="79">79</option>
								<option value="80">80</option>
								<option value="81">81</option>
								<option value="82">82</option>
								<option value="83">83</option>
								<option value="84">84</option>
								<option value="85">85</option>
								<option value="86">86</option>
								<option value="87">87</option>
								<option value="88">88</option>
								<option value="89">89</option>
								<option value="90">90</option>
								<option value="91">91</option>
								<option value="92">92</option>
								<option value="93">93</option>
								<option value="94">94</option>
								<option value="95">95</option>
								<option value="96">96</option>
								<option value="97">97</option>
								<option value="98">98</option>
								<option value="99">99</option>
								<option value="100">100</option>
</select>
MYDOC1;
 echo $html;
  }
  

/**
  * Function to generate the YEAR-MON-DAY for SCHEDULE SMS MESSAGE for a form.
  * This can be used in forms for which date needs to be selected
  * Display the select tags section for embedding into the form.
  * It takes as parameters the names to be used for the three
  * selections (YEAR, MONTH, DAY)
  * @param $yy YEAR form field name 
  * @param $mm MONTH form field name
  * @param $dd DAY form field name
  * @param $yya YEAR value
  * @param $mma MONTH value
  * @param $dda DAY value
  * @param $class CSS CLASS for display 
  */
 function scheduledate($yy,$mm,$dd,$yya='',$mma='',$dda='',$class=''){
 $toshowb = <<<MYDOCB
 Day  <select name="$dd" class="$class">
                        <option value="$dda">$dda</option>
                        <option value="01">1</option>
                        <option value="02">2</option>
                        <option value="03">3</option>
                        <option value="04">4</option>
                        <option value="05">5</option>
                        <option value="06">6</option>
                        <option value="07">7</option>
                        <option value="08">8</option>
                        <option value="09">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                        <option value="21">21</option>
                        <option value="22">22</option>
                        <option value="23">23</option>
                        <option value="24">24</option>
                        <option value="25">25</option>
                        <option value="26">26</option>
                        <option value="27">27</option>
                        <option value="28">28</option>
                        <option value="29">29</option>
                        <option value="30">30</option>
                        <option value="31">31</option>
                        </select> Month
                        <select name="$mm" class="$class">
                        <option selected value="$mma">$mma</option>
                        <option  value="01">January</option>
                        <option value="02">February</option>
                        <option value="03">March</option>
                        <option value="04">April</option>
                        <option value="05">May</option>
                        <option value="06">June</option>
                        <option value="07">July</option>
                        <option value="08">August</option>
                        <option value="09">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                        </select> Year
                        <select name="$yy" class="$class">
                        <option selected value="$yya">$yya</option>
                                <option value="2011">2011</option>
                                <option value="2012">2012</option>
                                <option value="2013">2013</option>
                                <option value="2014">2014</option>
                                <option value="2015">2015</option>
                                <option value="2016">2016</option>
                                <option value="2017">2017</option>
                                <option value="2018">2018</option>
                                <option value="2019">2019</option>
                                <option value="2020">2020</option>
                                <option value="2021">2021</option>
                                <option value="2022">2022</option>
                                <option value="2023">2023</option>
                                <option value="2024">2024</option>
                                <option value="2025">2025</option>
                        </select>
MYDOCB;

 echo $toshowb;
 } 
  
  /**
  * Function to display selection of numbers from 1 to 10
  * To be used with forms
  * @param string $tnum, value of the name field in the select tag
  * @param string $tnumber, value already selected. Usually '' if nothing is selected  
  * @return $thtml, HTML code for the select tag
  */
  function selectnumb($tnum, $tnumber='', $tclass=''){
      $thtml = <<<MYDOC0
<select name="$tnum" class="$tclass">
<option selected value="$tnumber">$tnumber</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
</select>
MYDOC0;
 echo $thtml;
  }


   /**
  * Function to display selection of COLORS 
  * To be used with forms
  * @param $selcolor, value already selected. Usually '' if nothing is selected
  * @param $color, value of the name field in the select tag
  * @param $sclass, vaslue for the class for the select tag
  * @return $htmlcolor, HTML code for the select tag
  */
  function selectcolor($selcolor='', $color, $sclass=''){
  	$htmlcolor = <<<MYDOC2
<select name="$color" id="$color" class="$sclass">
<option selected style="background-color: $selcolor" value="$selcolor">$selcolor</option>
                                <option value="">Default Color</option>
                                <option style="background-color: #000000" value="#000000">#000000</option>
								<option style="background-color: #003300" value="#003300">#003300</option>
								<option style="background-color: #009900" value="#009900">#009900</option>
								<option style="background-color: #33CC00" value="#33CC00">#33CC00</option>
								<option style="background-color: #33FF00" value="#33FF00">#33FF00</option>
								<option style="background-color: #666600" value="#666600">#666600</option>
								<option style="background-color: #66FF00" value="#66FF00">#66FF00</option>
								<option style="background-color: #333333" value="#333333">#333333</option>
								<option style="background-color: #000033" value="#000033">#000033</option>
								<option style="background-color: #006633" value="#006633">#006633</option>
								<option style="background-color: #00CC33" value="#00CC33">#00CC33</option>
								<option style="background-color: #330033" value="#330033">#330033</option>
								<option style="background-color: #339933" value="#339933">#339933</option>
								<option style="background-color: #33FF33" value="#33FF33">#33FF33</option>
								<option style="background-color: #669933" value="#669933">#669933</option>
								<option style="background-color: #FF00FF" value="#FF00FF">#FF00FF</option>
								<option style="background-color: #9900FF" value="#9900FF">#9900FF</option>
								<option style="background-color: #9999FF" value="#9999FF">#9999FF</option>
								<option style="background-color: #99FFFF" value="#99FFFF">#99FFFF</option>
								<option style="background-color: #CC00FF" value="#CC00FF">#CC00FF</option>
								<option style="background-color: #CCCCCC" value="#CCCCCC">#CCCCCC</option>
								<option style="background-color: #0000CC" value="#0000CC">#0000CC</option>
								<option style="background-color: #0000FF" value="#0000FF">#0000FF</option>
								<option style="background-color: #000066" value="#000066">#000066</option>
								<option style="background-color: #000099" value="#000099">#000099</option>
								<option style="background-color: #00CC99" value="#00CC99">#00CC99</option>
								<option style="background-color: #00FF99" value="#00FF99">#00FF99</option>
								<option style="background-color: #009966" value="#009966">#009966</option>
								<option style="background-color: #FFFF00" value="#FFFF00">#FFFF00</option>
								<option style="background-color: #FFCC33" value="#FFCC33">#FFCC33</option>
								<option style="background-color: #FFFF33" value="#FFFF33">#FFFF33</option>
								<option style="background-color: #FFFF00" value="#FFFF00">#FFFF00</option>
								<option style="background-color: #FF0000" value="#FF0000">#FF0000</option>
								<option style="background-color: #FF3300" value="#FF3300">#FF3300</option>
								<option style="background-color: #FF9900" value="#FF9900">#FF9900</option>
								<option style="background-color: #00FFFF" value="#00FFFF">#00FFFF</option>
								<option style="background-color: #A9A9A9" value="#A9A9A9">#A9A9A9</option>
								<option style="background-color: #B8860B" value="#B8860B">#B8860B</option>
								<option style="background-color: #00008B" value="#00008B">#00008B</option>
								<option style="background-color: #FF8C00" value="#FF8C00">#FF8C00</option>
								<option style="background-color: #00FF00" value="#00FF00">#00FF00</option>
								<option style="background-color: #8B0000" value="#8B0000">#8B0000</option>
								<option style="background-color: #FFFFFF" value="#FFFFFF">#FFFFFF</option>
</select>
MYDOC2;
 echo $htmlcolor;
  }
 
 /**
     * Function to display error messages in javascript
     *
     * Displays errors if any occurs in the process
     *
     * @access Public
     * @param string error_message to be displayed.
     *
     */
    function display_error($err) {
        echo "<script language='javascript'> alert('" . $err . "'); history.go(-1);</script>";
        exit();
    }
 /**
     * Function to display success messages in javascript
     *
     * Displays success after a success operation and then closes the window
     *
     * @access Public
     * @param string success_message to be displayed.
     *
     */
    function display_success($suc) {
        echo "<script language='javascript'> alert('" . $suc . "'); window.close();</script>";
        exit();
    }
/*****************************************************************************************************/
    /**
     * Function to display error messages in ezzzy Style Using Javascript of course
     *
     * Displays errors if any occurs in the process
     *
     * @access Public
     * @param string error_message to be displayed.
     *
     */
    function tim_msg($title,$text,$time) {
        //echo "<script language='javascript'> alert('" . $err . "'); history.go(-1);</script>";
        echo "<script language='JavaScript' type='text/javascript'>
           swal({
          title: '".$title."',
          text: '".$text."',
          timer: '".$time."',
          showConfirmButton: false
            });
        </script>";
        //exit();
    }

    /**
     * Function to display Success messages in ezzzy Style Using Javascript of course
     *
     * Displays errors if any occurs in the process
     *
     * @access Public
     * @param string error_message to be displayed.
     *
     */
    function tim_success($title,$text) {
        echo "<script language='JavaScript' type='text/javascript'>
        swal('".$title."', '".$text."','success');
        </script>";
        //exit();
    }

    /**
     * Function to display error messages in ezzzy Style Using Javascript of course
     *
     * Displays errors if any occurs in the process
     *
     * @access Public
     * @param string error_message to be displayed.
     *
     */
    function tim_error($title,$text) {
        echo "<script language='JavaScript' type='text/javascript'>
        swal('".$title."', '".$text."','error');
        </script>";
        //exit();
    }

    /**
     * Function to display input box in ezzzy Style Using Javascript of course
     *
     * Displays errors if any occurs in the process
     *
     * @access Public
     * @param $iph String
     * @param $title String
     * @param $text String
     * @param ShowCancelutton ($scb) true/false
     * @param CloseOnConfirm ($coc) true/false
     *
     */
    function ezzzy_input($title,$text,$scb,$coc,$iph) {
        echo "<script language='JavaScript' type='text/javascript'>
           swal({
          title: '".$title."',
          text: '".$text."',
          type: 'input',
          showCancelButton: '".$scb."',
          closeOnConfirm: '".$coc."',
          animation: 'slide-from-top',
          inputPlaceholder: '".$iph."'
          });

        </script>";
        //exit();
    }

    /**
     * Function to display Confirm message in ezzzy Style Using Javascript of course
     *
     * Displays errors if any occurs in the process
     *
     * @access Public
     * @param $title String
     * @param $text String
     *
     */
    function ezzzy_confirm($title,$text,$aftext) {
        echo "<script language='JavaScript' type='text/javascript'>
           swal({
          title: '".$title."',
          text: '".$text."',
          type: 'warning',
          showCancelButton: 'true',
          confirmButtonColor: '#DD6B55',
          confirmButtonText: 'Yes, delete it!',
          closeOnConfirm: 'false'
          },
          function(){
            swal('Deleted!', '".$aftext."', 'success');

          });

        </script>";
        //exit();
    }
/*****************************************************************************************************/

  /**
  * Function to display month in numbers like May as '05'
  * in the process of doing anything in this program.
  *
  * @name MakeitNum
  * @param $monthval, value
  * @return monthnum
  */
  function MakeitNum($monthval){
      // prepare the month to display month name instead of number, i.e, May instead of '05'
        switch ($monthval) {
            case "January":
                $monthnum = '01';
                break;
            case "February":
                $monthnum = '02';
                break;
            case "March":
                $monthnum = '03';
                break;
            case "April":
                $monthnum = '04';
                break;
            case "May":
                $monthnum = '05';
                break;
            case "June":
                $monthnum = '06';
                break;
            case "July":
                $monthnum = '07';
                break;
            case "August":
                $monthnum = '08';
                break;
            case "September":
                $monthnum = '09';
                break;
            case "October":
                $monthnum = '10';
                break;
            case "November":
                $monthnum = '11';
                break;
            case "December":
                $monthnum = '12';
                break;
           }
          
          return $monthnum;
  }
  
 /**
  * Function to display month in Words like '05' as 'May'
  * in the process of doing anything in this program.
  *
  * @name MakeitVal
  * @param $monthnum, value
  * @return $monthval
  */ 
  function MakeitVal($monthnum){
      // prepare the month to display month name instead of number, i.e, May instead of '05'
         
        switch ($monthnum) {
            case '01':
                $monthval = "January";
                break;
            case '02':
                $monthval = "February";
                break;
            case '03':
                $monthval = "March";
                break;
            case '04':
                $monthval = "April";
                break;
            case '05':
                $monthval = "May";
                break;
            case '06':
                $monthval = "June";
                break;
            case '07':
                $monthval = "July";
                break;
            case '08':
                $monthval = "August";
                break;
            case '09':
                $monthval = "September";
                break;
            case '10':
                $monthval = "October";
                break;
            case '11':
                $monthval = "November";
                break;
            case '12':
                $monthval = "December";
                break;
           }
        return  @$monthval;
  }
  
 
 /**
  * Function to PREPARE Time from selcttime function
  * into a good format for database.
  * 
  * @name MakeDataTime
  * @param $hr, $min
  * @return $tformat
  */ 
  function MakeDataTime($HR,$MIN){
      // prepare in the format the 12:05:00'
      $SC = '00';
      $tformat = $HR.':'.$MIN .':'.$SC;

        return  $tformat;         
  }

 /**
  * Function to break Time values from database in a nice format for forms.
  * 
  * @name formDataTime
  * @param $time
  * @return array $tdat
  */ 
  function formDataTime($DTIME){
      // prepare in the format the 12:05:00'
      if (@preg_match(":",$DTIME)) {
             // e.g 12:05:00 (HH-MIN-SS)
            $tdat = explode(":",$DTIME);
            // $mfld[0] = 12; $mfld[1]=05; $mfld[2]=00
      }

      return  $tdat;         
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
        if (@preg_match(",", $mystr)) {
            // more than one record, so it can be exploded
            return true;
        }
        else {
            return false;
        }
    }

  
 /**
     * Function to convert a string of values to arrays
     *
     * @access Private
     * @param string data_to_convert to array.
     * @return $marr, array of values from data
     */
    function makearray($mstrg) {
        // check if values are separated by comma ','.
        //C;LEAN
        $marr = array();
        if (havecomma($mstrg)) {
            $marr = explode(",", $mstrg);
        }
        return $marr;
    }

    /**
     * Function to convert a string of values to arrays
     *
     * @access Private
     * @param string data_to_convert to array.
     * @return $marr, array of values from data
     */
    function makearray1($mstrg) {
        // check if values are separated by comma ','.
        //C;LEAN
        if (havecomma($mstrg)) {
            $marr = explode(",,", $mstrg);
        }
        return $marr;
    }

    /**
     * Function to convert a string of values to arrays
     *
     * @access Private
     * @param string data_to_convert to array.
     * @return $marr, array of values from data
     */
    function makearray2($mstrg) {
        // check if values are separated by comma ','.
        //C;LEAN
        if (havecomma($mstrg)) {
            $marr = explode(" ", $mstrg);
        }
        return $marr;
    }

    /**
     * Function to formart a given number to the cureency fomart
     *
     * @access Private
     * @param string data_to_format to currency.
     * @return $$symbol.$sign .($j ? substr($i,0, $j) + $t : '').preg_replace('/(\d{3})(?=\d)/',"$1" + $t,substr($i,$j)) ;
     */

    function toMoney($val,$symbol='',$r=2)
{


    $n = $val;
    $c = is_float($n) ? 1 : number_format($n,$r);
    $d = '.';
    $t = ',';
    $sign = ($n < 0) ? '-' : '';
    $i = $n=number_format(abs($n),$r);
    @$j = (($j = $i.length) > 3) ? $j % 3 : 0;

   return  $symbol.$sign .($j ? substr($i,0, $j) + $t : '').@preg_replace('/(\d{3})(?=\d)/',"$1" + $t,substr($i,$j)) ;

}
    /**************************************************************************/
    /**************************************************************************/
    /**************************************************************************/
    /**************************************************************************/
    /**************************************************************************/
    /**************************************************************************/

/**
     * Function to convert a integer Numbers to English Words
     *
     * @access Private
     * @param string data_to_convert to Words.
     * @return $string, Words
     */
    function convert_number_to_words($number) {

    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'One',
        2                   => 'Two',
        3                   => 'Three',
        4                   => 'Four',
        5                   => 'Five',
        6                   => 'Six',
        7                   => 'Seven',
        8                   => 'Eight',
        9                   => 'Nine',
        10                  => 'Ten',
        11                  => 'Eleven',
        12                  => 'Twelve',
        13                  => 'Thirteen',
        14                  => 'Fourteen',
        15                  => 'Fifteen',
        16                  => 'Sixteen',
        17                  => 'Seventeen',
        18                  => 'Eighteen',
        19                  => 'Nineteen',
        20                  => 'Twenty',
        30                  => 'Thirty',
        40                  => 'Fourty',
        50                  => 'Fifty',
        60                  => 'Sixty',
        70                  => 'Seventy',
        80                  => 'Eighty',
        90                  => 'Ninety',
        100                 => 'Hundred',
        1000                => 'Thousand',
        1000000             => 'Million',
        1000000000          => 'Billion',
        1000000000000       => 'Trillion',
        1000000000000000    => 'Quadrillion',
        1000000000000000000 => 'Quintillion'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}//end of the function that converts numbers to word
 
?>