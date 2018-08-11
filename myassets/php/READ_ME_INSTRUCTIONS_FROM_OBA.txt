		<!-- //**********************  PAGINATOR STARTS HERE ****************************// -->
					<?php

						$tbl_name="centerusers";		//table name
						$limit = 2; 	//how many items to show per page
						$targetpage = "paginator2.php"; 	//your file name  (the name of this file)		
						include_once("myassets/php/paginator.php");
						echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"myassets/php/pagee.css\">";
							$sql = "SELECT * FROM $tbl_name   order by rec_id desc LIMIT $start, $limit "; //the query i want to perfom

						//Go and copy this while loop where to use it
						while($row = mysqli_fetch_array($my_paginator_query)){


						?>

							<a href=""><?php echo $row['typee']?></a><br>

					<?php
					}//ends while

					?>



					<center>	<?=$pagination; //Copy this to where you want those numbers to be showing under each Page ?> 
					</center>

					<!-- //**********************  PAGINATOR ENDS HERE ****************************// -->
