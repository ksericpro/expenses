<?php
	require_once('user_auth_fns.php');
	check_valid_user();
	require_once('expense_fns.php');
	include_once('db.php');
?>
<?php require('header.inc')?>
<?php
      $msg = '<br>';
      $date = $_REQUEST['date'];
	  $formatteddate = $date;
	  if ($date!="")
	  {
		$pos = strripos($date, '-');
		$zpos = stripos($date, '-');
		$day = substr($date, $pos+1, strlen($date)-$pos);
		$month = substr($date, $zpos+1, $pos - $zpos -1);
		$formatteddate = $month.'/'.$day.'/'.substr($date, 0, 4);
	  }
      $act = $_REQUEST['action'];
      $userid = $_SESSION['userid'];
      $affectedid = $_REQUEST['id'];
      $dcatid = '';
      $expid = '';
      $amount = '';
	  $remarks = '';

      switch ($act) {
         case 'ADD':
              $editid = $_REQUEST['editid'];
			  $catid = $_POST['category'];
			  $expdate = $_POST['txDate'];
			  $pos = strripos($expdate, '/');
			  $zpos = stripos($expdate, '/');
		      $yr = substr($expdate, $pos+1, 4);
		      $expdate = $yr.'/'.substr($expdate, 0, $pos);
			  
			  $amount = $_POST['txAmount'];
			  $remarks = $_POST['txRemarks'];
			  if (!get_magic_quotes_gpc()) {
				 $amount = addslashes($amount);
				 $remarks = addslashes($remarks);
			  }

			  $mdate = date('Y-m-d H:i:s');

			  if ($editid == "") {
			  	//echo 'adding....';
			  	$sql = "insert into expenses(ExpensesDate, Amount, Remarks, UserID,
					   CategoryID, ModifiedDate)
					   values('".$expdate."', ".$amount.", '".$remarks."', ".
					   $userid.", ".$catid.", '".$mdate."')";
			     $msg = '('.$catid.') Expenses of $\''.$amount. '\' Successfully Added.';
			     $amount = "";
			     $remarks = "";
              } else {
                 //echo "updating...".$editid;
                 $sql = "update expenses set Amount = $amount, Remarks = '$remarks',
                         CategoryID = $catid, ExpensesDate = '$expdate', ModifiedDate = '$mdate'
                         where ExpensesID = $editid";
                 $msg = '('.$catid.') Expenses of $\''.$amount. '\' Successfully Updated.';
                 $dcatid = $catid;
              }

			 // echo '<br>'.$sql;
			  if( !$db->sql_query($sql) )
				$msg = 'SQL error.';

              break;

       case 'DELETE':
              //echo 'deleting....'.$affectedid;
              $sql = "delete from expenses where ExpensesID = $affectedid";

			  //echo $sql;
			  if( !$db->sql_query($sql) )
				$msg = 'SQL error.';
			  else
			    $msg = 'Expenses '.$id.' Successfully Deleted.';

              break;
       case 'EDIT':
       		  //echo 'editing....'.$affectedid;
       		  $sql = "select ExpensesID, CategoryID, Amount, Remarks, ModifiedDate from expenses
                      where ExpensesID = $affectedid";

              //echo $sql;
			  if ( !($result = $db->sql_query($sql)) )
				  $msg = "SQL error.";

			  if( $row = $db->sql_fetchrow($result) )
			  {
				  $expid = $row['ExpensesID'];
				  $amount = $row['Amount'];
				  $dcatid = $row['CategoryID'];
				  $remarks = $row['Remarks'];
				  $mdate = $row['ModifiedDate'];

				  $db->sql_freeresult($result);
				  $msg = 'Record Successfully Loaded.';
		      }

              break;
      }


?>
<html>
<head><title>Expenses - User</title></head>
<link rel="stylesheet" href="style.css">
<SCRIPT language="javascript" src="calendar2.js"></SCRIPT>
<SCRIPT language="JavaScript" src="functions.js"></SCRIPT>
<script language="javascript">
<!--
   ////////////////////
   // Validation
   ////////////////////
   function check() {

      if (document.frm.category.value=="") {
		  alert("Category cannot be empty!");
		  return false;
      }
      if (document.frm.txAmount.value=="") {
          alert("Amount cannot be empty!");
          document.frm.txAmount.focus();
          return false;
      } else if (isNaN(document.frm.txAmount.value)) {
		  alert("Amount is not a number");
		  document.frm.txAmount.focus();
 		  return false;
      }

      return true;
   }

   /////////////////
   // Initialise
   /////////////////
   function init() {
   	 setDefault("document.frm.dcategory", "document.frm.category");
   }

   ////////////////////
   // Write Total Amount
   ////////////////////
   function writeTotalAmount(total) {
     document.getElementById('totalamount').innerHTML = total;
   }
-->
</script>
<body onload="init()">
<table width="96%" border="1" align="center" cellpadding="5" cellspacing="0" bgcolor="#B0C4DE" style="border-collapse:collapse">
<tr bgcolor="#52637B"><td>
<div align="center"><strong><font color="#FFCC00" size="3">EXPENSES Add</font></strong></div>
  </td></tr>
  <tr>
    <td>
