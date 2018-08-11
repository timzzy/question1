<?php


/**
 * ************************************************************************************
 * 	              TIMZZY FUNCTIONS	v2.0										  *
 * 				  (c) 2017	 OGUNDARE TIMILEHIN                    				  *
 * ************************************************************************************ 
 */
/***
 * @version 1.00
 * @package SamMysql_Class
 * @name TImzzy Functions
 * Comprises of basic funtion for repeated tesks
 **/

	

/***
	########################################################

  * Function name: db_connect
  * This function is to connect to the database with pdo
  
*/
	function db_conect($dir){

		include_once("$dir/config.php");
	}



/***
	########################################################

  * Function name: inc
  * This function is to to be including files to the web page
  
*/
	function inc($path,$file){

		if (!isset($path)) {
			echo "Please Spceifybthe Path, N => NO PATH, 1 => Back 1time,, 2=> Back 2times, 3=> back 3Times,, 4=> back 4Times";
			exit;
		}

		##############NOW lets set the Processing
		if ($path =="0") {
			include_once("pages/$file.php");
		}


		if ($path =="1") {
			include_once("../pages/$file.php");
		}



		if ($path =="2") {
			include_once("../../pages/$file.php");
		}


		if ($path =="3") {
			include_once("../../../pages/$file.php");
		}


		
		if ($path =="4") {
			include_once("../../../../pages/$file.php");
		}		


		if ($path =="5") {
			include_once("../../../../../pages/$file.php");
		}
		return true;
	}




/***
	########################################################

  * Function name: countrows
  * This function is to count the bumber of rows of the supplied query
  
*/
	function countrows($val){

		$cval=mysqli_num_rows($val);
		return $cval;
	}



	/***
	########################################################

  * Function name: fetcharray
  * This function is to fetch array of the query supplied
  
*/
	function fetcharray($val){

		$cval=mysqli_fetch_array($val);
		return $cval;
	}




	/***
	########################################################

  * Function name: trackcode
  * This function is get the last id inserted into the database
*/  

 function trackcode() {
 	//set of characters to use
 	$myv = "ABCDE689FGHJKLMNPQRSTUVWXYZ23456789abcdefghijkmn757pqrstuvwxyz";
    
     $str = $myv{rand(0, strlen($myv)-1)}; 
     $str .= $myv{rand(0, strlen($myv)-1)}; 
     $str .= $myv{rand(0, strlen($myv)-1)}; 
     $str .= $myv{rand(0, strlen($myv)-1)}; 
     $str .= $myv{rand(0, strlen($myv)-1)}; 
     $str .= $myv{rand(0, strlen($myv)-1)};          
     $str .= $myv{rand(0, strlen($myv)-2)};          
     $str .= $myv{rand(0, strlen($myv)-1)};          
     $str .= $myv{rand(0, strlen($myv)-1)};          
     $str .= $myv{rand(0, strlen($myv)-2)};          
     $str .= $myv{rand(0, strlen($myv)-1)};          
     $str .= $myv{rand(0, strlen($myv)-1)};          
     $str .= $myv{rand(0, strlen($myv)-1)};          
     $str .= $myv{rand(0, strlen($myv)-1)};          
     $genpass = $str; 
	return $genpass;
 }







	/***
	########################################################

  * Function name: thelastid
  * This function is get the last id inserted into the database
*/  

 function thelastid($insert) {
 	//set of characters to use
 	$theid = mysqli_insert_id($con,$insert);

	return $theid;
	TRUE;
 }



/***
	########################################################

  * Function name: capitalizefirst
  * This function is capitalize the first Letter in a string
*/  

 function capitalizefirst($word) {
  
  if (!empty($word)) {
  $val= ucfirst($word);
  return $val;
    }
 }



/***
  ########################################################

  * Function name: capitalizewords
  * This function is capitalize the first Letter of words in a string
*/  

 function capitalizeword($word) {

if (empty($word)) {
      showerror('Missing String capitalizeword funcion');
  }
 
  $val= ucwords($word);
  return $val;

 }



/***
  ########################################################

  * Function name: mss
  * This function is to return an amount of a substring in a word
*/  

 function mss($word,$start,$finish) {
 
 	$val= substr($word, $start,$finish);
 	return $val;

 }


 function mysubstring($word,$start,$finish) {
 
  $val= substr($word, $start,$finish);
  return $val;

 }



