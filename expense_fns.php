<?php

function getCategory(&$db)
{
	// check if username is unique
	include('db_new.php');
	if (!isset($_SESSION['valid_user'])) session_start();
	$sql = "select CONCAT(CategoryID ,'@',Name) as 'str1' from category where UserID = ".$_SESSION['userid']. " order by Name";

	//echo $sql;
	$return_str = '';
	$result = $con->query($sql);

	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			$str = $row['str1'];

			if (strlen($return_str) == 0)
				$return_str = $str;
			else
				$return_str = $return_str.':'.$str;
		}
	} 
	$con->close();
	return $return_str;
}


function getCategoryID($db, $cat_name, $userid)
{
	// check if username is unique
	include('db_new.php');
	if (!isset($_SESSION['valid_user'])) session_start();
	$sql = "select CategoryID from category where UserID = ".$userid. " AND Name like '%$cat_name%'";
	$result = $con->query($sql);

	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			$str = $row['str1'];

			if (strlen($return_str) == 0)
				$return_str = $str;
			else
				$return_str = $return_str.':'.$str;
		}
	} 
  //echo $sql;
  /*$return_str = '';
  if ( !($result = $db->sql_query($sql)) ) {
  	 ;
   }

  if ( $row = $db->sql_fetchrow($result) )
  {
		do
		{
		 $str = $row['str1'];

		 if (strlen($return_str) == 0)
			$return_str = $str;
		 else
			$return_str = $return_str.':'.$str;

   		}
   	    while ( $row = $db->sql_fetchrow($result) );

        $db->sql_freeresult($result);
   }
  //$db->sql_close();*/
	$con->close();
	return $return_str;
}
?>
