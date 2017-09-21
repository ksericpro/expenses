<?php
include_once('constants.php');

//echo 'sqlhost='.mysql_host.' password='.mysql_password;
$con = mysqli_connect(mysql_host,mysql_user,mysql_password,mysql_dbname);

// Check connection
if (mysqli_connect_errno())
{
  echo "<br/>Failed to connect to MySQL: " . mysqli_connect_error();
}
 
echo "<br/><center>database connected</center>";
?>