<?php

require('config.php');
$result = $con->query("SELECT * FROM members  ") or die('Unable to select');///Query here

$data = array();//Initializing the array
while ($row = $result->fetch_array()) {
    $data[] = array(
    	   "id"=>$row['member_id'],
    	   "name"=>$row['surname']." ".$row['firstname'],
    	   "phone"=>$row['phone'],
    	   "email"=>$row['email'],
    	   "coverletter"=>$row['coverletter'],
    	   "passport"=>$row['passport'],
           "resume"=>$row['resume'],
           "date_applied"=>$row['date_added'],
    	   "time_applied"=>$row['time_added']
    	);
}
echo json_encode($data);//Echo it out so that angular js can do ddecode it

	


?>
