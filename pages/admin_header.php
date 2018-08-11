<?php

  session_name("Admin");
  session_start();
  if(@$_SESSION['log'] == null && @$_SESSION['log']!="YES"){
    //echo "Sorry, You are not logged in. Please log in to be able to view this page";
    header('Location: ../?rdr=login');
    exit;
}


 include_once('config.php'); 
 require_once('../myassets/functions/da_function.php');
 require_once('../myassets/functions/da_sql.inc.php');
require_once('../myassets/functions/timzzy_function.php');
 $handle = new SamMysql($dbC);
 $adminid = $_SESSION['uid'];
 $aname = $_SESSION['name'];
$timer=time();



$countMembers = countrows($handle->getallresult('members') );

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin:: </title>

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    
        
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="css/font-awesome.min.css">

    <!-- Ionicons -->
    <link rel="stylesheet" href="css/ionicons.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">

    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">

    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">

    <!-- Morris chart -->
    <link rel="stylesheet" href="plugins/morris/morris.css">

    <!-- jvectormap -->
    <link rel="stylesheet" href="plugins/jvectormap/jquery-jvectormap-1.2.2.css">

    <!-- Date Picker -->
    <link rel="stylesheet" href="plugins/datepicker/datepicker3.css">

    <!-- Daterange picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker-bs3.css">

    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


    <!-- ############################################## My asset includes  For Admin-->


          <!--Table Style -->
        <link rel="stylesheet" href="../myassets/tables/jquery.dataTables.css" type="text/css" />
        <script type="text/javascript" src="../myassets/tables/jquery.js"></script>
        <script language="javascript" src="../myassets/tables/jquery.dataTables.js"></script>
        <!-- / table Style -->



      <link type="text/css" href="../myassets/css/bootstrap.min.css" rel="stylesheet"><!--responsible for those border in tables-->
    

      <!--<script src="../myassets/responsive_table/js/jquery-1.11.1.min.js"></script>
      <script src="../myassets/responsive_table/js/bootstrap.min.js"></script>
      <script src="../myassets/responsive_table/js/bootstrap-table.js"></script>-->


  



        <!--Editor -->
        <script type="text/javascript" src="../myassets/myeditor/wysiwyg.js"></script><!--my editor did not wrk with this admin panel-->
        <!--//Editor -->
        

       <!-- scripts for sweetalert -->
        <script src="../myassets/dist/sweetalert.min.js"></script>
        <link rel="stylesheet" type="text/css" href="../myassets/dist/sweetalert.css">
        <link rel="stylesheet" type="text/css" href="../myassets/css/thecss.css">
      <!-- / scripts for sweetalert -->





  <!-- ############################################## Ends My asset includes  for Admin-->


  </head>
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

      <header class="main-header">
        <!-- Logo -->
        <a href="./?rdr=home" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>A</b>LT</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b> ADMIN</b></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- Messages: style can be found in dropdown.less-->
             





              <li class="dropdown messages-menu" title=" <?php echo $countMembers;?> Total members">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-user"></i>
                  <span class="label label-success"><?php echo $countMembers;?></span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">You have <?php echo $countMembers;?> messages</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                      <a href="./?rdr=members">View</a>

                    </ul>
                  </li>
                </ul>
              </li>






              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <p>
                     <?php echo $aname;?>
                      <small></small>
                    </p>
                  </li>
                  <!-- Menu Body -->
                  <li class="user-body">
                    <div class="col-xs-4 text-center">
                      <!--<a href="#">Followers</a>-->
                    </div>
                    <div class="col-xs-4 text-center">
                      <!--<a href="#">Sales</a>-->
                    </div>
                    <div class="col-xs-4 text-center">
                     <!-- <a href="#">Friends</a>-->
                    </div>
                  </li>
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                    </div>
                    <div class="pull-right">
                      <a href="./?rdr=logout" class="btn btn-default btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
              <!-- Control Sidebar Toggle Button -->
              <li>
                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
              </li>
            </ul>
          </div>
        </nav>
      </header>
