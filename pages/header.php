<?php

   //include_once('pages/date_compare.php'); 
   include_once('admin/config.php'); 
 require_once('myassets/functions/da_function.php');
 require_once('myassets/functions/da_sql.inc.php');
 require_once('myassets/functions/timzzy_function.php');

$handle = new SamMysql($dbC);
$timer=time();
?>

<!doctype html>
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=2">

    <title><?php echo $pagetitle;?></title>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700' rel='stylesheet' type='text/css'>

     <!--Custom Stylesheet -->
    <link href="bootstrap/css//bootstrap.min.css" rel="stylesheet">
    <link href="myassets/css/thecss.css" rel='stylesheet' type='text/css' />
    <link href="bootstrap/css/font-awesome.min.css" rel="stylesheet"> 
    <link rel="stylesheet" href="css/ocustom.css" type="text/css" />

    
  <!-- ############################################## My asset includes -->

        <script type= "text/javascript" src="myassets/js/countries.js"></script>

        <link href="myassets/css/thecss.css" rel='stylesheet' type='text/css' />

         <!-- scripts for the ajax submit function -->
        <script src="myassets/js/jquery-1.12.4-jquery.min.js"></script>
        <!-- //scripts for the ajax submit function -->


        <!--Editor -->
        <script type="text/javascript" src="myassets/myeditor/wysiwyg.js"></script><!--my editor did not wrk with this admin panel-->
        <!--//Editor -->

       <!-- scripts for sweetalert -->
        <script src="myassets/dist/sweetalert.min.js"></script>
        <link rel="stylesheet" type="text/css" href="myassets/dist/sweetalert.css">
      <!-- / scripts for sweetalert -->

  <!-- ############################################## Ends My asset includes -->
<link rel="shortcut_icon" href="favicon.ico" type="image/x-icon" />

    </head>