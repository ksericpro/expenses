<?php
	require_once('user_auth_fns.php');
	check_valid_user();
	include('include_fns.php');
?>
<?php require('header.inc')?>

<?php

  ///////////////////////////
  // Get Current Month & Day & Year
  ///////////////////////////

  $currentmonth = $_REQUEST['month'];
  $currentyear = $_REQUEST['year'];

  if ($currentmonth == null) {
	$currentmonth = date('m');
	$currentyear = date('Y');
  }


 // echo 'm='.$currentmonth.', yr='.$currentyear;
  $firstday = mktime(0,0,0, $currentmonth, 1, $currentyear);
  $totaldays = date('t', $firstday);
  $monthname = date("M", $firstday);

   ///////////////////
   // Last Month
   ////////////////////
  $lastMonth = $currentmonth - 1;
  $lastYear = $currentyear;
  if ($lastMonth < 1) {
  	$lastMonth = 12;
  	--$lastYear;
  }

  ///////////////////
  // Next Month
  ////////////////////
 $nextMonth = $currentmonth + 1;
 $nextYear = $currentyear;
 if ($nextMonth > 12) {
  	 $nextMonth = 1;
      ++$nextYear;
  }

 ?>
<?php
      $msg = '';
      $show = '';
      $c_no = '';
	  $id = $_REQUEST['id'];
	  $act = $_REQUEST['action'];


      include_once('db.php');

      if ($act == "DELETE") {

      	$sql = "delete from visa_transaction where transaction_id = $id";
      	//echo $sql;
		if( !$db->sql_query($sql) )
		    $msg = 'SQL error.';
	    else
			$msg = 'Credit Card Transaction '.$id.' Successfully Deleted.';
      }

      if ($act == "SHOW") {
            $c_no = $_REQUEST['CreditCard'];
            if ($c_no!="") {
				$msg = 'Showing Transactions for Credit Card #'.$_REQUEST['CreditCard'];
				$show = ' AND a.visa_id = '.$_REQUEST['CreditCard'];
            }
      }