/***
	########################################################

  * Function name: mysubstring
  * This function is to return an amount of a substring in a word
*/  

 function mystrip($word) {
 
 	$val= strip_tags($word, '<b><br><p><i><h1><h2><h3><h4><h5><h6><li><ul><u><center>');
  $val=str_replace('"', '', $val);
  $val=str_replace("'", "", $val);
 	return $val;

 }


function formatDate($date){
	return date('g:i a', strtotime($date));
}







/***
	########################################################

  * Function name: time_since
  * This function is to return the number of years and month from a date
  @params input is a date time formatted as strttotime UNIX time stamp
*/  

	function time_since($date) {
    $original=strtotime($date);
    // array of time period chunks
    $chunks = array(

        array(60 * 60 * 24 * 365 , 'year'),

        array(60 * 60 * 24 * 30 , 'month'),

        array(60 * 60 * 24 * 7, 'week'),

        array(60 * 60 * 24 , 'day'),

        array(60 * 60 , 'hour'),

        array(60 , 'minute'),
    );

     

    $today = time(); /* Current unix time  */
    $since = $today - $original;

     

    // $j saves performing the count function each time around the loop

    for ($i = 0, $j = count($chunks); $i < $j; $i++) {    
        $seconds = $chunks[$i][0];
        $name = $chunks[$i][1];

        

        // finding the biggest chunk (if the chunk fits, break)
        if (($count = floor($since / $seconds)) != 0) {
            // DEBUG print "<!-- It's $name -->n";
          break;

        }

    }

     

    $print = ($count == 1) ? '1 '.$name : "$count {$name}s";  
    if ($i + 1 < $j) {

        // now getting the second item

        $seconds2 = $chunks[$i + 1][0];

        $name2 = $chunks[$i + 1][1];

         

        // add second item if it's greater than 0

        if (($count2 = floor(($since - ($seconds * $count)) / $seconds2)) != 0) {

            $print .= ($count2 == 1) ? ', 1 '.$name2 : ", $count2 {$name2}s";

        }

    }

    return $print;
}



/***
  ########################################################

  * Function name: writeToFile
  * This function is to return the number of years and month from a date
  @params input is a date time formatted as strttotime UNIX time stamp
*/  


function writeToFile($textToWrite,$filePath){

  $logging=$textToWrite.PHP_EOL.PHP_EOL;
  $handle = fopen($filePath, 'a');
  fwrite($handle, $logging);  // write the Data to file
  fclose($handle);           // close the file

}




/***
  ########################################################

  * Function name: absolute_url
  * This function is to return  the absolute URl path link for a web page
  @params No  input
*/  


function absolute_url(){

$url2= $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
  return $url2;

}




/***
  ########################################################

  * Function name: absolute_url2
  * This function is to return  the absolute URl path link for a web page without the host
  @params No  input
*/  


function absolute_url2(){

$url2=$_SERVER['REQUEST_URI'];

  return $url2;

}




/***
  ########################################################

  * Function name: absolute_e_url
  * This function is to return the encoded absolute URl path link for a web page 
  @params No  input
*/  


function absolute_e_url(){

$url1= $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$url= rawurlencode($url1);//for whatsapp
return $url;

}



/***
  ########################################################

  * Function name: to_upper
  * This function is to return the uppercase for any string
  @params No  input
*/  


function to_upper($str){

if (!empty($str)) {
$dstr= strtoupper($str);
return $dstr;
 }
}




/***
  ########################################################

  * Function name: to_lower
  * This function is to return the lowercase for any string
  @params No  input
*/  


function to_lower($str){

if (!empty($str)) {
  $dstr= strtolower($str);
  return $dstr;
}



}


/***
  ########################################################

  * Function name: showerror
  * This function is to show an error message
  @params No  input
*/  



function showerror($err) {
    echo "<script language='javascript'> alert('Sorry! " . $err . "'); history.go(-1);</script>";
    exit();
  }



  /***
  ########################################################

  * Function name: even_no
  * This function is to get maybe a value lis even or odd
  @params No  input
*/  



function even_no($no) {

if (empty($no) OR (!is_numeric($no)) ) {
  showerror("Function even_no is not a number or has empty arguement, Please Check");
  return false;
}
    elseif (is_numeric($no)) {
      $eval= ceil($no%2);
      if ($eval==0){
        echo 'No is even';
      }
      elseif ($eval ==1){
        echo 'No is odd';
      }
    }//ends if numeric

}





  /***
  ########################################################

  * Function name: no_typ
  * This function is to get maybe a value  odd or even
  @params No  input
*/  



