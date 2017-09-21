<?php
include_once('constants.php');
  
function login($username, $password)
{
	include_once('db_new.php');
	$sql = "select CONCAT(u.userID ,'@',u.Name ,'@',u.Role,'@', s.SettingsID) as 'str1' from user u, settings s
         where u.UserID = s.UserID
         and u.LoginID='$username'
         and u.password=sha1('$password')";
	 
	echo $sql;
  
	$result = $con->query($sql);

	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			//echo "stock id: " . $row["stock_id"]. " - Name: " . $row["stock_name"]. "<br>";
			$str = $row['str1'];
			//echo "<br/>Closing SQL Conection";
			mysqli_free_result($result);
			$con->close();
			return $str;
		}
	} 
	$con->close();
	return null;
}

///////////////////////
// Check valid user
/////////////////////
function check_valid_user()
{
 // echo "Helo";

  session_start();

  if (isset($_SESSION['valid_user']))
  {
      //echo 'Logged in as '.$_SESSION['valid_user'].'.';
      //echo '<br />';
      ;
  }
  else
  {
     // they are not logged in
     echo 'You are not logged in.<br />';
     header($MAINDIR.'/logout.php');
  }
}

///////////////////
// Check Super User
///////////////////
function checkUser($str) {
	if ($_SESSION['role'] == SUPERUSER)
	  echo 'href="'.$str.'"';
	else {
	  $msg = 'alert("You are not authorised.");';
	  echo "href='javascript:$msg'";
	  }
}

?>
