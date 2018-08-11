<?php
		

	$pagetitle="Sign Up .:.";	
    include_once('pages/header.php');
	

	

?>
	
<main><!--Starts main conntent-->

		<div class="bg_color_2">
			<div class="container-fluid padg30 bash ">
					
					<center><h4 class="bwhite padg20"><a href="./?rdr=home">&laquo; Back Home</a>  &nbsp;&nbsp;&nbsp;Fill the form below to complete your application process</h4></center><br>



					<div class="form-container">
					<div class="col-md-3"></div>
					<div class="col-md-5 bwhite padg20">


						<?php
							if (countrows(mysqli_query($con, " SELECT * FROM members WHERE typee='MEMBER' ")) >= 4 ) {
						echo "<center><h4 class=\"red\"> Application Closed, Try again later </h4></center>";

							}//ENDS:: if that says applicnts are less than 4
						
						 else{
						
						?>	

						<form method="POST" action="./?rdr=processor" enctype="multipart/form-data">
								
								<div class="form-group">
									<label>First Name</label>
									<input type="text" name="firstname" class="form-control" required>
								</div>

								<div class="form-group">
									<label>Surname</label>
									<input type="text" name="surname" class="form-control" required>
								</div>

								<div class="form-group">
									<label>Phone Number</label>
									<input type="number" min="1" name="phone" class="form-control" required>
								</div>

								<div class="form-group">
									<label>Email</label>
									<input type="email" name="email" class="form-control" required>
								</div>

								<div class="form-group">
									<label>Cover Letter</label>
									<textarea name="coverletter"  rows="4" required class="form-control"></textarea>
								</div>

								<div class="form-group">
									<label>Upload Passport (Max 100kb)</label>
									<input type="file" name="pic" class="form-control" accept="image/jpg" required>
								</div>

								<div class="form-group">
									<label>Upload Resume (Max 2Mb)</label>
									<input type="file" name="pic2" class="form-control" accept=".doc,.docx,.pdf" required>
								</div>

								<input type="submit" name="submitdata" value="Submit Application" class="btn btn-primary btn-sm">

						</form>
						

						<?php }//Ends if ?>



						</div>
						<div class="clearfix"></div>


					</div><!--form-container-->
					
		
		
		</div>
		</div>
	</main><!-- /main content -->

	<?php include_once('pages/footer.php');?>