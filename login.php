<?php
		

	$pagetitle="Login .:.";	
    include_once('pages/header.php');
	

	

?>
	
<main><!--Starts main conntent-->

		<div class="bg_color_2">
			<div class="container-fluid padg30 bash ">
					
					<center><h4>Login with your credentials</h4></center><br><hr>



					<div class="form-container">
					<div class="col-md-3"></div>
					<div class="col-md-5 bwhite padg20">


						<form method="POST" action="admin/?rdr=validator" enctype="multipart/form-data">
								
								<div class="form-group">
									<label>User Name</label>
									<input type="text" name="username" class="form-control" required>
								</div>

								<div class="form-group">
									<label>Password</label>
									<input type="text" name="password" class="form-control" required>
								</div>


								<input type="submit" name="" value="Login" class="btn btn-primary btn-sm">

						</form>

						</div>
						<div class="clearfix"></div>


					</div><!--form-container-->
					
		
		
		</div>
		</div>
	</main><!-- /main content -->

	<?php include_once('pages/footer.php');?>