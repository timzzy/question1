<?php
	$dbhost='localhost';
	$dbuser='root';
	$dbpass='';
	$dbname="upperlink";
	
	// Now let us connect to the database
	$con=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);
	$con2 = new mysqli($dbhost,$dbuser,$dbpass,$dbname);
	//$dbc=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);
	$dbC = mysqli_connect($dbhost, $dbuser, $dbpass,$dbname) or die('Error Connecting to MySQL DataBase');

	// Check connection
if (mysqli_connect_errno($con))
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }




########################################---PDO REGION--##################################################

try 
{
# MS SQL Server and Sybase with PDO_DBLIB
$dbh = new PDO("mysql:host=$dbhost; dbname=$dbname", $dbuser, $dbpass);
$db = new PDO("mysql:host=$dbhost; dbname=$dbname", $dbuser, $dbpass);
}
catch
(PDOException $e) {
echo "This is a PDO connection error message,  ". $e->getMessage();
}

########################################---//PDO REGION--##################################################

?>