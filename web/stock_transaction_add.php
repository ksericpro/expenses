<?php
	require_once('user_auth_fns.php');
	check_valid_user();
//include('db.php');
include('include_fns.php');

?>
<?php require('header.inc')?>
<?php
      $msg = '<br>';
      $date = $_REQUEST['date'];

      //$date = '01/24/2006';
      $date = date('m/d/Y', strtotime($date));
      $year = date('Y', strtotime($date));
      $month = date('m', strtotime($date));

	  $CURRENT_ACTION = "SAVE";
      $act = $_REQUEST['action'];
      $userid = $_SESSION['userid'];
      $id = $_REQUEST['id'];
      $FEE = get_Fees();


      switch ($act) {
         case 'EDIT':
         case 'SAVE':
   			  $res = insert_Stocks_Transaction($_POST);
     	      $res_array = explode('@', $res);
		      reset($res_array);
			  $ct = 0;
		  	  foreach ($res_array as $current) {
		    	 switch($ct) {
		    	    case 0: $id = $current; break;
		    	    case 1: $msg = $current; break;
		    	    case 2: $success = $current; break;
		    	 }
		    	 $ct++;
		  	  }

		  	  $act = "LOAD";

              break;

       case 'DELETE':
	 		 if (delete_Stock_Transaction($id))
      			$msg = "Stock Transaction record #'".$id."' Successfully Deleted.";
      		 $act = "SAVE";
      		 $CURRENT_ACTION = "SAVE";

              break;
       case 'LOAD':
       		  $msg = "Stock Transaction record #'$id' successfully loaded.";
              break;
      }


      if ($act=="LOAD") {
      		include('constants.php');
	 	    $transaction = load_Stock_Transaction($id);
	 	    $sell_date = $transaction['sell_date'];

	 	    if ($sell_date!="")
	 	    	$sell_date = date('m/d/Y', strtotime($sell_date));
		    $CURRENT_ACTION = "EDIT";
      }

