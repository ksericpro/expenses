<?php
	require_once('user_auth_fns.php');
	check_valid_user();
	include('creditcard_fns.php');
?>
<?php require('header.inc')?>
<?php

    $currentpage = $_REQUEST['page'];
    $totalrecords = $_REQUEST['totalrecords'];
    $totalpages = $_REQUEST['totalpages'];
?>

<?php
      $msg = '<br>';
      $nric = $_SESSION['userid'];

      $success = 0;
      $act = $_REQUEST['act'];


      if (DEBUG==1) echo $act.'<br>';
      switch ($act) {
        case 'SAVE':
     	case 'EDIT':
     	    $res = insert_CreditCard($_POST);
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

		  	//echo 'dd'.$id;

		 	$act = "LOAD";
     	break;

     	case 'DELETE':
            $did = $_REQUEST['CreditCard'];
	 		if (delete_CreditCard($did))
      			$msg = "Credit Card record ".$did." Successfully Deleted.";
      		$CURRENT_ACTION = "SAVE";
      		$success = 1;
     	break;

	 	case 'LOAD':
	 		$id = $_REQUEST['CreditCard'];
	 	    $msg = "Credit Card record ".$id." Successfully Loaded.";
	 	break;

	 	default :$CURRENT_ACTION = "SAVE"; break;
      }

      if ($act=="LOAD") {

         $creditcard = load_CreditCard($id);
         $mdate = $creditcard['lastmodifieddate'];
		 if ($id==-1)$CURRENT_ACTION = "SAVE";
         else $CURRENT_ACTION = "EDIT";
	  }

      if (DEBUG == 1) echo  '<center>'.$act.' : '.$sql.'</center><br>';

?>
<html>
<head><title>Credit Card</title>
</head>
<link rel="stylesheet" href="style.css">
<script language="JavaScript" src="functions.js"></script>
<script language="javascript">
<!--
   ////////////////////
   // Validation
   ////////////////////
   function check() {

      if (document.frm.bankname.value=="") {
		  alert("Bank Name cannot be empty!");
		  document.frm.bankname.focus();
		  return false;
      }

      if (document.frm.cardnumber.value=="") {
		  alert("Card Number cannot be empty!");
		  document.frm.cardnumber.focus();
		  return false;
      }

      if (document.frm.points.value!="") {
		  if (isNaN(document.frm.points.value) ){
				alert("Points must be a Number!");
				document.frm.points.focus();
				return false;
				}
		 if (parseInt(document.frm.points.value) < 1){
						alert("Points Date must be > 0!");
						document.frm.points.focus();
						return false;
				}
		}


       if (document.frm.payment_start.value=="") {

         alert("Payment Start Date cannot be empty!");
	   	  document.frm.payment_start.focus();
		  return false;
       } else {
			if (isNaN(document.frm.payment_start.value) ){
				alert("Payment Start Date must be a Number!");
				document.frm.payment_start.focus();
				return false;
			}

			if (parseInt(document.frm.payment_start.value) < 1){
				alert("Payment Start Date must be > 0!");
				document.frm.payment_start.focus();
				return false;
			}

	  	}

       if (document.frm.payment_end.value=="") {

         alert("Payment End Date cannot be empty!");
	   	  document.frm.payment_end.focus();
		  return false;
        } else {
	   			if (isNaN(document.frm.payment_end.value) ){
	   				alert("Payment End Date must be a Number!");
	   				document.frm.payment_end.focus();
	   				return false;
	   			}

	   			if (parseInt(document.frm.payment_end.value) < 1){
	   				alert("Payment End Date must be > 0!");
	   				document.frm.payment_end.focus();
	   				return false;
	   			}

	  	}

      return true;
   }

   /////////////////
   // Initialise
   /////////////////
   function init() {
     if (document.frm.reloadmaster.value=="1")
   	  	opener.window.location.reload();
   	 setDefault("document.frm.d_CreditCard", "document.frm.CreditCard");
   }

