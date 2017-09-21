<?php
	require_once('user_auth_fns.php');
	check_valid_user();
	include_once('db.php');
?>
<?php require('header.inc')?>

<html>
<head>
<meta http-equiv="Content-Language" content="en-us">
<title>Reports</title>
</head>
<link rel="stylesheet" href="style.css">

<SCRIPT language="JavaScript" src="functions.js"></SCRIPT>
<script language="javascript">
<!--
	function check() {
	 //if (document.frm.c_month.value=="-1") {
	 //  alert("Month cannot be empty!");
	 //   return false;
	 // }
	 if (document.frm.c_year.value=="-1") {
	   alert("Year cannot be empty!");
	    return false;
	  }
	  return true;
	 }

   function init() {
 		now = new Date;
 		var mth = now.getMonth();

 		for (var i=0;i<document.frm.c_month.length;i++)
 		  if (parseInt(document.frm.c_month[i].value)==mth+1) {
 		    document.frm.c_month[i].selected = 1;
 		    break;
 		    }

 		var yr = now.getFullYear();
 		for (var i=0;i<document.frm.c_year.length;i++)
 		  if (parseInt(document.frm.c_year[i].value)==yr) {
 		    document.frm.c_year[i].selected = 1;
 		    break;
 		    }
   }

function Start(page,h,w) {
   OpenWin = this.open(page, "report", "toolbar=no,menubar=no,location=no,scrollbars=no,statusbar=no,resizable=no,height=" + h + ", width=" + w);
}

function openPDF(){
    var month = document.frm.c_month.value;
    var year = document.frm.c_year.value;

	if (check())
		Start('report_expenses_pdf.php?month='+month+'&year='+year,50,50);
}
-->
</script>
<body onload="init();">

<table border="1" width="40%" align=center cellpadding=0 cellspacing=0 style="border-collapse:collapse">
  <tr bgcolor="#52637B">
    <td> <div align="center"><font color="#FFCC00" size="3"><strong>Reports - Expenses</strong></font> </div></td>
  </tr>
	<tr>
		<td><div class=t1><a href="main.php" title="main menu" class="s1">Main Menu</a> | <a href="reports.php" title="reports menu" class="s1">Reports Menu</a> | <a href="logout.php" title="log off" class="s1">Log Off</a></div></td>
	</tr>
	<tr>
		<td align="center"><p>&nbsp;</p>

		<form name="frm" method="post" action="" onsubmit="return check();">

		<table border="0" width="63%" cellpadding=0 cellspacing=0>
			<tr>
				<td width="151">Please choose Month</td>
				<td>

		    <select name="c_month" class="s2">
		    <option value="-1">-All-</option>
			<option value="1">Jan</option>
			<option value="2">Feb</option>
			<option value="3">Mar</option>
			<option value="4">Apr</option>
			<option value="5">May</option>
			<option value="6">Jun</option>
			<option value="7">Jul</option>
			<option value="8">Aug</option>
			<option value="9">Sep</option>
			<option value="10">Oct</option>
			<option value="11">Sep</option>
			<option value="12">Dec</option>
			 </select></td>
			</tr>
			<tr>
				<td>

				Please chooose Year</td><td>
					    <select name="c_year" class=s2>
					    <option value="-1"></option>
						 <script language="javascript">
						  <!--
							writeSelectYear();
							-->
						  </script>

						</select></td>

			</tr>
			<tr>
				<td>

				<!--Click <input type="image" name="mysubmit" src="images/run.GIF" title="Generate Report">to Generate Report<p>-->
				<i>Click Icon to get report --></i></td><td>
				<a href="javascript:openPDF();" title="Get Report">
				<img border="0" src="images/pdf.jpg" width="90" height="90"></a></td>

			</tr>
			<tr>
				<td width="151">&nbsp;

				</td>
				<td>&nbsp;</td>
			</tr>
		</table>

		</form>
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
                <td><div align="center"><font color="#FFFFCC">&nbsp;</font></div></td>
                <td><div align="right" class=t1><font color="#FFFFCC"><a href="logout.php" title="log out" class="s2">Log
                    Off</a>&nbsp;</font></div></td>
              </tr>
        </table>

		</td>
	</tr>
</table>

</body>
</html>
<?php require('footer.inc')?>