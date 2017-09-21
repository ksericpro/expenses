<?php
function getCreditcard()
{
	include("db_new.php");
	$sql = "select CONCAT(visa_id ,'@',visa_cardno,'(', bank,')') as 'str1' from visa";

	$return_str="";
  
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

function load_Creditcard_Transaction($id) {

     if(!$id)
       return false;

	 include("db_new.php");

     $sql = "select * from visa_transaction where transaction_id = $id";
	 if (DEBUG==1) echo $sql.'<br>';
	 $result = $con->query($sql);
	 return mysqli_fetch_array($result,MYSQLI_ASSOC);
     
}

function delete_Creditcard_Transaction($id) {
    if (!$id)
    	return false;

	include("db_new.php");

	$sql = "delete from visa_transaction where transaction_id = $id";

	if (DEBUG == 1) echo $sql.'<br>';

	if ($con->query($sql) === TRUE) {
		//$msg = 'Category \''.$name. '\' Successfully Added.';
	} else {
		echo 'delete_Creditcard Transaction:SQL error.';
     	$con->close();
		return false;
	}
		 
	$con->close();
  	return true;
}

function delete_Creditcard_Transaction_CreditCard($id) {
    if (!$id)
    	return false;

	include("db_new.php");

	$sql = "delete from visa_transaction where visa_id = $id";

	if (DEBUG == 1) echo $sql.'<br>';

	if ($con->query($sql) === TRUE) {
		//$msg = 'Category \''.$name. '\' Successfully Added.';
	} else {
		echo 'delete_Creditcard Transaction CreditCard:SQL error.';
     	$con->close();
		return false;
	}

	$con->close();
  	return true;
}

function insert_Creditcard_Transaction($details)
{
  // extract order_details out as variables
  extract($details);

  include("db_new.php");
  //$conn = db_connect();
  $mdate = date('Y-m-d H:i:s');
  $success = 0;

  if (DEBUG == 1) echo 'action:'.$action.'<br>';

  $purpose = addslashes($txPurpose);

  if ($txDate)
     $txDate = date('Y-m-d', strtotime($txDate));

  if ($action == "EDIT") {

    $sql = "update visa_transaction set transaction_date ='$txDate', amount = $txAmount,
              purpose = '$purpose', visa_id = $creditcard, status = '$status',
			  lastmodifieddate = '$mdate' where transaction_id = $id";

    if (DEBUG==1) echo $sql.'<br>';

    $msg = 'Record successfully Updated.';

	if ($con->query($sql) === TRUE) {
		$msg = "Credit Card Transaction '$id' successfully Updated.";
	} else {
		$con->close();
		return $id.'@SQL error:UPDATE@0';
	}
    /*$result = mysql_query($query, $conn) or die('counter UPDATE error: '.mysql_errno().', '.mysql_error());
	if (!$result) return $id.'@SQL error:UPDATE@0';
        $msg = "Credit Card Transaction '$id' successfully Updated.";
*/
    $success = 1;

  }

  if ($action == "SAVE") {


      $sql = "insert into visa_transaction(transaction_date, amount,
               purpose, visa_id, status, lastmodifieddate)
               VALUES('$txDate', $txAmount,'$purpose', $creditcard,
               '$status', '$mdate')";

      if (DEBUG==1) echo $sql.'<br>';

	  if ($con->query($sql) === TRUE) {
		  $msg = "Credit Card Transaction #'$id' successfully Inserted.";
	  } else {
		  $con->close();
		  return $id.'@SQL error:UPDATE@0';
	  }
	  //$result = mysql_query($query, $conn) or die('counter INSERT error: '.mysql_errno().', '.mysql_error());
	  $id = mysqli_insert_id();
	  //echo "id=".$id;
	  //if (!$result) return $id.'@SQL error:INSERT@0';
        //$msg = "Credit Card Transaction #'$id' successfully Inserted.";

      $success = 1;
  }
  $con->close();
  return $id.'@'.$msg.'@'.$success;
}


