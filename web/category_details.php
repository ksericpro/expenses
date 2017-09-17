<?php
	require_once('user_auth_fns.php');
	check_valid_user();
?>
<?php require('header.inc')?>
<?php
      $msg = '';
      $userid = $_SESSION['userid'];
      $catid = $_REQUEST['catid'];
      $mth = $_REQUEST['month'];
      $year = $_REQUEST['year'];

      include_once('db.php');

	  $sql = "select ExpensesDate, Amount, Remarks from expenses WHERE userid = $userid AND CategoryID=$catid AND
	          month(ExpensesDate)=$mth AND year(ExpensesDate)=$year";

	  //echo $sql;
	  if ( !($result = $db->sql_query($sql)) )
		  $msg = "SQL error.";

?>
<html>
<head><title>Category - Details</title></head>
<link rel="stylesheet" href="style.css">

<body>
<table width="90%" border="1" align="center" cellpadding="5" cellspacing="0" bgcolor="#B0C4DE" style="border-collapse:collapse">
 <tr bgcolor="#52637B"><td>
<div align="center"><strong><font color="#FFCC00" size="3">CATEGORY Details</font></strong></div>
  </td></tr>
  <tr>
    <td>
       <div align="right" style="margin-top:5pt;margin-bottom:5pt"><A href="javascript:this.self.close();" title="Close" class="s1">&gt;Close&lt;</A>
      </div>

        <table width="100%" border="1" style="border-collapse:collapse" cellspacing="0" cellpadding="0">

       <tr>
         <td align=center><b>Date</b></td>
         <td align=center><b>Amount</b></td>
         <td align=center><b>Remarks</b></td>
       </tr>

 <?php
            $i=0;
            if ( $row = $db->sql_fetchrow($result) )
		  	 {
		  		do
		  		{
		  			$expdate = $row['ExpensesDate'];
		  			$amount = $row['Amount'];
		  			$remarks = $row['Remarks'];
			        $i++;

			?>
          <tr>

            <td><?php echo $expdate?></td>

            <td>$<?php echo $amount?></td>

            <td><?php echo $remarks?></td>
          </tr>

          <?php


		  	  	}
		  			while ( $row = $db->sql_fetchrow($result) );

		  		  $db->sql_freeresult($result);
		  	  }
		  	  $msg = 'Record Successfully Loaded.';

            $db->sql_close();
          ?>


          </tr>
        </table>


</td>
  </tr>
</table>
<?php require('footer.inc')?>
</body>
</html>