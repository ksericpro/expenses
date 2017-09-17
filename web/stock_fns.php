<?php

function get_Fees()
{
   if(!($conn=db_connect()))
	    return false;

   $query = "select * from stocks_settings where touse = 1";

   if (DEBUG==1) echo $query.'<br>';

   $result = mysql_query($query) or die('SELECT failed: ' . mysql_error());

   if(!$result)
     {
       echo "Cannot retrieve Stock Settings";
       return false;
     }
   return mysql_fetch_array($result,MYSQL_ASSOC);
}

function delete_Stock($id) {
    if (!$id)
    	return false;

	if(!($conn=db_connect()))
	    return false;

	delete_Stock_Transaction_Stock($id);

	$query = "delete from stocks where stock_id = $id";

	if (DEBUG == 1) echo $query.'<br>';

	$result = mysql_query($query);
  	if ( !$result ) {
     	echo 'delete_Stock:SQL error.';
     	return false;
     }

  	return true;
}

function load_Stock($id) {
     if(!$id)
       return false;

     if(!($conn=db_connect()))
       return false;

     $query = "select * from stocks where stock_id = $id";

     if (DEBUG==1) echo $query.'<br>';
     $result = mysql_query($query) or die('SELECT failed: ' . mysql_error());

     if(!$result)
     {
       echo "Cannot retrieve this Stock";
       return false;
     }
     return mysql_fetch_array($result,MYSQL_ASSOC);
}

function insert_Stock($details)
{
  // extract order_details out as variables
  extract($details);

  if(!($conn=db_connect()))
       return false;

  $mdate = date('Y-m-d H:i:s');
  $success = 0;

  $stockname = addslashes($stockname);
  $remarks = addslashes($remarks);

  if (DEBUG == 1) echo 'action:'.$act.'<br>';

  if ($act == "EDIT") {
    $query = "update stocks set stock_name='$stockname', remarks='$remarks',
              pe='$pe',
    		  market_shares='$market_shares',
    		  modifieddate='$mdate'
    		  where stock_id = $id";

	if (DEBUG==1) echo $query.'<br>';
    $msg = "Stock '$stock_name' Record successfully Updated.";
    $result = mysql_query($query, $conn) or die('counter UPDATE error: '.mysql_errno().', '.mysql_error());
    if (!$result) return $id.'@SQL error:UPDATE@0'; else $success = 1;
  }

  if ($act == "SAVE") {

    // Check whether name exists
    $query = "select * from stocks where stock_name = '$stockname'";
	if (DEBUG==1) echo $query.'<br>';
    $result = mysql_query($query, $conn) or die('SELECT error: '.mysql_errno().', '.mysql_error());
    $msg = "";
    $num = mysql_num_rows($result);
    if ($num > 0) {
 	  $msg = "Stock '".$stockname."' exists.";
 	  mysql_free_result($result);
 	  $id = -1;
 	  return $id.'@'.$msg.'@0';
    }

    $query = "insert into stocks(stock_name, remarks, pe, market_shares, modifieddate)
			 values
			 ('$stockname', '$remarks', '$pe', '$market_shares','$mdate')";

    if (DEBUG==1) echo $query.'<br>';
	$result = mysql_query($query, $conn) or die('counter INSERT error: '.mysql_errno().', '.mysql_error());
	if (!$result) return $id.'@SQL error:INSERT@0';
    $id = mysql_insert_id();
    $msg = "Stock '$stockname' successfully Inserted.";
    $success = 1;

  }

  return $id.'@'.$msg.'@'.$success;
}