?>
<html>
<head><title>Stocks - Transactions</title></head>
<link rel="stylesheet" href="style.css">
<SCRIPT language="javascript" src="calendar2.js"></SCRIPT>
<SCRIPT language="JavaScript" src="functions.js"></SCRIPT>
<script language="javascript">
<!--
   ////////////////////
   // Validation
   ////////////////////

   var COMMISSION_FEE;
   var CLEAR_FEE;
   var SGX_ACCESS_FEE;
   var GST;
   var DECIMAL_PT;
   var COMMISSION_MIN;

   function check() {

      if (document.frm.stocks.value=="") {
		  alert("Stocks cannot be empty!");
		  return false;
      }
      if (document.frm.payment_medium.value=="") {
		  alert("Payment Medium cannot be empty!");
		  return false;
      }

      mode = get_Mode();
 	  switch(mode) {
         case "DIVIDEND":
         	if (!check_Empty_Numeric("dividend_rate", "Dividend Rate"))
              return false;
         case "BUY":
            if (!check_Empty_Numeric("buy_price", "Buy Price"))
              return false;
         break;
		 case "SELL":
			if (!check_Empty_Numeric("sell_price", "Sell Price"))
			   return false;
		   if (document.frm.sell_date.value=="") {
			  alert("Sell Date cannot be empty!");
			  return false;
            }
         break;
      }

      if (!check_Empty_Numeric("quantity", "Quantity"))
        return false;

      if (!(document.frm.skip_calc.checked)) calculate_details();

      return true;
   }

   function get_Mode() {

       for (var i=0; i<document.frm.transaction_mode.length;i++)
       if (document.frm.transaction_mode[i].checked) {
          mode = document.frm.transaction_mode[i].value;
          break;
       }

       return mode;
   }

   function clear_Fields(x) {

     if (x == "BUY") {
		document.frm.buy_commission.value = "";
		document.frm.buy_clear_fee.value = "";
		document.frm.buy_sgx_access_fee.value = "";
		document.frm.buy_gst.value = "";
        document.frm.buy_total.value = "";
     }

     if (x == "SELL") {
       	document.frm.sell_commission.value = "";
       	document.frm.sell_clear_fee.value = "";
       	document.frm.sell_sgx_access_fee.value = "";
       	document.frm.sell_gst.value = "";
       	document.frm.sell_total.value = "";
     }

     if (x=="ALL") {
		document.frm.buy_commission.value = "";
		document.frm.buy_clear_fee.value = "";
		document.frm.buy_sgx_access_fee.value = "";
		document.frm.buy_gst.value = "";
        document.frm.buy_total.value = "";
       	document.frm.sell_commission.value = "";
       	document.frm.sell_clear_fee.value = "";
       	document.frm.sell_sgx_access_fee.value = "";
       	document.frm.sell_gst.value = "";
       	document.frm.sell_total.value = "";
     }

   }

   function roundNumber(num, dec) {
   	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
   	return result;
   }

   function calculate_sub(x) {

     COMMISSION_FEE = parseFloat(document.frm.commission.value);
     onetime = false;
     buy_date = document.frm.buy_date.value;
     sell_date = document.frm.sell_date.value;
     mode = get_Mode();
     if ( (buy_date==sell_date) && ( (mode == "CFD_LONG") || (mode == "CFD_SHORT")) )  onetime=true;

     if (x=="BUY") {
			buy_amount = parseFloat(document.frm.buy_price.value * document.frm.quantity.value);
			commission = COMMISSION_FEE*buy_amount/100;
			if (commission<COMMISSION_MIN) commission = COMMISSION_MIN;

			if ( (mode == "CFD_LONG") || (mode == "CFD_SHORT") ) {
			    clear_fee = 0.0;
			    sgx_access_fee = 0.0;
			} else {
				clear_fee = CLEAR_FEE*buy_amount/100;
				sgx_access_fee = SGX_ACCESS_FEE*buy_amount/100;
			}

			gst = GST*(commission+clear_fee+sgx_access_fee)/100;
			buy_total = buy_amount + commission + clear_fee + sgx_access_fee + gst;

			document.frm.buy_commission.value = roundNumber(commission, DECIMAL_PT);
			document.frm.buy_clear_fee.value = roundNumber(clear_fee, DECIMAL_PT);
			document.frm.buy_sgx_access_fee.value = roundNumber(sgx_access_fee, DECIMAL_PT);
			document.frm.buy_gst.value = roundNumber(gst,DECIMAL_PT);
       		document.frm.buy_total.value = roundNumber(buy_total,DECIMAL_PT);
     }

     if (x=="SELL") {
            sell_amount = parseFloat(document.frm.sell_price.value * document.frm.quantity.value);
            if (onetime==false) {
	 	        commission = COMMISSION_FEE*sell_amount/100;
	 	        if (commission<COMMISSION_MIN) commission = COMMISSION_MIN;
			   	clear_fee = CLEAR_FEE*sell_amount/100;
			    sgx_access_fee = SGX_ACCESS_FEE*sell_amount/100;
			    }
	 	    else {
	 	        commission = 0.0;
	 	        clear_fee = 0.0;
			    sgx_access_fee = 0.0;
	 	     }


			//alert(roundNumber(sgx_access_fee,2));
			gst = GST*(commission+clear_fee+sgx_access_fee)/100;
			sell_total = sell_amount - commission - clear_fee - sgx_access_fee - gst;

			document.frm.sell_commission.value = roundNumber(commission, DECIMAL_PT);
			document.frm.sell_clear_fee.value = roundNumber(clear_fee, DECIMAL_PT);
			document.frm.sell_sgx_access_fee.value = roundNumber(sgx_access_fee, DECIMAL_PT);
			document.frm.sell_gst.value = roundNumber(gst,DECIMAL_PT);
		    document.frm.sell_total.value = roundNumber(sell_total,DECIMAL_PT);
     }

     if (x=="PROFIT") {
      		mode = get_Mode();

       		if (mode == "DIVIDEND") {
       		    buy_total = parseFloat(document.frm.buy_total.value);
				profit = parseFloat(document.frm.dividend_rate.value * document.frm.quantity.value);
       	        document.frm.profit.value = profit.toFixed(DECIMAL_PT);
			    percentage = profit / buy_total*100;
			    document.frm.percentage.value = percentage.toFixed(DECIMAL_PT);
       		}

       	    else {
       	       buy_total = parseFloat(document.frm.buy_total.value);
			   sell_total = parseFloat(document.frm.sell_total.value);
			   profit = (sell_total - buy_total).toFixed(DECIMAL_PT);
			   document.frm.profit.value = profit;
			   percentage = profit/buy_total*100;
        	   document.frm.percentage.value = percentage.toFixed(DECIMAL_PT);
       	    }

     }

   }


   function calculate_details() {

        if (!check_Empty_Numeric("quantity", "Quantity"))
           return false;


 		mode = get_Mode();

       	if (mode == "DIVIDEND") {

       	     if (!check_Empty_Numeric("dividend_rate", "Dividend Rate"))
             return false;

   			 if (!check_Empty_Numeric("buy_price", "Buy Price"))
               return false;

       	     clear_Fields("ALL");
       	     calculate_sub("BUY");
       	     calculate_sub("PROFIT");

       	}
       	else {
       	    if (document.frm.buy_price.value!="") calculate_sub("BUY");

       		if (document.frm.sell_price.value!="") calculate_sub("SELL");

            if ( (document.frm.sell_total.value !="") && ( document.frm.buy_total.value !="") ) calculate_sub("PROFIT");

       }
  }

   function check_Empty_Numeric(x, msg) {

      var y = eval("document.frm." +x);
      if (y.value=="") {
          alert(msg + " cannot be empty!");
          y.focus();
          return false;
      } else if (isNaN(y.value)) {
		  alert(msg + " is not a number");
		  y.focus();
 		  return false;
      }
      return true;
   }
   /////////////////
   // Initialise
   /////////////////
   var cal1 = null;
   var cal2 = null;

   function init() {

   	 setDefault("document.frm.d_stocks", "document.frm.stocks");
   	 setDefault("document.frm.d_payment_medium", "document.frm.payment_medium");
   	 setDefault("document.frm.d_quantity", "document.frm.quantity");
   	 setDefault("document.frm.d_status", "document.frm.status");
   	 setDefault2("document.frm.d_transaction_mode", "document.frm.transaction_mode");

 	 cal1 = new calendar2(document.forms['frm'].elements['buy_date']);
 	 cal1.year_scroll = true;
   	 cal2 = new calendar2(document.forms['frm'].elements['sell_date']);
 	 cal2.year_scroll = true;

 	 COMMISSION_FEE = parseFloat(document.frm.COMMISSION_FEE.value);
 	 CLEAR_FEE = parseFloat(document.frm.CLEAR_FEE.value);
 	 SGX_ACCESS_FEE = parseFloat(document.frm.SGX_ACCESS_FEE.value);
 	 GST = parseFloat(document.frm.GST.value);
 	 DECIMAL_PT = parseInt(document.frm.DECIMAL_PT.value);
	 COMMISSION_MIN = parseFloat(document.frm.COMMISSION_MIN.value);

   }

   function change_Mode() {

  	 mode = get_Mode();
     switch(mode) {
         case "DIVIDEND" :
     	   document.frm.dividend_rate.disabled = 0;
     	   document.frm.sell_price.disabled = 1;
     	   document.frm.sell_reasons.disabled = 1;
     	   document.frm.buy_reasons.disabled = 0;
     	   document.frm.buy_price.disabled = 0;
     	   //document.frm.sell_price.value="";
     	   document.frm.payment_medium.disabled = 0;
         break;

         case "BUY" :
     	   document.frm.dividend_rate.disabled = 1;
     	   document.frm.buy_price.disabled = 0;
     	   document.frm.buy_reasons.disabled = 0;
     	   document.frm.sell_price.disabled = 1;
     	   document.frm.sell_reasons.disabled = 1;
     	   document.frm.dividend_rate.value="";
     	   document.frm.payment_medium.disabled = 0;
         break;

         case "SELL" :
     	   document.frm.dividend_rate.disabled = 1;
     	   document.frm.buy_price.disabled = 1;
     	   document.frm.buy_reasons.disabled = 1;
     	   document.frm.sell_price.disabled = 0;
     	   document.frm.sell_reasons.disabled = 0;
     	   document.frm.dividend_rate.value="";
     	   document.frm.payment_medium.disabled = 0;
         break;

         case "CONTRA" :
     	   document.frm.dividend_rate.disabled = 1;
     	   document.frm.dividend_rate.value="";
     	   document.frm.buy_price.disabled = 0;
     	   document.frm.buy_reasons.disabled = 0;
     	   document.frm.sell_price.disabled = 0;
     	   document.frm.sell_reasons.disabled = 0;
     	   document.frm.payment_medium.disabled = 0;
         break;

         case "CFD_SHORT":
         case "CFD_LONG":
		    document.frm.payment_medium[1].selected = 1;
		    document.frm.payment_medium.disabled = 1;
		    document.frm.buy_price.disabled = 0;
		    document.frm.buy_reasons.disabled = 0;
		    document.frm.sell_price.disabled = 0;
     	    document.frm.sell_reasons.disabled = 0;

         break;

         default:
        	document.frm.dividend_rate.disabled = 1;
            document.frm.dividend_rate.value="";
         break;
     }

     }

  function process() {

    if (check()) {
  		document.frm.buy_price.disabled = 0;
  		document.frm.sell_price.disabled = 0;
  		document.frm.dividend_rate.disabled = 0;
  		document.frm.sell_reasons.disabled = 0;
  		document.frm.buy_reasons.disabled = 0;
  		document.frm.submit();
     }
  }
