<?php
		
	
	$pagetitle="processor::";
    include_once('pages/header.php');
	

	

?>
	
<main><!--Starts main conntent-->

		<div class="bg_color_2">
			<div class="container-fluid padg30 bash ">
					
					<center><h4>Submission Details</h4></center><br><hr>



					<div class="form-container">
					<div class="col-md-3"></div>
					<div class="col-md-5 bwhite padg20">

						<?php
							
								
								
				//STARTS:: Processing****************************
				if (isset($_POST['submitdata'])) {
					
					
					//1. Lets validate the inputs
					

                    $firstname = mystrip(mysqli_real_escape_string($con, $_POST['firstname']));
                    $surname = mystrip(mysqli_real_escape_string($con, $_POST['surname']));
                    $phone = mystrip(mysqli_real_escape_string($con, $_POST['phone']));
                    $email = mystrip(mysqli_real_escape_string($con, $_POST['email']));
                    $coverletter = mystrip(mysqli_real_escape_string($con, $_POST['coverletter']));
                    $typee="MEMBER";//
                    $timer=time();//
                    $timereg = prepdate(time());//to insert the time registered here
                    $datereg = date('Y-m-d');//to insert the date here
                    //$timereg = get_time();//to insert the time registered here
                    $raw_time=get_time();

                    

            	   //2. Lets check kif there is no duplicate entry
                if(countrows($handle->getallrow('email',$email,'members')) >=1){
                    $error[] = "Email already exist, kindly choose another email";
                }
                if(countrows($handle->getallrow('phone',$phone,'members')) >=1){
                    $error[] = "Phone Number already exist, kindly choose another phone Number";
                }
                if (strlen($phone) < 11) {
                  $error[] ="Phone number too short";
                }
                       


                //3. Lets upload the passport Now

              if (is_uploaded_file($_FILES['pic']['tmp_name'])) {//This handles the passport
                 
               $target="admin/gallery";
               $p = $_FILES['pic']['type'];

                  $siz=$_FILES['pic']['size'];
                  if ($siz > 102400) {// 
                    display_error('Passport Size to big, Max of 100kb Uploads');
                  }

               $pi = explode("/",$p);
               $uiname = $timer."-".basename($_FILES['pic']['name']); // true name of image

               // copy the temp document to the place for storage
              $move=move_uploaded_file($_FILES['pic']['tmp_name'], $target . "/" .$uiname);

                if (!$move) {
                  display_error("Passprt Not Uploade Succesfully, Try again.");
                }

               //prepare document for database
               // prepare new name for picture
               $newpic = $uiname;
               $filestore = $target ."/".$newpic;
               $passport=$newpic;
               
               /*********************************************************************************************/
             
             }//Ends::paspport upload


  			//4. Lets upload the resume Now

              if (is_uploaded_file($_FILES['pic2']['tmp_name'])) {//This handles the resume
                 
               $target="admin/gallery";
               $p = $_FILES['pic2']['type'];

                  $siz=$_FILES['pic2']['size'];
                  if ($siz > 2097152) {//
                    display_error('Resume Size to big, Max of 2Mb Uploads');
                  }

               $pi = explode("/",$p);
               $uiname = $timer."-".basename($_FILES['pic2']['name']); // true name of image

               // copy the temp document to the place for storage
              $move=move_uploaded_file($_FILES['pic2']['tmp_name'], $target . "/" .$uiname);

                if (!$move) {
                  display_error("Resume Not Uploade Succesfully, Try again.");
                }

               //prepare document for database
               // prepare new name for picture
               $newpic = $uiname;
               $filestore = $target ."/".$newpic;
               $resume=$newpic;
               
               /*********************************************************************************************/
             
             }//Ends::resume upload



                       
                  	//5. display the errors if there is a value in the $error variable
                    	if (isset($error)) {
                    		//echo count($error);
                    			
                    		foreach ($error as $val) {
                    		echo "<li class=\"padg5 bwhite\" style=\"color:#ff0000\"> <i class=\"fa fa-dot-circle-o\"></i> $val</li>";
                    		
                    	}//Ends: foreach
                    	echo "<hr>";
                   	  }//Ends I;; there is error
                  else{//All is well no error

                    //4. Lets insert into the database using PDO if all is well
						$sth = $dbh->prepare("INSERT INTO members (firstname,surname,phone,email,coverletter,passport,resume,date_added,time_added,typee) VALUES (:firstname,:surname,:phone,:email,:coverletter,:passport,:resume,:date_added,:time_added,:typee)");
						
						$sth->bindParam(':firstname', $firstname);
						$sth->bindParam(':surname', $surname);
						$sth->bindParam(':phone', $phone);
						$sth->bindParam(':email', $email);
						$sth->bindParam(':coverletter', $coverletter);
						$sth->bindParam(':passport', $passport);
						$sth->bindParam(':resume', $resume);
						$sth->bindParam(':date_added', $datereg);
						$sth->bindParam(':time_added', $timereg);
						$sth->bindParam(':typee', $typee);
						$result=$sth->execute();

						if ($result) {
						echo "<div class=\"bwhite green font15 panel-title\"> <span style=\"\"> <i class=\"fa fa-dot-circle-o\"></i> Successful Registration. Your details have been submitted.</span><br></div><br> ";
						  tim_success("Success","Your details have been submitted.","");
						  echo "<h3><a href=\"javascript:history.go(-1)\"> &laquo;Go Back</a></h3>";
						}
						else{
							echo "record not added";
						}//Ends:: else that says the record is not inserted ito the DB
						
						
				}//Ends Else All is well, no $error set
				
			//ENDS:: Processing*********************



							}//Ends if there is a data to submit
							else{

								echo "wrong Link";
							}

						?>




						
				</div>
				<div class="clearfix"></div>


					</div><!--form-container-->
					
		
		
		</div>
		</div>
	</main><!-- /main content -->

	<?php include_once('pages/footer.php');?>