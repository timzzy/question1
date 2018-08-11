<?php
$mytitle = " | Admin";
include_once('../pages/admin_header.php'); 
?>
      <!-- Left side column. contains the logo and sidebar -->
      <?php include_once('../pages/admin_leftpanel.php'); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Welcome: <?php echo $aname; ?>

          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <!--Beginning of My Main Contents-->
              
              <div class="col-md-7">

               
              </div><!--col-md-7-->
              <div class="clearfix"></div>


            <!--Ending of My Main Contents-->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

      <!-- Main Footer -->
      <?php include_once('../pages/adminfooter.php'); ?>