-->
</script>
<body onload="init();change_Mode();">

	<form name="frm" method="post" action="">

<table width="96%" border="1" align="center" cellpadding="5" cellspacing="0" bgcolor="#B0C4DE" style="border-collapse:collapse">
<tr bgcolor="#52637B"><td>
<div align="center"><strong><font color="#FFCC00" size="3">STOCKS Add</font></strong></div>
  </td></tr>
  <tr>
    <td>
<div align="right" style="margin-top:5pt;margin-bottom:5pt"><A href="javascript:this.self.close();opener.window.location.reload();" title="Close" class="s1">&gt;Close&lt;</A>
      </div>

        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan=2><div align="left" style="margin-bottom:2pt"><font color="#006699"><?php echo $msg?></font></div></td>
          </tr>
          <tr>
            <td width="34%">User ID</td>
            <td width="66%">&nbsp;&nbsp;<input type="text" name="UserID" size="5" class=s1 readonly="1" value="<?php echo $userid?>"></td>
          </tr>
          <tr>
            <td valign=top>Buy Date</td>
            <td>&nbsp;&nbsp;<input type="text" name="buy_date" size="15" class="s1" readonly="1" value="<?php echo $date?>">
              &nbsp;<a href="javascript:cal1.popup();" class="s2" title="Pick date from Calendar"><img src="images/cal.gif" alt="Click Here to Pick up the date" width="16" height="16" border="0" align="middle"></a>&nbsp;<font color="#336699">(mm/dd/yyyy)</font></td>
          </tr>
          <tr>
            <td valign=top>Sell Date</td>
            <td valign="top">&nbsp;
			<input type="text" name="sell_date" size="15" class="s1" value="<?php echo $sell_date?>">
              &nbsp;<a href="javascript:cal2.popup();" class="s2" title="Pick date from Calendar"><img src="images/cal.gif" alt="Click Here to Pick up the date" width="16" height="16" border="0" align="middle"></a>&nbsp;<a onclick="javascript:document.frm.sell_date.value='';" class=s1>CLR</a></td>
          </tr>
          <tr>
            <td valign=top>Stocks</td>
            <td valign="top">&nbsp;&nbsp;<select name="stocks">
                <option value=""></option>
                <?php
                   $tstr = getStocks();
				  $arr = explode(':', $tstr);
				 // echo "<option>".$tstr."</option>";
				  reset($arr);
				  foreach ($arr as $current) {
					$sep_pos = strpos($current, '@');
					$cid = substr($current, 0, $sep_pos);

					echo '<option value="'.$cid.
						 '">'.substr($current, $sep_pos+1).'</option>';
				   }
                ?></select></td>
          </tr>
          <tr>
            <td valign=top>Transaction Mode</td>
            <td valign="top"><input type="radio" value="BUY" checked name="transaction_mode" onclick="change_Mode()">Buy&nbsp;
            <input type="radio" value="SELL" name="transaction_mode" onclick="change_Mode()">Sell
            &nbsp;<input type="radio" value="DIVIDEND" name="transaction_mode" onclick="change_Mode()">Dividend&nbsp;
            <input type="radio" value="CONTRA" name="transaction_mode" onclick="change_Mode()">Contra
            &nbsp;<input type="radio" value="CFD_SHORT" name="transaction_mode" onclick="change_Mode()">CFD Short
            &nbsp;<input type="radio" value="CFD_LONG" name="transaction_mode" onclick="change_Mode()">CFD Long</td>
          </tr>

          <tr>
            <td valign=top>Payment Medium</td>
            <td valign="top">&nbsp;&nbsp;<select name="payment_medium">
                <option value=""></option>
             <option value="CASH">CASH</option>
             <option value="CPF">CPF</option>

             </select></td>
          </tr>

          <tr>
		      <td valign=top>Commission</td>
				  <td valign="top">&nbsp;&nbsp;<select name="commission">
					  <!--<option value="0.1">0.1</option>-->
				     <option value="0.285">0.285</option>
				     <!--<option value="0.300">0.300</option>-->

				   </select>

				   <i>
			<font color="#336699">%</font></i></td>
				</tr>


          <tr>
            <td valign=top>Buy Price</td>
            <td><font color="#336699">$</font><input type="text" name="buy_price" size="20" value="<?php echo $transaction['buy_price']?>"></td>
          </tr>

           <tr>
             <td valign=top>Buy Reasons</td>
             <td valign="top">&nbsp;&nbsp;<textarea name="buy_reasons" cols="60" rows="1" class="s2"><?php echo $transaction['buy_reasons']?></textarea></td>
          </tr>

          <tr>
            <td valign=top>Sell Price</td>
            <td><font color="#336699">$</font><input type="text" name="sell_price" size="20" value="<?php echo $transaction['sell_price']?>"></td>
          </tr>

            <tr>
              <td valign=top>Sell Reasons</td>
              <td valign="top">&nbsp;&nbsp;<textarea name="sell_reasons" cols="60" rows="1" class="s2"><?php echo $transaction['sell_reasons']?></textarea></td>
          </tr>
          <tr>
            <td valign=top>Dividend Rate</td>
            <td>&nbsp;&nbsp;<input type="text" name="dividend_rate" size="10" value="<?php echo $transaction['dividend_rate']?>" disabled=1>
			<i>
			<font color="#336699">per 1 share</font></i></td>
          </tr>
          <tr>
            <td valign=top>Quantity</td>
            <td>&nbsp;

            <select name="quantity">
            	<option value=""></option>
            	<?php
            	  for ($i=1;$i<=60;$i++)
            	  	echo '<option>'.($i*1000).'</option>';
            	?>

            </select>
            <i><font color="#336699">shares</font></i>
            </td>
          </tr>
          <tr>
            <td valign=top>Status</td>
            <td>&nbsp;&nbsp;<select name="status">
             <option value="NEW" checked>NEW</option>
             <option value="CLOSED">CLOSED</option>

             </select></td>
          </tr>
          <tr>
            <td valign=top>&nbsp;</td>
            <td>
                &nbsp;</td>
          </tr>
          <tr>
            <td valign=top colspan="2" bgcolor="#000000"><div style="width:2px; height:2px;"><spacer type="block" width=2 height=2></div></td>
          </tr>
          <tr>
            <td valign=top><input type="checkbox" name="skip_calc"> Manual Entry</td>
            <td>
                <input type="button" name="calculate" value="Please work out for me!" onclick="javascript:calculate_details();"></td>
          </tr>
          <tr>
            <td valign=top>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>

          <tr>
            <td valign=top>Buy Commission <font color="#336699">(<?php echo $FEE['commission']?>%)</font></td>
            <td><font color="#336699">$</font><input type="text" name="buy_commission" size="20" value="<?php echo $transaction['buy_commission']?>"></td>
          </tr>

          <tr>
            <td valign=top>Buy Clear Fee <font color="#336699">(<?php echo $FEE['clear_fee']?>%)</font></td>
            <td><font color="#336699">$</font><input type="text" name="buy_clear_fee" size="20" value="<?php echo $transaction['buy_clear_fee']?>"></td>
          </tr>

          <tr>
            <td valign=top>Buy SGX Access Fee <font color="#336699">(<?php echo $FEE['sgx_access_fee']?>%)</font></td>
            <td><font color="#336699">$</font><input type="text" name="buy_sgx_access_fee" size="20" value="<?php echo $transaction['buy_sgx_access_fee']?>"></td>
          </tr>

          <tr>
            <td valign=top>Buy GST <font color="#336699">(<?php echo $FEE['gst']?>%)</font></td>
            <td><font color="#336699">$</font><input type="text" name="buy_gst" size="20" value="<?php echo $transaction['buy_gst']?>"></td>
          </tr>

          <tr>
            <td valign=top><b>Paid Amount</b></td>
            <td><font color="#336699">$</font><input type="text" name="buy_total" size="20" value="<?php echo $transaction['buy_total']?>"></td>
          </tr>


          <tr>
            <td valign=top>Sell Commission <font color="#336699">(<?php echo $FEE['commission']?>%)</font></td>
            <td><font color="#336699">$</font><input type="text" name="sell_commission" size="20" value="<?php echo $transaction['sell_commission']?>"></td>
          </tr>

          <tr>
            <td valign=top>Sell Clear Fee <font color="#336699">(<?php echo $FEE['clear_fee']?>%)</font></td>
            <td><font color="#336699">$</font><input type="text" name="sell_clear_fee" size="20" value="<?php echo $transaction['sell_clear_fee']?>"></td>
          </tr>
			<tr>
            <td valign=top>Sell SGX Access Fee <font color="#336699">(<?php echo $FEE['sgx_access_fee']?>%)</font></td>
            <td><font color="#336699">$</font><input type="text" name="sell_sgx_access_fee" size="20" value="<?php echo $transaction['sell_sgx_access_fee']?>"></td>
          </tr>
			<tr>
            <td valign=top>Sell GST <font color="#336699">(<?php echo $FEE['gst']?>%)</font></td>
            <td><font color="#336699">$</font><input type="text" name="sell_gst" size="20" value="<?php echo $transaction['sell_gst']?>"></td>
          </tr>

          <tr>
            <td valign=top><b>Sold Amount</b></td>
            <td><font color="#336699">$</font><input type="text" name="sell_total" size="20" value="<?php echo $transaction['sell_total']?>"></td>
          </tr>

          <tr>
            <td valign=top><b>Profit/Loss</b></td>
            <td><font color="#336699">$</font><input type="text" name="profit" size="20" value="<?php echo $transaction['profit']?>"></td>
          </tr>
          <tr>
            <td valign=top><b>Percentage</b></td>
            <td>&nbsp;&nbsp;<input type="text" name="percentage" size="20" value="<?php echo $transaction['percentage']?>"> %</td>
          </tr>
          <tr>
            <td valign=top>Remarks</td>
            <td valign="top">&nbsp;&nbsp;<textarea name="remarks" cols="40" rows="1" class="s2"><?php echo $transaction['remarks']?></textarea></td>
          </tr>
          <tr>
            <td colspan="2"> <div align="center">
                <input type="button" name="Save" value="Save" onclick="process();">
                <input type="reset" name="Reset" value="Reset" onclick="javascript:document.frm.submit();">
                <input type="button" name="New" value="New" onclick="document.frm.action.value='';document.frm.submit();">
                <input type="hidden" name="action" value="<?php echo $CURRENT_ACTION?>">
                <input type="hidden" name="id" value="<?php echo $id?>">
                <input type="hidden" name="d_stocks" value="<?php echo $transaction['stock_id']?>">
                <input type="hidden" name="d_quantity" value="<?php echo $transaction['quantity']?>">
                <input type="hidden" name="d_status" value="<?php echo $transaction['status']?>">
                <input type="hidden" name="d_payment_medium" value="<?php echo $transaction['payment_medium']?>">
                <input type="hidden" name="d_transaction_mode" value="<?php echo $transaction['transaction_mode']?>">
                <input type="hidden" name="COMMISSION_FEE" value="<?php echo $FEE['commission']?>">
				<input type="hidden" name="CLEAR_FEE" value="<?php echo $FEE['clear_fee']?>">
				<input type="hidden" name="SGX_ACCESS_FEE" value="<?php echo $FEE['sgx_access_fee']?>">
				<input type="hidden" name="GST" value="<?php echo $FEE['gst']?>">
				<input type="hidden" name="DECIMAL_PT" value="<?php echo $FEE['decimal_pt']?>">
				<input type="hidden" name="COMMISSION_MIN" value="<?php echo $FEE['commission_min']?>">
              </div></td>
          </tr>
          <tr>
		   <td colspan=2>&nbsp;</td>
          </tr>
          <tr>
            <td colspan=2 bgcolor="#000000"><div style="width:2px; height:2px;"><spacer type="block" width=2 height=2></div></td>
          </tr>

        <tr>
            <td colspan="2">
			<table width="100%"><tr><td width="69%"><u>Today Position(s)</u></td>
			<td width="31%" align=right>&nbsp;</td>
			</tr></table>


			<table width="100%" border="1" cellspacing="0" cellpadding="0" style="border-collapse:collapse" bordercolor="#bbbbbb">
                <tr bgcolor="#666666">
                  <td>
				    <div align="center"><font color="#6699CC">#</font></div></td>
                  <td>
                    <div align="center"><font color="#6699CC">Stock Name</font></div></td>
                  <td>
                    <p align="center"><font color="#6699CC">Mode</font></td>
                  <td>
                    <div align="center"><font color="#6699CC">XD rate</font></div></td>
                  <td>
                    <p align="center"><font color="#6699CC">Quantity</font></td>
                  <td>
                    <p align="center"><font color="#6699CC">Medium</font></td>
                  <td>
                    <p align="center"><font color="#6699CC">Paid $</font></td>
                  <td>
                    <p align="center"><font color="#6699CC">Sold $</font></td>
                  <td>
                    <p align="center"><font color="#6699CC">Profit</font></td>
                  <td>
                    <div align="center"><font color="#6699CC">Last Modified</font></div></td>
                  <td>
                    <div align="center"><font color="#6699CC">A1</font></div></td>
                  <td>
                    <div align="center"><font color="#6699CC">A2</font></div></td>
                </tr>

                <?php


                 $x = date('Y-m-d', strtotime($date));

                 $sql = "select st.*, s.* from stocks_transaction st, stocks s where
                         s.stock_id = st.stock_id
                         AND st.buy_date = '$x'";

                 //echo '<tr></td>'.$sql.'</td></tr>';
		   		 if (!$conn) $conn=db_connect();
		   		 $result = mysql_query($sql);
		   		 if (!$result)
					echo '<tr></td>SQL Error</td></tr>';

				 while ($row = mysql_fetch_assoc($result)) {
				        $tx_id = $row['transaction_id'];
						$stock_id = $row['stock_id'];
						$stock_name = $row['stock_name'];
						$mdate = $row['modifieddate'];
						$buy_date = $row['buy_date'];
						$profit = $row['profit'];
						if ($profit=="")
						$profit = $row['dividend_rate']*$row['quantity'];

                ?>

                <tr bgcolor="#FFFFCC">
                  <td><div class="t1"><?php echo $tx_id?></div></td>
                  <td><div class="t1"><?php echo $stock_name?></div></td>
                  <td><div class="t1"><?php echo $row['transaction_mode']?></div></td>
                  <td><div class="t1"><?php echo $row['dividend_rate']?></div></td>
                  <td><div class="t1"><?php echo $row['quantity']?></div></td>
                  <td><div class="t1"><?php echo $row['payment_medium']?></div></td>
                  <td><div class="t1"><?php echo $row['buy_total']?></div></td>
                  <td><div class="t1"><?php echo $row['sell_total']?></div></td>
                  <td><div class="t1"><?php echo $profit?></div></td>
                  <td><div class="t1"><?php echo $mdate?></div></td>
                  <td><div align="center"><a href="?action=LOAD&id=<?php echo $tx_id?>&date=<?php echo $buy_date?>" title="edit" class="s1">Edit</a></div></td>
                  <td><div align="center">
                  <a class='s1' title='Delete Record' href='javascript:ConfirmDeleteB("?", "<?php echo $tx_id?>", "<?php echo $date?>");' >delete</a></div></td>
                </tr>

                <?php

                	}

        		mysql_free_result($result);

                ?>

              </table>
			</td>
          </tr>


          <tr>
            <td colspan="2">
			<table width="100%" cellpadding=0 cellspacing=0><tr><td width="69%">&nbsp;</td>
			<td width="31%" align=right>&nbsp;</td>
			</tr>
			<tr><td colspan=2 bgcolor="#000000"><div style="width:2px; height:2px;"><spacer type="block" width=2 height=2></div></td></tr>

			<tr><td width="69%"><u>Outstanding Position(s)</u></td>
			<td width="31%" align=right>&nbsp;</td>
			</tr></table>

			<table width="100%" border="1" cellspacing="0" cellpadding="0" style="border-collapse:collapse" bordercolor="#bbbbbb">
           <tr bgcolor="#666666">
                  <td>
				    <div align="center"><font color="#6699CC">#</font></div></td>
                  <td>
                    <div align="center"><font color="#6699CC">Stock Name</font></div></td>
                  <td>
                    <p align="center"><font color="#6699CC">Mode</font></td>
                  <td>
                    <div align="center"><font color="#6699CC">XD rate</font></div></td>
                  <td>
                    <p align="center"><font color="#6699CC">Quantity</font></td>
                  <td>
                    <p align="center"><font color="#6699CC">Medium</font></td>
                  <td>
                    <p align="center"><font color="#6699CC">Paid $</font></td>
                  <td>
                    <p align="center"><font color="#6699CC">Sold $</font></td>
                  <td>
                    <p align="center"><font color="#6699CC">Profit</font></td>
                  <td>
                    <div align="center"><font color="#6699CC">Last Modified</font></div></td>
                  <td>
                    <div align="center"><font color="#6699CC">A1</font></div></td>
                  <td>
                    <div align="center"><font color="#6699CC">A2</font></div></td>
                </tr>

                <?php

                 $sql = "select st.*, s.* from stocks_transaction st, stocks s where
                         s.stock_id = st.stock_id
                         AND year(st.buy_date) = $year
                         AND month(st.buy_date) = $month
                         AND st.status = 'NEW'

                         order by st.buy_date";

                 //echo '<tr></td>'.$sql.'</td></tr>';
		   		 if (!$conn) $conn=db_connect();
		   		 $result = mysql_query($sql);
		   		 if (!$result)
					echo '<tr></td>SQL Error</td></tr>';

				 while ($row = mysql_fetch_assoc($result)) {
				        $tx_id = $row['transaction_id'];
						$stock_id = $row['stock_id'];
						$stock_name = $row['stock_name'];
						$mdate = $row['modifieddate'];
						$buy_date = $row['buy_date'];
						$profit = $row['profit'];
						if ($profit=="")
						$profit = $row['dividend_rate']*$row['quantity'];
                ?>

               <tr bgcolor="#FFFFCC">
                  <td><div class="t1"><?php echo $tx_id?></div></td>
                  <td><div class="t1"><?php echo $stock_name?></div></td>
                  <td><div class="t1"><?php echo $row['transaction_mode']?></div></td>
                  <td><div class="t1"><?php echo $row['dividend_rate']?></div></td>
                  <td><div class="t1"><?php echo $row['quantity']?></div></td>
                  <td><div class="t1"><?php echo $row['payment_medium']?></div></td>
                  <td><div class="t1"><?php echo $row['buy_total']?></div></td>
                  <td><div class="t1"><?php echo $row['sell_total']?></div></td>
                  <td><div class="t1"><?php echo $profit?></div></td>
                  <td><div class="t1"><?php echo $mdate?></div></td>
                  <td><div align="center"><a href="?action=LOAD&id=<?php echo $tx_id?>&date=<?php echo $buy_date?>" title="edit" class="s1">Edit</a></div></td>
                  <td><div align="center">
                  <a class='s1' title='Delete Record' href='javascript:ConfirmDeleteB("?", "<?php echo $tx_id?>", "<?php echo $date?>");' >delete</a></div></td>
                </tr>

                <?php

                	}

        		mysql_free_result($result);

                ?>

              </table>
			</td>
          </tr>


          <tr>
		        <td colspan=2><div style="color:#993366;font-size:7pt;"><i><?php echo $i.' record(s).'?></i></div></td>
          </tr>
        </table>



	  </form>

</td>
  </tr>
</table>

</form>


<?php
	require('footer.inc');
  ?>
</body>
</html>