<div align="right" style="margin-top:5pt;margin-bottom:5pt"><A href="javascript:this.self.close();opener.window.location.reload();" title="Close" class="s1">&gt;Close&lt;</A>
      </div>

	<form name="frm" method="post" action="" onsubmit="return check();">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan=2><div align="left" style="margin-bottom:2pt"><font color="#006699"><?php echo $msg?></font></div></td>
          </tr>
          <tr>
            <td width="25%">User ID</td>
            <td width="75%">&nbsp;&nbsp;<input type="text" name="txUserID" size="5" class=s1 readonly="1" value="<?php echo $userid?>"></td>
          </tr>
          <tr>
            <td valign=top>Date</td>
            <td>&nbsp;&nbsp;<input type="text" name="txDate" size="15" class="s1" readonly="1" value="<?php echo $formatteddate?>">
              &nbsp;<a href="javascript:cal1.popup();" class="s2" title="Pick date from Calendar"><img src="images/cal.gif" alt="Click Here to Pick up the date" width="16" height="16" border="0" align="middle"></a>&nbsp;<font color="#336699">(mm/dd/yyyy)</font></td>
          </tr>
          <tr>
            <td valign=top>Category</td>
            <td valign="top">&nbsp;&nbsp;<select name="category">
                <option value=""></option>
                <?php
                   $catstr = getCategory($db);
                   if ($catstr !=null) {
                      //echo '<option>'.$catstr.'</option>';
				      $cat_array = explode(':', $catstr);
		              reset($cat_array);
		              foreach ($cat_array as $current) {
		                $sep_pos = strpos($current, '@');
		                echo '<option value="'.substr($current, 0, $sep_pos).
		                     '">'.substr($current, $sep_pos+1).'</option>';
		               }
		           }
                ?></select></td>
          </tr>
          <tr>
            <td valign=top>Amount</td>
            <td><font color="#336699">$</font><input type="text" name="txAmount" size="30" value="<?php echo $amount?>"></td>
          </tr>
          <tr>
            <td valign=top>Remarks</td>
            <td valign="top">&nbsp;&nbsp;<textarea name="txRemarks" cols="40" rows="3" class="s2"><?php echo $remarks?></textarea></td>
          </tr>
          <tr>
            <td colspan="2"> <div align="center">
                <input type="submit" name="Save" value="Save" onclick="javascript:document.frm.action.value='ADD';">
                <input type="reset" name="Reset" value="Reset" onclick="javascript:document.frm.submit();">
                <input type="button" name="New" value="New" onclick="document.frm.action.value='';document.frm.submit();">
                <input type="hidden" name="action" value="">
                <input type="hidden" name="editid" value="<?php echo $expid?>">
                <input type="hidden" name="dcategory" value="<?php echo $dcatid?>">
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
			<table width="100%"><tr><td width="27%"><u>Existings Record(s)</u></td>
                  <td width="42%"><div align="center"><a href="javascript:openNewWindow2('statistics_daily.php?date=<?php echo $date?>','bar','530', '400')" title="plot graph"><img src="images/graph.gif" border=0></a></div></td>
			<td width="31%" align=right>Total :$<span id="totalamount"></span></td>
			</tr></table>

			<br>
			<table width="100%" border="1" cellspacing="0" cellpadding="0" style="border-collapse:collapse" bordercolor="#bbbbbb">
                <tr bgcolor="#666666">
                  <td>
				    <div align="center"><font color="#6699CC">#</font></div></td>
                  <td>
                    <div align="center"><font color="#6699CC">Category</font></div></td>
                  <td>
                    <div align="center"><font color="#6699CC">Amount</font></div></td>
                  <td>
                    <div align="center"><font color="#6699CC">Last Modified</font></div></td>
                  <td>
                    <div align="center"><font color="#6699CC">A1</font></div></td>
                  <td>
                    <div align="center"><font color="#6699CC">A2</font></div></td>
                </tr>

                <?php

                 $sql = "select e.ExpensesID, e.Amount, e.ModifiedDate, c.Name from expenses e, category c
                         where c.CategoryID = e.CategoryID and e.UserID = $userid
                         and e.ExpensesDate = '$date'";

                 //echo '<tr></td>'.$sql.'</td></tr>';
				 if( !($result = $db->sql_query($sql)) )
					echo '<tr></td>SQL Error</td></tr>';

				 $i = 0;
				 $totalamount = 0;
				 if ( $row = $db->sql_fetchrow($result) )
				 {
					do
					{
						$expid = $row['ExpensesID'];
						$amount = $row['Amount'];
						$categoryname = $row['Name'];
						$mdate = $row['ModifiedDate'];
						$i++;
						$totalamount +=$amount;

                ?>

                <tr bgcolor="#FFFFCC">
                  <td><div class="t1"><?php echo $expid?></div></td>
                  <td><div class="t1"><?php echo $categoryname?></div></td>
                  <td><div class="t1"><?php echo $amount?></div></td>
                  <td><div class="t1"><?php echo $mdate?></div></td>
                  <td><div align="center"><a href="?action=EDIT&id=<?php echo $expid?>&date=<?php echo $date?>" title="edit" class="s1">Edit</a></div></td>
                  <td><div align="center">
                  <a class='s1' title='Delete Record' href='javascript:ConfirmDeleteB("?", "<?php echo $expid?>", "<?php echo $date?>");' >delete</a></div></td>
                </tr>

                <?php

                	}
				 	while ( $row = $db->sql_fetchrow($result) );

                $db->sql_freeresult($result);
                }

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

 <script language="javascript">
  <!--
	writeTotalAmount(<?php echo $totalamount?>);
  -->
  </script>
  
  
  <script>
<!--
     var cal1 = null;
   	 cal1 = new calendar2(document.forms['frm'].elements['txDate']);
 	 cal1.year_scroll = true;
-->
</script>
<?php
	$db->sql_close();
	require('footer.inc');
  ?>
</body>
</html>
