<?php


function getCreditcard()
{

  $query = "select CONCAT(visa_id ,'@',visa_cardno,'(', bank,')') as 'str1' from visa";

  $return_str="";
  if($conn=db_connect())
  	{
  	  $result = mysql_query($query) or die('Query failed: ' . mysql_error());
  	  if(!$result)
  	  {
  		echo '<p>Unable to get list from database.</p>';
  		return false;
	  }
   while ($row = mysql_fetch_assoc($result))
		{
		 $str = $row['str1'];

		 if (strlen($return_str) == 0)
			$return_str = $str;
		 else
			$return_str = $return_str.':'.$str;

        } //end while
     if ($result) mysql_free_result($result);
   }
   return $return_str;
}

function load_Creditcard_Transaction($id) {

     if(!$id)
       return false;

     if(!($conn=db_connect()))
       return false;

     $query = "select * from visa_transaction where transaction_id = $id";

     if (DEBUG==1) echo $query.'<br>';

     $result = mysql_query($query) or die('SELECT failed: ' . mysql_error());

     if(!$result)
     {
       echo "Cannot retrieve this Credit card Transaction";
       return false;
     }
     return mysql_fetch_array($result,MYSQL_ASSOC);
}

function delete_Creditcard_Transaction($id) {
    if (!$id)
    	return false;

	if(!($conn=db_connect()))
	    return false;

	$query = "delete from visa_transaction where transaction_id = $id";

	if (DEBUG == 1) echo $query.'<br>';

	$result = mysql_query($query);
  	if ( !$result ) {
     	echo 'delete_Creditcard Transaction:SQL error.';
     	return false;
     }

  	return true;
}

function delete_Creditcard_Transaction_CreditCard($id) {
    if (!$id)
    	return false;

	if(!($conn=db_connect()))
	    return false;

	$query = "delete from visa_transaction where visa_id = $id";

	if (DEBUG == 1) echo $query.'<br>';

	$result = mysql_query($query);
  	if ( !$result ) {
     	echo 'delete_Creditcard Transaction CreditCard:SQL error.';
     	return false;
     }

  	return true;
}

function insert_Creditcard_Transaction($details)
{
  // extract order_details out as variables
  extract($details);

  include("constants.php");
  $conn = db_connect();
  $mdate = date('Y-m-d H:i:s');
  $success = 0;

  if (DEBUG == 1) echo 'action:'.$action.'<br>';

  $purpose = addslashes($txPurpose);

  if ($txDate)
     $txDate = date('Y-m-d', strtotime($txDate));

  if ($action == "EDIT") {

    $query = "update visa_transaction set transaction_date ='$txDate', amount = $txAmount,
              purpose = '$purpose', visa_id = $creditcard, status = '$status',
			  lastmodifieddate = '$mdate' where transaction_id = $id";

    if (DEBUG==1) echo $query.'<br>';

    $msg = 'Record successfully Updated.';

    $result = mysql_query($query, $conn) or die('counter UPDATE error: '.mysql_errno().', '.mysql_error());
	if (!$result) return $id.'@SQL error:UPDATE@0';
        $msg = "Credit Card Transaction '$id' successfully Updated.";

    $success = 1;

  }

  if ($action == "SAVE") {


      $query = "insert into visa_transaction(transaction_date, amount,
               purpose, visa_id, status, lastmodifieddate)
               VALUES('$txDate', $txAmount,'$purpose', $creditcard,
               '$status', '$mdate')";

      if (DEBUG==1) echo $query.'<br>';

	  $result = mysql_query($query, $conn) or die('counter INSERT error: '.mysql_errno().', '.mysql_error());
	  $id = mysql_insert_id();
	  if (!$result) return $id.'@SQL error:INSERT@0';
        $msg = "Credit Card Transaction #'$id' successfully Inserted.";

      $success = 1;
  }

  return $id.'@'.$msg.'@'.$success;
}


function load_CreditCard($id) {
     if(!$id)
       return false;

     if(!($conn=db_connect()))
       return false;

     $query = "select * from visa where visa_id = $id";

     if (DEBUG==1) echo $query.'<br>';
     $result = mysql_query($query) or die('SELECT failed: ' . mysql_error());

     if(!$result)
     {
       echo "Cannot retrieve this Credit Card";
       return false;
     }
     return mysql_fetch_array($result,MYSQL_ASSOC);
}


function insert_CreditCard($details)
{
  // extract order_details out as variables
  extract($details);

  if(!($conn=db_connect()))
       return false;

  $mdate = date('Y-m-d H:i:s');
  $success = 0;

  $bankname = addslashes($bankname);
  $cardnumber = addslashes($cardnumber);
  $expirydate = addslashes($expirydate);
  $payment_start = addslashes($payment_start);
  $payment_end = addslashes($payment_end);
  $points = addslashes($points);

  if (DEBUG == 1) echo 'action:'.$act.'<br>';

  if ($act == "EDIT") {
    $query = "update visa set visa_cardno='$cardnumber', bank='$bankname',
              expirydate='$expirydate',
    		  payment_start=$payment_start,
    		  payment_end=$payment_end,
    		  points = $points,
    		  lastmodifieddate='$mdate'
    		  where visa_id = $id";

	if (DEBUG==1) echo $query.'<br>';
    $msg = "Credit Card '$cardnumber' Record successfully Updated.";
    $result = mysql_query($query, $conn) or die('counter UPDATE error: '.mysql_errno().', '.mysql_error());
    if (!$result) return $id.'@SQL error:UPDATE@0'; else $success = 1;
  }

  if ($act == "SAVE") {

    // Check whether name exists
    $query = "select * from visa where visa_cardno = '$cardnumber'";
    //echo $query.'<br>';
    $result = mysql_query($query, $conn) or die('SELECT error: '.mysql_errno().', '.mysql_error());
    $msg = "";
    $num = mysql_num_rows($result);
    if ($num > 0) {
 	  $msg = "Credit Card #'".$cardnumber."' exists.";
 	  mysql_free_result($result);
 	  $id = -1;
 	  return $id.'@'.$msg.'@0';
    }

    $query = "insert into visa(visa_cardno, bank, expirydate, payment_start, payment_end, points, userid, lastmodifieddate)
			 values
			 ('$cardnumber', '$bankname', '$expirydate', $payment_start, $payment_end, $points, ".$_SESSION['userid'].", '$mdate')";

    if (DEBUG==1) echo $query.'<br>';
	$result = mysql_query($query, $conn) or die('counter INSERT error: '.mysql_errno().', '.mysql_error());
	if (!$result) return $id.'@SQL error:INSERT@0';
    $id = mysql_insert_id();
    $msg = "Credit Card '$cardnumber' successfully Inserted.";
    $success = 1;

  }

  return $id.'@'.$msg.'@'.$success;
}


function delete_CreditCard($id) {
    if (!$id)
    	return false;

	if(!($conn=db_connect()))
	    return false;

	delete_Creditcard_Transaction_CreditCard($id);

	$query = "delete from visa where visa_id = $id";

	if (DEBUG == 1) echo $query.'<br>';

	$result = mysql_query($query);
  	if ( !$result ) {
     	echo 'delete_CreditCard:SQL error.';
     	return false;
     }

  	return true;
}



?>