-->
</script>
<body onload="init();">

	<table width="90%" border="1" align="center" cellpadding="5" cellspacing="0" bgcolor="#B0C4DE" style="border-collapse:collapse">
		<tr bgcolor="#52637B">
			<td>
			<div align="center"><strong><font size="3" color="#FFCC00">Credit Card </font></strong></div>
			</td>
		</tr>

		<tr><td bgcolor=#cccccc>

		    <table width=100% cellpadding=0 cellspacing=0><tr><td>

		    </td><td>
		    <div align="right" class=t1>
				<A href="javascript:this.self.close();" title="Close" class="s1">Close</A>
			</div>
			</td></table>

		</td></tr>

		<tr>
			<td>
<br>
			<form name="frm" method="post" action="" onsubmit="return check();">

				<table width="100%" border="0" cellspacing="0" cellpadding="0" >
					<tr>
						<td colspan=2>
						<div align="left" style="margin-bottom:2pt;margin-left:2pt;">
							<font color="#006699"><?php echo $msg?></font></div>

						</td>
					</tr>

					<tr>
						<td valign=top>Bank Name </td>
						<td>
						<input type = 'text' name = 'bankname' value = "<?php echo $creditcard['bank']?>" maxlength = 50 size=50 class=s2>
              			</td>
					</tr>
					<tr>
						<td>Card Number </td>
						<td>
						<input type = 'text' name = 'cardnumber' value = "<?php echo $creditcard['visa_cardno']?>" maxlength = 30 size=30 class=s2></td>
					</tr>
					<tr>
					  <td>Expire Date(MM/YY) </td>
					  <td><input type = 'text' name = 'expirydate' value = "<?php echo $creditcard['expirydate']?>" maxlength = 20 size=20 class=s2></td>
				  </tr>
					<tr>
					  <td>Points</td>
					  <td><input type = 'text' name = 'points' value = "<?php echo $creditcard['points']?>" maxlength = 5 size=5 class=s2></td>
				  </tr>
					<tr>
						<td>Payment Range  </td>
						<td>
						<input type = 'text' name = 'payment_start' value = "<?php echo $creditcard['payment_start']?>" maxlength = 5 size=5 class=s2>
						~
						<input type = 'text' name = 'payment_end' value = "<?php echo $creditcard['payment_end']?>" maxlength = 5 size=5 class=s2>  (Previous Month to Current Month)</td>

					</tr>
					 <tr>
				       <td colspan="2">
<br>

				       <u><b>Existing Credit Cards </b></u>
				       <p>
						Credit Card
						  <select name="CreditCard" class=s2>

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
          </select><br><input type="button" name="edit" value="Edit" onclick="document.frm.act.value='LOAD';document.frm.submit();" >&nbsp;
          <input type="button" name="delete" value="Delete" onclick="document.frm.act.value='DELETE';document.frm.submit();" ></td>
                    </tr>
					 <tr>
				       <td colspan="2"><div style="color:#993366;font-size:7pt;"><i>Last Modified Date : <?php echo $mdate?></i></div></td>
                    </tr>
					<tr>
						<td colspan="2">

							<p align="center">

							<input type="submit" name="Save" value="Save" onclick="javascript:document.frm.act.value='<?php echo $CURRENT_ACTION?>';" >
							<input type="button" name="Reset" value="New" onclick="javascript:document.frm.act.value='';document.frm.submit();">
							<input type="hidden" name="reloadmaster" value="<?php echo $success?>">
							<input type="hidden" name="id" value="<?php echo $id?>">
							<input type="hidden" name="act" value="<?php echo $act?>">
							<input name="d_CreditCard" type=hidden value="<?php echo $id?>">
							<input name="page" type=hidden value="<?php echo $currentpage?>">
							<input name="totalpages" type=hidden value="<?php echo $totalpages?>">
                			<input type="hidden" name="reinit" value="">
						</td>
					</tr>
					</table>

			</form></td>
		</tr>

         <tr height=5>
            <td bgcolor="#cccccc"> <table width=100% border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><div class=t1 align=left><font color="#cc3300" size=-2><?php echo 'User : '.$_SESSION['valid_user']?></font></div></td>
                  <td><div align="right"><font color="#cc3300" size=-2>&nbsp;</font></div></td>

                </tr>
              </table></td>
        </tr>

	</table>

<?php
if ($conn) mysql_close();
require('footer.inc')?>
</body>
</html>