function getStocks()
{

  $query = "select CONCAT(stock_id ,'@',stock_name) as 'str1' from stocks order by stock_name";

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

function delete_Stock_Transaction($id) {
    if (!$id)
    	return false;

	if(!($conn=db_connect()))
	    return false;

	$query = "delete from stocks_transaction where transaction_id = $id";

	if (DEBUG == 1) echo $query.'<br>';

	$result = mysql_query($query);
  	if ( !$result ) {
     	echo 'delete_Stock Transaction:SQL error.';
     	return false;
     }

  	return true;
}

function delete_Stock_Transaction_Stock($id) {
    if (!$id)
    	return false;

	if(!($conn=db_connect()))
	    return false;

	$query = "delete from stocks_transaction where stock_id = $id";

	if (DEBUG == 1) echo $query.'<br>';

	$result = mysql_query($query);
  	if ( !$result ) {
     	echo 'delete_Stock Transaction:SQL error.';
     	return false;
     }

  	return true;
}


function load_Stock_Transaction($id) {
     if(!$id)
       return false;

     if(!($conn=db_connect()))
       return false;

     $query = "select * from stocks_transaction where transaction_id = $id";

     if (DEBUG==1) echo $query.'<br>';

     $result = mysql_query($query) or die('SELECT failed: ' . mysql_error());

     if(!$result)
     {
       echo "Cannot retrieve this Stock Transaction";
       return false;
     }

     return mysql_fetch_array($result,MYSQL_ASSOC);
}


function insert_Stocks_Transaction($details)
{
  // extract order_details out as variables
  extract($details);

  include("constants.php");
  $conn = db_connect();
  $mdate = date('Y-m-d H:i:s');
  $success = 0;

  if (DEBUG == 1) echo 'action:'.$action.'<br>';

  $remarks = addslashes($remarks);
  $buy_reasons = addslashes($buy_reasons);
  $sell_reasons = addslashes($sell_reasons);

  if ($buy_date)
     $buy_date = date('Y-m-d', strtotime($buy_date));

  if ($sell_date)
  	$sell_date = date('Y-m-d', strtotime($sell_date));

  if ($action == "EDIT") {

    switch($transaction_mode) {

    case "BUY":
    case "SELL":
    case "CONTRA":
    case "CFD_LONG":
    case "CFD_SHORT":

    $query = "update stocks_transaction set stock_id =$stocks, buy_date = '$buy_date',
              buy_price = $buy_price, buy_commission = $buy_commission, buy_clear_fee = $buy_clear_fee,
			  buy_sgx_access_fee = $buy_sgx_access_fee, buy_gst = $buy_gst, buy_total = $buy_total,
			  buy_reasons = '$buy_reasons',
		  	  payment_medium = '$payment_medium', quantity = $quantity, transaction_mode = '$transaction_mode'";

	if ($sell_date!="")
      	$query = $query.", sell_date = '$sell_date', sell_price = $sell_price, sell_commission = $sell_commission,
      	                   sell_clear_fee = $sell_clear_fee, sell_sgx_access_fee = $sell_sgx_access_fee,
      	                   sell_gst = $sell_gst, sell_total = $sell_total, sell_reasons = '$sell_reasons',
      	                   profit = $profit, percentage = $percentage, status = '$status'";

    $query = $query.", remarks = '$remarks', modifieddate= '$mdate'";

    $query = $query." where transaction_id = $id";

    break;

    case "DIVIDEND":

	$query = "update stocks_transaction set stock_id =$stocks, buy_date = '$buy_date',
			  buy_price = $buy_price, buy_commission = $buy_commission, buy_clear_fee = $buy_clear_fee,
			  buy_sgx_access_fee = $buy_sgx_access_fee, buy_gst = $buy_gst, buy_total = $buy_total, buy_reasons = '$buy_reasons',
		  	  payment_medium = '$payment_medium', quantity = $quantity, transaction_mode = '$transaction_mode',
		  	  profit = $profit, percentage = $percentage, status = '$status', remarks = '$remarks', modifieddate= '$mdate'
		  	  where transaction_id = $id";

    break;

    }

    $msg = 'Record successfully Updated.';

    if (DEBUG==1) echo $query.'<br>';

    $result = mysql_query($query, $conn) or die('counter UPDATE error: '.mysql_errno().', '.mysql_error());
	if (!$result) return $id.'@SQL error:UPDATE@0';
        $msg = "Stock Transaction '$id' successfully Updated.";

    $success = 1;

  }

  if ($action == "SAVE") {

  	switch($transaction_mode) {
      case "BUY":
      case "SELL":
      case "CONTRA":
      case "CFD_LONG":
      case "CFD_SHORT":

      $query = "insert into stocks_transaction(UserID, stock_id, buy_date, buy_price,
                buy_commission, buy_clear_fee, buy_sgx_access_fee, buy_gst, buy_total, buy_reasons,
                payment_medium, quantity, transaction_mode, status";

      if ($sell_date!="")
      	$query = $query.", sell_date, sell_price, sell_commission, sell_clear_fee,
      	                   sell_sgx_access_fee, sell_gst, sell_total, sell_reasons, profit, percentage";

      $query = $query.", remarks, modifieddate)";

      $query = $query." VALUES($UserID, $stocks, '$buy_date', $buy_price,
               $buy_commission, $buy_clear_fee, $buy_sgx_access_fee, $buy_gst, $buy_total, '$buy_reasons',
               '$payment_medium', $quantity, '$transaction_mode', '$status'";

      if ($sell_date!="")
      	$query = $query.", '$sell_date', $sell_price, $sell_commission, $sell_clear_fee,
      	                  $sell_sgx_access_fee, $sell_gst, $sell_total, '$sell_reasons', $profit, $percentage";

      $query = $query.", '$remarks', '$mdate')";

      break;

      case "DIVIDEND":

      $query = "insert into stocks_transaction(UserID, stock_id, buy_date,
               buy_price, buy_commission, buy_clear_fee, buy_sgx_access_fee, buy_gst, buy_total,
               payment_medium, quantity, dividend_rate,
               profit, transaction_mode, percentage, status, remarks, modifieddate)
               VALUES($UserID, $stocks,'$buy_date', $buy_price,
               $buy_commission, $buy_clear_fee, $buy_sgx_access_fee, $buy_gst, $buy_total,
               '$payment_medium', $quantity, $dividend_rate,
               $profit, '$transaction_mode', $percentage, '$status', '$remarks', '$mdate')";

      break;

      }

      if (DEBUG==1) echo $query.'<br>';

	  $result = mysql_query($query, $conn) or die('counter INSERT error: '.mysql_errno().', '.mysql_error());
	  $id = mysql_insert_id();
	  if (!$result) return $id.'@SQL error:INSERT@0';
        $msg = "Stock Transaction #'$id' successfully Inserted.";

      $success = 1;
  }

  return $id.'@'.$msg.'@'.$success;
}

?>