function no_typ($no) {

if (empty($no) OR (!is_numeric($no)) ) {
  showerror("Function even_no is not a number or has empty arguement, Please Check");
  return false;
}
    elseif (is_numeric($no)) {
      $eval= ceil($no%2);
      if ($eval==0){
        $typ = "EVEN";//even
        return $typ;
      }
      elseif ($eval ==1){
       $typ = "ODD";//odd
        return $typ;
      }
    }//ends if numeric

}


  /***
  ########################################################

  * Function name: to number
  * This function is to get and return the number format of a value
  @params No  input
*/  



function to_number($no) {

//if (empty($no) OR (!is_numeric($no)) ) {
  //showerror("Function to_number is not a number or has empty arguement, Please Check");
  //return false;
//}
    if (is_numeric($no) AND (!empty($no))) {
      $d_num= number_format($no);
        return $d_num;
      }//ends if numeric
    }



/***
  ########################################################

  * Function name: get_referer
  * This function is to get and return the page refere and redirect user to the referer  else supply the default
  @params No  input
*/  

  function get_referer($default){

    @$m_rdr=$_SERVER['HTTP_REFERER'];
    $def=$default;//the default page to redirect to
  
  //Check file existence  
    if ((!file_exists($def .".php") AND ($def .".html") )) {
    showerror("Default Page does not exist as html or php file, please check you arguement in get_referer function");
    return false;
  }

    if (empty($default) ) {
  showerror(" Check Arguement values for get_referer ");
  return false;
}
else{
  
  if (!is_null($m_rdr)) {
    @$rdr=$_SERVER['HTTP_REFERER'];
     
  }
    else{
    $rdr="./?rdr=".$default;
    }

    return $rdr;
 }//ends else that say we can proceed
 

}



/***
  ########################################################

  * Function name: get_id
  * This function is to get and return the the id of a concatennated string in which case the id is always the first element in the array e.g 10-i-am-a-boy
  @params No  input
*/  

  function get_id($expr){

  if (!empty($expr)) {

    //Lets Explode the share to get the REc_id
    @$ex1= explode('-', $expr);
    @$postid= $ex1[0];
    

      return $postid;//the extracted post id from the statemnet
     }   


}



/***
  ########################################################

  * Function name: get_text
  * This function is to get and return the text of a concatennated string in which case the id is always the first element in the array e.g 10-i-am-a-boy
  @params No  input
*/  

  function get_text($expr){

  if (!empty($expr)) {
           //Lets Explode the share to get the REc_id
    @$ex1= explode('-', $expr);
    @$postid= $ex1[0];
    $p=$postid."-";
    $tit2= str_replace($p, ' ', $expr);
    $tit2=ucwords(str_replace('-', ' ', $tit2));

      return $tit2;//the extracted post id from the statemnet
     }   


}






/***
  ########################################################

  * Function name: share
  * put out put in form rec_id-post_title
  
*/  

  function share($id,$text){

  if (isset($id)  AND isset($text)) {

    $linktitle=str_replace(' ' , '-' , $text);
    $title=$id."-".strtolower($linktitle);
    return$title; 
     }
     else{
      showerror('Arguement not complete in share function');
    }


}



//to format the time to the real time


  function get_time(){

       $unixtime = time() + 3600;// i added 3600 because of the time on server is 1hr late
       return date('g:ia',$unixtime);
  
}


//To display  dismissible alert so that i can use it as a function
  //@params:: arguement

  function show_alert($btn,$text){

      if (empty($text)) {
        showerror('Text arguement for the show_alert function not specified, kindly try');
      }
      elseif (empty($btn)) {
        showerror('Kindly specify the brn color class');
      }

      echo "<div class=\"alert alert-$btn alert-dismissible ofade show\" role=\"alert\">
        <!--<strong>Holy guacamole!</strong>-->
        $text
        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
          <span aria-hidden=\"true\">&times;</span>
        </button>
      </div>" ;
  
}


