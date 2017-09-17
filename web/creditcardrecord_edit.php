<?php
	require_once('user_auth_fns.php');
	check_valid_user();

	include('include_fns.php');
?>
<?php require('header.inc')?>
<?php
      $msg = '';

	  $reload = 0;
      $id = $_REQUEST['id'];
      $act = $_REQUEST['action'];
      $CURRENT_ACTION = "SAVE";
	  switch ($act) {

         case 'EDIT':
         case 'SAVE':
   			  $res = insert_Creditcard_Transaction($_POST);
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
	 		 if (delete_Creditcard_Transaction($id))
      			$msg = "Credit Card Transaction record #'".$id."' Successfully Deleted.";
      		 $act = "SAVE";
      		 $CURRENT_ACTION = "SAVE";

              break;

  	  	case 'LOAD':
       		  $msg = "Credit Card Transaction record #'$id' successfully loaded.";
              break;
      }

      if ($act=="LOAD") {
      		include('constants.php');
			//echo "id".$id;
	 	    $transaction = load_Creditcard_Transaction($id);
	 	    $txdate = $transaction['transaction_date'];
			$txAmount = $transaction['amount'];
			$purpose = $transaction['purpose'];
			$creditcardid = $transaction['visa_id'];
			$status = $transaction['status'];
			$mdate = $transaction['lastmodifieddate'];
	 	    if ($txdate!="")
	 	    	$txdate = date('m/d/Y', strtotime($txdate));
		    $CURRENT_ACTION = "EDIT";
      }

?>
<html>
<head><title>Expenses - Credit Card</title></head>
<link rel="stylesheet" href="style.css">
<SCRIPT language="javascript" src="calendar2.js"></SCRIPT>
<SCRIPT language="JavaScript" src="functions.js"></SCRIPT>
<script language="javascript">
<!--
   function check() {
      if (document.frm.creditcard.value=="") {
          alert("Credit Card cannot be empty!");
          return false;
      }
	  if (document.frm.txDate.value=="") {
          alert("Transaction Date cannot be empty!");
		  document.frm.txDate.focus();
          return false;
      }
	  if (document.frm.txAmount.value=="") {
          alert("Amount $ cannot be empty!");
		  document.frm.txAmount.focus();
          return false;
      } else
	      if (isNaN(document.frm.txAmount.value)) {
		    alert("Amount $ is not a Number!");
		  	document.frm.txAmount.focus();
          	return false;
		  }

      return true;
   }



   function init() {

	 if (document.frm.Reload.value="1")
     	opener.window.location.reload();
	 setDefault("document.frm.dcreditcard", "document.frm.creditcard");
	 setDefault("document.frm.dstatus", "document.frm.status");
   }
-->
</script>
<body onload="init()">
<table width="90%" border="1" align="center" cellpadding="5" cellspacing="0" bgcolor="#B0C4DE" style="border-collapse:collapse">
 <tr bgcolor="#52637B"><td>
<div align="center"><strong><font color="#FFCC00" size="3">Credit Card Transaction </font></strong></div>
  </td></tr>
  <tr>
    <td>
       <div align="right" style="margin-top:5pt;margin-bottom:5pt"><A href="javascript:this.self.close();" title="Close" class="s1">&gt;Close&lt;</A>
      </div>
	<form name="frm" method="post" action="" onsubmit="return check();">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan=2><div align="left" style="margin-bottom:2pt"><font color="#006699"><?php echo $msg?></font></div></td>
          </tr>
          <tr>
            <td width="40%">Credit Card </td>
            <td width="60%"><select name="creditcard">
              <option value=""></option>
              <?php
                   $creditstr = getCreditcard();
                   if ($creditstr !=null) {
                      //echo '<option>'.$catstr.'</option>';
				      $credit_array = explode(':', $creditstr);
		              reset($credit_array);
		              foreach ($credit_array as $current) {
		                $sep_pos = strpos($current, '@');
		                echo '<option value="'.substr($current, 0, $sep_pos).
		                     '">'.substr($current, $sep_pos+1).'</option>';
		               }
		           }
                ?>
            </select></td>
          </tr>
		  <tr>
		    <td valign=top>Transaction Date </td>
		    <td><input type="text" name="txDate" size="15" class="s1" readonly="1" value="<?php echo $txdate?>">
&nbsp;<a href="javascript:cal1.popup();" class="s2" title="Pick date from Calendar"><img src="images/cal.gif" alt="Click Here to Pick up the date" width="16" height="16" border="0" align="middle"></a>&nbsp;<font color="#336699">(mm/dd/yyyy)</font></td>
		  </tr>
          <tr>
            <td valign=top>Amount</td>
            <td>$ <input type="text" name="txAmount" size="20" value="<?php echo $txAmount?>"></td>
          </tr>
          <tr>
            <td valign=top>Status</td>
            <td><select name="status">
              <option value="NEW" checked>NEW</option>
              <option value="CLOSED">CLOSED</option>
            </select></td>
          </tr>
          <tr>
            <td valign=top>Purpose</td>
            <td><textarea name="txPurpose" cols="47" rows="3"><?php echo $purpose?></textarea></td>
          </tr>
          <tr>
            <td colspan="2"><div style="color:#006699;font-size:7pt;"><i>Last Modified Date : <?php echo $mdate?></i></div></td>
          </tr>
          <tr>
            <td colspan="2"> <div align="center">
                <input type="submit" name="Submit" value="Save">
                <input type="button" name="delete" value="Delete" onclick="document.frm.action.value='DELETE';document.frm.submit();">
                <input type="button" name="New" value="New" onclick="document.frm.action.value='';document.frm.submit();">
                <input type="hidden" name="action" value="<?php echo $CURRENT_ACTION?>">
                <input type="hidden" name="id" value="<?php echo $id?>">
				<input type="hidden" name="Reload" value="<?php echo $reload?>">
				<input type="hidden" name="dcreditcard" value="<?php echo $creditcardid?>">
				<input type="hidden" name="dstatus" value="<?php echo $status?>">
              </div></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
	  </form>

<script>
<!--
     var cal1 = null;
   	 cal1 = new calendar2(document.forms['frm'].elements['txDate']);
 	 cal1.year_scroll = true;
-->
</script>
</td>
  </tr>
</table>
<?php
$db->sql_close();
require('footer.inc')
?>
</body>
</html>