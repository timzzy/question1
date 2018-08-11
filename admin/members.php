<?php
  
  
include_once('../pages/admin_header.php'); 

      
?>
      <!-- Left side column. contains the logo and sidebar -->
      <?php include_once('../pages/admin_leftpanel.php'); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
           

          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content bwhite">
            <!--Beginning of My Main Contents-->
                 
                     <script type="text/javascript">
                     $(document).ready(function() {
                          $('#ecxample').DataTable();
                      } );
                    </script>



                              <div class="col-md-12">
                                <h4>View All members Here</b></h4><hr>


                  
                <div  ng-controller="membersController">

                <div class="table-responsive">
                 <table id="example" class="display table table-striped table-bordered table-hover" cellspacing="0">
                  
                    <thead>
                  <tr>
                    <th>S/N</th>
                    <th>Full Name</th>
                    <th>Phone/Email</th>
                    <th>Picture</th>
                    <th>Cover Letter</th>
                    <th>Registered on</th>
                  </tr>
                  </thead>

                     

                      <div >
                      <tr ng-repeat="member in members">
                        <td>{{member.id}}</td>
                        <td>{{member.name}}</td>
                        <td>{{member.phone}} <br>{{member.email}} </td>
                        <td>
                          <img src="gallery/{{member.passport}}" width="50" height="50"> <br>
                          <a href="gallery/{{member.passport}}" target="_blank">View Full Image</a>
                        </td>
                        <td>{{member.coverletter}}</td>
                        <td>{{member.date_applied}} at {{member.time_applied}}</td>

                                            



                      </tr>

                        


                      <div>


                         


                                          <?php
                                            //$r++;
                                            //}//ends the default while loop
                                          ?>

                                </table>
                            </div>
                          </div>
                        </div>

                              
                             

                            <div class="clearfix"></div> 

                

                <div class="clearfix"></div>
            <!--Ending of My Main Contents-->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

      <!-- Main Footer -->
      <?php include_once('../pages/adminfooter.php'); ?>