//To display  get the IP of a user
  //@params:: arguement

 function getRealIp() {
       if (!empty($_SERVER['HTTP_CLIENT_IP'])) {  //check ip from share internet
         $ip=$_SERVER['HTTP_CLIENT_IP'];
       } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  //to check ip is pass from proxy
         $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
       } else {
         $ip=$_SERVER['REMOTE_ADDR'];
       }
       return $ip;
    }



    //To replace any space with dash
  //@params:: arguement

 function removeSpace($data) {
       if (!isset($data)) {
         showerror('Arguement Not given');
       }
       else{
        $data=rtrim($data);//remove white spaces
        $data1=str_replace("   ", "", $data);
        $data2=str_replace(" ", "-", $data1);
      }

       return $data2;

    }


    //To replace any space with dash
  //@params::  No arguement

 function strip($data) {
       if (!isset($data)) {
         showerror('Arguement Not given');
       }
       else{
        $value=strip_tags($data);
      }

       return $value;
    }






  /**
   * Function Name: Generate Voucherz 
   * Generate a random string, using a cryptographically secure 
   * pseudorandom number generator (random_int)
   * 
   * @param int $length      How many characters do we want?
   * @param string $keyspace A string of all possible characters
   *                         to select from
   * @return string
   */
  function ogenerate_voucherz(){
    $myv = '01M!NOPQ@>RSTUop<q3456789MVWXcdefghijklmnop<qO3op<q345678945bcde#MYZ^2aYZ^2abcdefghijklmnop<q3456789op<Pq345bcdefghijklmnop<q345op<q34567896789/rstuvwx*&yzABCDop<q3456789EFGHIJKLcdefghijop<q3456789klmnop<q34op<q34567895bcde';

    //if (!isset(Mlength)) {
      //showerror("The legth of the pin is not given");
    //}//Ends: if length

      //$pieces = [];
      //$max = mb_strlen($keyspace, '8bit') - 1;
      /*for ($i = 0; $i < $length; ++$i) {
          $pieces []= $keyspace[rand(0, $max)];
      }*/
      //return implode('', $pieces);
      $str = $myv{rand(0, strlen($myv)-1)}; 
      $str .= $myv{rand(0, strlen($myv)-2)}; 
      $str .= $myv{rand(0, strlen($myv)-1)}; 
      $str .= $myv{rand(0, strlen($myv)-2)}; 
      $str .= $myv{rand(0, strlen($myv)-1)}; 
      $str .= $myv{rand(0, strlen($myv)-2)}; 
      $str .= $myv{rand(0, strlen($myv)-1)}; 
      $str .= $myv{rand(0, strlen($myv)-2)}; 
      $str .= $myv{rand(0, strlen($myv)-1)}; 
      $str .= $myv{rand(0, strlen($myv)-2)}; 
      $str .= $myv{rand(0, strlen($myv)-1)}; 
      $str .= $myv{rand(0, strlen($myv)-2)}; 
      return $str;
  }                





  function generate_voucherz($length = 10) {
    //$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    //$characters = '01M!NOPQ@>RSTUop<q3456789MVWXcdefghijklmnop<qO3op<q345678945bcde#MYZ^2aYZ^2abcdefghijklmnop<q3456789op<Pq345bcdefghijklmnop<q345op<q34567896789/rstuvwx*&yzABCDop<q3456789EFGHIJKLcdefghijop<q3456789klmnop<q34op<q34567895bcde';
    $characters = 'QYUI384756WEQYUIOIO1029384756WERYUIOQYUIOIO1029384756WERYUIO1029384756110293847561OIO1029384756WERYUIO10293847561QYQYUIOIO1029384756WERYUIO10293847561UIOIO1029384756WEQYUIOIO1029384756WERYUIOQYUIOIO1029384756WERYUIO1029384756110293847561RYUIO10293847561029384756WERYUIO1029384756TYUIO102IO1029384756WERYUIO10293847569384IO1029384756WERYUIO1029384756756VYUIO10YUIGDHEEKO1029384756293YUIO1029384UEEOEMRYWWOP75684756PLKJHGFDSAZYUIO102YUIOUUIO10YUIGDHEEKO1029384756293YUIO1029384UEEOEMRIO10YUIGDHEEKO1029384756293YUIO1029384UEEOEMR10293847569SSIHRHR384756XCVBNQYUIOIf384756WEQYUIOIO1029384756WERYUIOQYUIOIO1029384756WERYUIO1029384756110293847561O1029384756WERYUIO10293847561M';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}  