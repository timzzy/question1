<?php 
	require '../../admin/config.php';
 		
 		$sqltran = mysqli_query($con, "SELECT * FROM discussion_comment ")or die(mysqli_error($con));
		$arrVal = array();
 		
		$i=1;
 		while ($rowList = mysqli_fetch_array($sqltran)) {
 								 
						$name = array(
								'num' => $i,
 	 		 	 				'first'=> $rowList['comment'],
	 		 	 				'last'=> $rowList['status']
 	 		 	 			);		


							array_push($arrVal, $name);	
			$i++;			
	 	}
	 		 echo  json_encode($arrVal);		
 

	 	mysqli_close($con);
?>   
 