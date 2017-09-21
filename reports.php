
<?php
	require_once('user_auth_fns.php');
	check_valid_user();
?>
<?php require('header.inc')?>

<?php
     $userid = $_SESSION['userid'];
     $question = $_REQUEST['question'];
     echo $question;
     include_once('db_new.php');

     if ($question!="") {
        $cat_name="";
        $cat_remarks="";
        $q_array = explode('@', $question);
        $ct = 0;
        foreach ($q_array as $current) {
            switch($ct) {
				case 0: $cat_name = $current; break;
				case 1: $cat_remarks = $current; break;
			 }
		    $ct++;
        }

      //$cat_id = getCategoryID($db, $userid, $cat_name);
      //echo $cat_id;
      }
     $msg = '';

     echo $cat_name;


	 //$sql = "select ExpensesDate, Amount, Remarks from Expenses WHERE userid = $userid AND CategoryID=$catid AND
			//  month(ExpensesDate)=$mth AND year(ExpensesDate)=$year";

	  //echo $sql;
	  //if ( !($result = $db->sql_query($sql)) )
	  //$msg = "SQL error.";
 ?>
<html>
<head>
<meta http-equiv="Content-Language" content="en-us">
<title>Reports</title>
</head>
<link rel="stylesheet" href="style.css">

<SCRIPT language="JavaScript" src="functions.js"></SCRIPT>

<body>


<form name="form1">
<table border="1" width="60%" align=center cellpadding=0 cellspacing=0 style="border-collapse:collapse">
  <tr bgcolor="#52637B">
    <td> <div align="center"><font color="#FFCC00" size="3"><strong>Reports</strong></font> </div></td>
  </tr>
	<tr>
		<td><div class=t1><a href="main.php" title="main menu" class="s1">Main Menu</a> | <a href="logout.php" title="log off" class="s1">Log Off</a></div></td>
	</tr>
	<tr>
		<td align="center">&nbsp;<p>
		<a href="report_expenses.php" class=s1 title="Expenses Report"><font size="4">
		<b>&gt;Expenses Summary&lt;</b></font></a><br>
		<a href="report_creditcard.php" class=s1 title="Credit Card Report"><font size="4">
		<b>&gt;Credit Card Summary&lt;</b></font></a><br>
		<a href="report_stocks.php" class=s1 title="Stocks Report">
		<font size="4"><b>&gt;Stocks Summary&lt;</b></font></a><br><br>
		<table border=0><tr><td>
		When I last <select name="question">
		<option value="Grooming@hair cut">cut my hair</option>
		<option value="Household@maid">hire a maid</option>
		</select>?
		</td><td><input type=submit value="Ask Mr SQL" class=s1></td></tr>
		<tr><td colspan=2>




		 <?php




		     $con->close();
          ?>
		</td>
		</table>



		</p>
		<p>&nbsp;</td>
	</tr>
	<tr>
		<td align="center">&nbsp;</td>
	</tr>
	<tr>
		<td align="center" bgcolor="#52637B">
		<table width=100% cellpadding=0 cellspacing=0>
		       <tr>
                <td><div class=t1 align=left><font color="#FFFFCC"><?php echo 'User : '.$_SESSION['valid_user']?></font></div></td>
                <td><div align="center"><font color="#FFFFCC"><?php echo 'Role : '.$_SESSION['role']?></font></div></td>
                <td><div align="right" class=t1><font color="#FFFFCC"><a href="logout.php" title="log out" class="s2">Log
                    Off</a>&nbsp;</font></div></td>
              </tr>
        </table>

		</td>
	</tr>
</table>

</form>

</body>
</html>
<?php require('footer.inc')?>