function load_CreditCard($id) {
     if(!$id)
       return false;

     //if(!($conn=db_connect()))
     //  return false;
 
	 include("db_new.php");

     $sql = "select * from visa where visa_id = $id";

     if (DEBUG==1) echo $sql.'<br>';
	 
	 $result = $con->query($sql);
	 return mysqli_fetch_array($result,MYSQLI_ASSOC);
     //return mysql_fetch_array($result,MYSQL_ASSOC);
}

function delete_CreditCard($id) {
    if (!$id)
    	return false;

	include("db_new.php");

	delete_Creditcard_Transaction_CreditCard($id);

	$sql = "delete from visa where visa_id = $id";

	if (DEBUG == 1) echo $sql.'<br>';

	if ($con->query($sql) === TRUE) {
		return true;
	} 	else {
		echo 'delete_CreditCard:SQL error.';
		$con->close();
     	return false;
	}
	
	$con->close();
}

function insert_CreditCard($details)
{
  // extract order_details out as variables
  extract($details);

  //if(!($conn=db_connect()))
   //    return false;
  include("db_new.php");

  $mdate = date('Y-m-d H:i:s');
  $success = 0;

  $bankname = addslashes($bankname);
  $cardnumber = addslashes($cardnumber);
  $expirydate = addslashes($expirydate);
  $payment_start = addslashes($payment_start);
  $payment_end = addslashes($payment_end);
  $points = addslashes($points);
 // $id = addslashes($id);

  if (DEBUG == 1) echo 'action:'.$act.'<br>';

  if ($act == "EDIT") {
    $sql = "update visa set visa_cardno='$cardnumber', bank='$bankname',
              expirydate='$expirydate',
    		  payment_start=$payment_start,
    		  payment_end=$payment_end,
    		  points = $points,
    		  lastmodifieddate='$mdate'
    		  where visa_id = $id";

	if (DEBUG==1) echo $sql.'<br>';
    
	
	if ($con->query($sql) === TRUE) {
		$msg = "Credit Card '$cardnumber' Record successfully Updated.";
		$success = 1;
	} else {
		$con->close();
		return $id.'@SQL error:UPDATE@0';
	}
   
  }
  
  if ($act == "SAVE") {

    // Check whether name exists
    $sql = "select * from visa where visa_cardno = '$cardnumber'";
    //echo $query.'<br>';
    $result = mysql_query($query, $conn) or die('SELECT error: '.mysql_errno().', '.mysql_error());
    $msg = "";
	
	$result = $con->query($sql);

	if ($result->num_rows > 0) {
		// output data of each row
		$msg = "Credit Card #'".$cardnumber."' exists.";
 	    $id = -1;
		// Free result set
		mysqli_free_result($result);
		$con->close();
 	    return $id.'@'.$msg.'@0';		
	} 
   
    $sql = "insert into visa(visa_cardno, bank, expirydate, payment_start, payment_end, points, userid, lastmodifieddate)
			 values
			 ('$cardnumber', '$bankname', '$expirydate', $payment_start, $payment_end, $points, ".$_SESSION['userid'].", '$mdate')";

    if (DEBUG==1) echo $sql.'<br>';
	if ($con->query($sql) === TRUE) {
		$msg = "Credit Card '$cardnumber' successfully Inserted.";
		$success = 1;
	} 	else {
		$con->close();
		return $id.'@SQL error:INSERT@0';
	}
	
	/*$result = mysql_query($query, $conn) or die('counter INSERT error: '.mysql_errno().', '.mysql_error());
	if (!$result) return $id.'@SQL error:INSERT@0';
    $id = mysql_insert_id();
    $msg = "Credit Card '$cardnumber' successfully Inserted.";
    $success = 1;*/

   }

 
   $con->close();
   return $id.'@'.$msg.'@'.$success;
}


?>