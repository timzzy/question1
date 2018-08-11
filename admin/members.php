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
                          $('#example').DataTable();
                      } );
                    </script>



                              <div class="col-md-12">
                                <h4>View All members Here</b></h4><hr>


                             

                <div class="table-responsive">
                 <table id="example" class="display table table-striped table-bordered table-hover" cellspacing="0">
                  
                    <thead>
                  <tr>
                    <th>S/N</th>
                    <th>Full Name</th>
                    <th>Passport</th>
                    <th>View Details</th>
                    <th>Registered on</th>
                  </tr>
                  </thead>

                      <?php
                      

                          $r=1;
                          $result=mysqli_query($con,"SELECT * from members order by member_id desc ") or die("cannot select table");
                          while($row=mysqli_fetch_array($result)){
                      ?>

                      <tr>
                        <td><?php echo $r;?></td>
                        <td><?php echo $row['surname']." ".$row['firstname'];?></td>
                        <td><?php if(empty($row['passport'])){echo "No picture";}else{?>
                          <img src="gallery/<?php echo $row['passport'];?>" width="50" height="50"> 
                          <?php }//end else?>
                        </td>
                        <td><a href="" data-toggle="modal" data-target="#membermodal<?php echo $row['member_id']; ?>" class="btn btn-xs btn-primary">View Details</a></td>
                        <td><?php echo $row['date_added']." ".$row['time_added'];?></td>

                      </tr>



                           <!--##############################################modal -->
            <div class="modal fade" id="membermodal<?php echo $row['member_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                          <div class="modal-dialog">
                              <div class="modal-content">
                                  <div class="modal-header">
                                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                      <center><h4 class="modal-title">Submission details for <?php echo $row['surname']." ".$row['firstname']; ?></h4></center>
                                  </div>
                                  <div class="modal-body">

                                      <p>
                                        Passport<br>
                                        <?php if(empty($row['passport'])){echo "No picture";}else{?>
                                      <center><img src="gallery/<?php echo $row['passport'];?>" xwidth="50" xheight="250" class="img-responsive"> </center>
                                      <?php }//end else?>
                                    </p><br>
                                      
                                     <p> <b>NAME:</b> &nbsp;&nbsp; <?php echo $row['surname']." ".$row['firstname'];?><br /></p>
                                      <p><b>PHONE:</b> &nbsp;&nbsp;<?php echo $row['phone']; ?><br /></p>
                                      <p><b>EMAIL:</b> &nbsp;&nbsp; <?php echo $row['email']; ?><br /></p>
                                      <p><b>COVER LETTER:::</b> &nbsp;&nbsp; <?php echo $row['coverletter'];?> <br /></p>
                                      <p><b>RESUME:::</b> &nbsp;&nbsp; <a href="gallery/<?php echo $row['resume']; ?>" target="_blank">Click here </a> <br /></p>
                                      <p><b>Applied on:</b> &nbsp;&nbsp; <?php echo $row['date_added']." at ".$row['time_added']; ?><br /></p>
                                      


                                  </div>
                                  <div class="modal-footer">
                                      <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <!-- //##############################################modal -->



                                          <?php
                                            $r++;
                                            }//ends the default while loop
                                          ?>

                                </table>
                            </div>
                            </div>

                              
                             

                            <div class="clearfix"></div> 

                

                <div class="clearfix"></div>
            <!--Ending of My Main Contents-->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

      <!-- Main Footer -->
      <?php include_once('../pages/adminfooter.php'); ?>