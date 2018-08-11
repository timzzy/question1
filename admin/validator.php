<?php
//define(DOC_ROOT,dirname(__FILE__)); // To properly get the config.php file
$username = $_POST['username']; //Set UserName
$password = $_POST['password']; //Set Password
//$username = $_POST['username']; //Set UserName
//$typ = $_POST['ltyp'];
require_once('../myassets/functions/da_function.php');
require_once('../myassets/functions/timzzy_function.php');
if($username == '' || $password == ''){
  display_error('All fields are required please');
}
$msg ='';
if(isset($username, $password)) {
    ob_start();
    include_once('config.php'); //Initiate the MySQL connection
    //require_once('../functions/sammysql.inc.php');
    //$ezzzy = new SamMysql($con);
    // To protect MySQL injection (more detail about MySQL injection)
    $myusername = stripslashes($username);
    $mypassword = stripslashes($password);
    $myusername = mysqli_real_escape_string($con,$myusername);
    $mypassword = mysqli_real_escape_string($con,$mypassword);
     
    $sql="SELECT * FROM admins WHERE username='$myusername' and password='$mypassword'";
    $result=mysqli_query($con,$sql) or die("Could not select table");
    $row=mysqli_fetch_array($result);
    if (mysqli_num_rows($result) === 1) {
      $name= $row['name'];
      $uid= $row['member_id'];

        
          //1. CASE ADMIN =======================
          session_name("Admin");
          session_start();
          $_SESSION['name']= $name;
          $_SESSION['uid']=$uid ;
          $_SESSION['who'] = "ADMIN";
          $_SESSION['log']="YES";
          header("location:./");
        
        


    }//If there is only 1 row
    else{
      die('Record not found, Please use the right credentials');
    }

    ob_end_flush();
}//end of the if isset $username & $password
else {
    header("location:../?rdr=home&msg=Please enter some username and password");
}

?>