?>
<html>
<head><title>Expenses - Credit Card</title></head>
<link rel="stylesheet" href="style.css">
<SCRIPT language="JavaScript" src="functions.js"></SCRIPT>
<SCRIPT language="JavaScript">
<!--
function go(ext)
{
   window.location = ext;
}
-->
</script>
<body>
<table width="60%" border="1" align="center" cellpadding="5" cellspacing="0" bgcolor="#B0C4DE" style="border-collapse:collapse">
  <tr bgcolor="#52637B">
    <td bgcolor="#52637B"> <div align="center"><font color="#FFCC00" size="3"><strong>Credit Card Record </strong></font> </div></td>
  </tr>
  <tr>
    <td>
	  <div> <a href="main.php" title="main menu" class="s1">Main Menu</a> | <a href="javascript:openNewWindow('creditcardrecord_edit.php?action=NEW','500','350');CURRENT_WINDOW.focus();" title="add Credit Card Transaction" class="s1">Add Credit Card Transaction</a>
        | <a href="javascript:openNewWindow('creditcard_edit.php','500','500');" title="Edit Credit Card" class=s1><font size="-2">Edit Credit Card</font></a> | <a href="logout.php" title="log off" class="s1">Log Off</a></div>
      <br>

	<form name="frm" method="post" action="">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr><td colspan="2">
		<input type="button" name="prev" class="s2" value="<"  title="previous month" onClick='javascript:go("?month=<?php echo $lastMonth?>&year=<?php echo $lastYear?>");'>
		<b><i><?php echo $monthname?> <?php echo $currentyear?></i></b>        <input title="next month" type="button" name="next" class="s2" value=">" onClick='javascript:go("?month=<?php echo $nextMonth?>&year=<?php echo $nextYear?>");'>
        &nbsp; Credit Card
		<select name="CreditCard" class=s2 onchange="document.frm.action.value='SHOW';document.frm.submit();">

              <option value="">-all-</option>
			   <?php
					$creditstr = getCreditcard();
					if ($creditstr !=null) {

					  $credit_array = explode(':', $creditstr);
					  reset($credit_array);
					  foreach ($credit_array as $current) {
						$sep_pos = strpos($current, '@');
						$val = substr($current, 0, $sep_pos);
						$str ='<option value="'.$val;
						if ($val == $c_no)
						   $str = $str.'" selected>';
						else
						   $str = $str.'">';
						echo $str.substr($current, $sep_pos+1).'</option>';
					   }
				   }
                ?>
        </select>
        <!--<input type="button" name="show" value="Show" onclick="document.frm.action.value='SHOW';document.frm.submit();" >
        -->
        <br>
        <a href="javascript:go('?')" title="Current" class=s1>&lt;Reset to Current&gt;</a>		</td>
		</tr>
          <tr>
            <td colspan=2><div align="left" style="margin-bottom:2pt"><font color="#006699"><?php echo $msg?></font></div></td>
          </tr>
          <tr>
            <td colspan="2">

			<table width="100%" border="1" cellspacing="0" cellpadding="0" style="border-collapse:collapse" bordercolor="#bbbbbb">
                <tr bgcolor="#666666">
                  <td>
                    <div align="center"><font color="#6699CC">Card</font></div></td>
                  <td>
                    <div align="center"><font color="#6699CC">Bank</font></div></td>
                  <td>
                    <div align="center"><font color="#6699CC">Date</font></div></td>
                  <td>
                    <div align="center"><font color="#6699CC">Purpose</font></div></td>
                  <td><div align="center"><font color="#6699CC">$$</font></div></td>
                  <td>
                    <div align="center"><font color="#6699CC">A1</font></div></td>
                  <td>
                    <div align="center"><font color="#6699CC">A2</font></div></td>
                </tr>

                <?php

				 $sql = "select a.visa_cardno, a.bank, b.transaction_id,  DATE_FORMAT(b.transaction_date,'%d %M %Y') 'transaction_date', b.purpose, b.amount from visa a ".
				 		"LEFT JOIN visa_transaction b ON b.visa_id = a.visa_id ".
				 		"WHERE a.userid = ".$_SESSION['userid'];
				 $sql=$sql.$show;

				 $sql=$sql.' AND MONTH( b.transaction_date ) = '.$currentmonth.
						   ' AND YEAR(b.transaction_date) = '.$currentyear;

			     $sql=$sql.' ORDER BY b.transaction_date DESC';

  				 if (DEBUG==1) echo '<span style="font-size:6pt">'.$sql.'</span>';
				 if( !($result = $db->sql_query($sql)) )
				    echo 'SQL Error';

				 $i = 0;
				 $amt = 0;
				 if ( $row = $db->sql_fetchrow($result) )
				 {
				 	do
				 	{
				 	    $i++;
						$amt += $row['amount'];
                ?>
                <tr bgcolor="#FFFFCC">
                  <td><div class="t1"><?php echo $row['visa_cardno'];?></div></td>
                  <td><div class="t1"><?php echo $row['bank'];?></div></td>
                  <td><div class="t1"><?php echo $row['transaction_date'];?></div></td>
                  <td><div class="t1"><?php echo $row['purpose'];?></div></td>
                  <td><div class="t1"><?php echo $row['amount'];?></div></td>
                  <td><div align="center"><a href="javascript:openNewWindow('creditcardrecord_edit.php?action=LOAD&id=<?php echo $row[transaction_id]?>','500','330');CURRENT_WINDOW.focus();" title="edit" class="s1">Edit</a></div></td>
                  <td><div align="center">
                  <a class='s1' title='Delete Record' href='javascript:ConfirmDeleteA("?", "<?php echo $row[transaction_id]?>");' >delete</a></div></td>
                </tr>

                <?php

                	}
				 	while ( $row = $db->sql_fetchrow($result) );

                $db->sql_freeresult($result);
                }

                $db->sql_close();
                ?>
              </table>

            </td>
          </tr>
          <tr>
            <td colspan=2><div style="color:#993366;font-size:7pt;"><i><?php echo $i.' record(s).'?></i></div></td>
          </tr>
		  <tr><td colspan=2> <b>Total : $<?php echo $amt?> </b>

		  <input type=hidden name=action value=""/>

		  </td></tr>
        </table>
	  </form>

</td>
  </tr>
</table>
<?php require('footer.inc')?>
</body>
</html>
