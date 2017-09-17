<?php
	require_once('user_auth_fns.php');
	check_valid_user();
	$userid = $_SESSION['userid'];
	$settingsid = $_SESSION['settingsid'];;
?>
<?php require('header.inc')?>

   <?php

     include_once('db.php');
     include_once('general_fns.php');

     ///////////////////////////
     // Get Current Month & Day & Year
     ///////////////////////////

     $currentmonth = $_REQUEST['month'];
     $currentyear = $_REQUEST['year'];

     if ($currentmonth == null) {
     	$currentmonth = date('m');
     	$currentyear = date('Y');
     }

     $currentday = date('j');
     $highlight_today = false;
     if ( ($currentmonth == date('m')) && ($currentyear == date('Y')) )
        $highlight_today = true;


     $firstday = mktime(0,0,0, $currentmonth, 1, $currentyear);
     $totaldays = date('t', $firstday);
     $monthname = date("M", $firstday);
     $firstday_dayofweek = date("w", $firstday);

     if (PRINT_COMMENTS == 1) {
     	echo '<br>Current Day :'.$currentday;
     	echo '<br>Total Days in Month :'.$totaldays;
     	echo '<br>1st Day of Month : '.$firstday_dayofweek;
     	echo '<br>Current Month :'.$currentmonth;
	 	echo '<br>Month Name :'.$monthname;
     	echo '<br>Current Year :'.$currentyear;
     	}


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

  	// Save Advance Settings
  	$act = $_REQUEST['action'];
  	$showadvance = $_POST['showadvance'];
  	if ($showadvance == null) $showadvance = 'hidden';

  	if ($act == "SAVE") {
  	  /////////////
  	  // Save
  	  /////////////
  	  $graphwidth = $_POST['txwidth'];
  	  $graphheight = $_POST['txheight'];
  	  $graphborder = $_POST['txborder'];
  	  $graphypoints = $_POST['txypoints'];
  	  $graphbkcolor = $_POST['txbkcolor'];
  	  $graphlinecolor = $_POST['txlinecolor'];
  	  $graphaxiscolor = $_POST['txaxiscolor'];
  	  $graphtextcolor = $_POST['txtextcolor'];
  	  $graphbkhexcolor = convertColorStr($graphbkcolor);

  	  //echo $graphbkcolor.' '.$graphbkhexcolor;
	  $graphlinehexcolor = convertColorStr($graphlinecolor);
	  $graphaxishexcolor = convertColorStr($graphaxiscolor);
	  $graphtexthexcolor = convertColorStr($graphtextcolor);
  	  $graphtype = $_POST['gtype'];
  	  $mdate = date('Y-m-d H:i:s');

  	  $sql = "update settings set Graphwidth = $graphwidth, Graphheight = $graphheight,
  	          Graphborder = $graphborder, Graphypoints = $graphypoints,
  	          Graphbkcolor = '$graphbkcolor', Graphlinecolor = '$graphlinecolor',
  	          Graphaxiscolor = '$graphaxiscolor', Graphtextcolor = '$graphtextcolor',
  	          Graphtype = '$graphtype',
  	          ModifiedDate = '$mdate'
  	          where SettingsID = $settingsid";

	   //echo $sql;
	  if( !$db->sql_query($sql) )
	     $msg = 'SQL error.';
	   else
		 $msg = 'Settings Successfully Updated.';

  	  } else {
  	    /////////////
  	    // Load
  	    /////////////

  	    $sql = "select Graphwidth, Graphheight, Graphborder, Graphypoints, Graphtype,
  	            Graphbkcolor, Graphlinecolor, Graphaxiscolor, Graphtextcolor
  	            from settings where Settingsid = $settingsid";
  	    //echo $sql;

	    if ( !($result = $db->sql_query($sql)) )
			  $msg = "SQL error.";

		if( $row = $db->sql_fetchrow($result) )
		  {
			  $graphwidth = $row['Graphwidth'];
			  $graphheight = $row['Graphheight'];
			  $graphborder = $row['Graphborder'];
			  $graphypoints = $row['Graphypoints'];
			  $graphbkcolor = $row['Graphbkcolor'];
			  $graphlinecolor = $row['Graphlinecolor'];
			  $graphaxiscolor = $row['Graphaxiscolor'];
  	    	  $graphtextcolor = $row['Graphtextcolor'];
			  $gtype = $row['Graphtype'];
			  $db->sql_freeresult($result);
			  $msg = "Settings Successfully Loaded.";

			  $graphbkhexcolor = convertColorStr($graphbkcolor);
			  $graphlinehexcolor = convertColorStr($graphlinecolor);
			  //echo $graphlinehexcolor;
			  $graphaxishexcolor = convertColorStr($graphaxiscolor);
			  $graphtexthexcolor = convertColorStr($graphtextcolor);
		  }

  	  }
 ?>
<head><title>stocks</title></head>
<link rel="stylesheet" href="style.css">
<script language="JavaScript" src="functions.js"></script>
<script language="JavaScript">
<!--
   var ADVANCE = 0;
   ////////////////////
   // Write Total Amount
   ////////////////////
   function writeTotalAmount(total) {
     document.getElementById('totalamount').innerHTML = total;
   }

   function check() {
      if (isNaN(document.frm.txheight.value)) {
   		  alert("Height is not a number");
   		  document.frm.txheight.value = "";
   		  document.frm.txheight.focus();
 		  return false;
 	  }
 	  if (isNaN(document.frm.txheight.value)) {
	  	  alert("Width is not a number");
	  	  document.frm.txwidth.value = "";
	      document.frm.txwidth.focus();
	   	  return false;
 	  }
 	  if (isNaN(document.frm.txypoints.value)) {
	  	  alert("Y-Points is not a number");
	   	  document.frm.txypoints.value = "";
	      document.frm.txypoints.focus();
	   	  return false;
 	  }
  	  if (isNaN(document.frm.txborder.value)) {
 	  	  alert("Border is not a number");
 	   	  document.frm.txborder.value = "";
 	      document.frm.txborder.focus();
 	   	  return false;
 	  }

 	  if ((document.frm.txheight.value < 1) || (document.frm.txheight.value < 1)
 	     || (document.frm.txypoints.value < 1) || (document.frm.txborder.value < 1) )
 	     {
 	      alert("Number must be > 0");
 	      return false;
 	     }
 	 return true;
   }

   //Open graph
   function openGraph(str) {

      if (check()) {
      //Determine Monthly/Yearly Type
      for(i = 0; i <document.frm.graphtype.length;i++)
        if (document.frm.graphtype[i].checked)
           var graphtype = document.frm.graphtype[i].value;

      // Graph Attributes
      var height = parseInt(document.frm.txheight.value);
	  var width = parseInt(document.frm.txwidth.value);
	  var ypoints = document.frm.txypoints.value;
	  var border = document.frm.txborder.value;
	  var lineorbar = document.frm.gtype.value;

	  var graphbkcolor = replaceChar(document.frm.graphbkcolor.style.backgroundColor);
	  var graphlinecolor = replaceChar(document.frm.graphlinecolor.style.backgroundColor);
	  var graphaxiscolor = replaceChar(document.frm.graphaxiscolor.style.backgroundColor);
	  var graphtextcolor = replaceChar(document.frm.graphtextcolor.style.backgroundColor);

      var url = str;
      var ext = '&graphtype=' + graphtype + '&width=' + width +
   	        '&height=' + height + '&ypoints=' + ypoints +
   	        '&border=' + border +
   	        '&lineorbar=' + lineorbar +
   	        '&graphbkcolor=' + graphbkcolor +
   	        '&graphlinecolor=' + graphlinecolor +
   	        '&graphaxiscolor=' + graphaxiscolor +
   	        '&graphtextcolor=' + graphtextcolor;

      if (lineorbar == 'LINE') { width +=50; height +=50; }
   	  else { width=550; height=750; }

   	  openNewWindow(url + ext, width, height);
   	  }
   }

   function replaceChar(text) {

       if (text.indexOf('#') == -1) {
			text = text.replace(/\ /g,"");
			text = text.replace(/rgb\(/g,"");
			text = text.replace(/\)/g,"");
       	} else
       	    text = convertColorStr(text);

       return text;
   }

   function showAdvance() {
      if (ADVANCE == 0) {
      		document.all['advancesettings'].style.visibility = "visible";
      		ADVANCE = 1;
      		}
      else {
      		document.all['advancesettings'].style.visibility = "hidden";
      		ADVANCE = 0;
      	}
   }

   function synColor() {
  	  document.frm.txbkcolor.value = replaceChar(document.frm.graphbkcolor.style.backgroundColor);
  	  document.frm.txlinecolor.value = replaceChar(document.frm.graphlinecolor.style.backgroundColor);
  	  document.frm.txaxiscolor.value = replaceChar(document.frm.graphaxiscolor.style.backgroundColor);
	  document.frm.txtextcolor.value = replaceChar(document.frm.graphtextcolor.style.backgroundColor);
   }

   function operationSettings(func) {
     if (check()) {
     	synColor();
     	document.frm.showadvance.value = "visible";
        document.frm.action.value=func;
        document,frm.submit();
     }
   }

   /////////////////
  // Initialise
  /////////////////
  function init() {
	 setDefault("document.frm.defgtype", "document.frm.gtype");
	 if (document.frm.showadvance.value == "hidden") ADVANCE = 0; else ADVANCE = 1;
   }
   -->
</script>

<body onload="init()">

<table align="center" width=100%>
  <tr>
    <td width="20%">
		<form name="frm" method="post" action="">
        <div align="center">
          <input name="graphtype" type="radio" value="monthly" checked>
          Month&nbsp;
          <input type="radio" name="graphtype" value="yearly">
          Year&nbsp; <a href="javascript:openGraph('statistics_monthly.php?month=<?php echo $currentmonth?>&year=<?php echo $currentyear?>');CURRENT_WINDOW.focus();" title="statistics" class="s1">
          <img src="images/graph.gif" border="0"></a> <br>
          <br>
          &nbsp;&nbsp;<a onclick="showAdvance();" href="javascript:;" title="Advance Settings" class="s1">Advance
          Settings</a> <br>
          <br>
        </div>
        <table width="80%" border="0" align="center" cellpadding="0"  cellspacing="0" id="advancesettings" style="visibility:<?php echo $showadvance;?>">
          <tr bgcolor="#9999CC">
            <td colspan="2"> <div align="center"><a href="javascript:operationSettings('SAVE');" title="Save settings" class="s2"><i>&lt;Save&gt;</i></a>&nbsp;<font color="#FFFFCC">Advance
                Settings</font></div></td>
          </tr>
          <tr>
            <td> <table width="100%"  cellspacing="0" cellpadding="0" border="1" >
                <tr>
                  <td width="69%"><font color="#993366">Width</font></td>
                  <td width="31%"> <font color="#993366">
                    <input type="text" name="txwidth" size="2" value="<?php echo $graphwidth?>" class=s1>
                    </font></td>
                </tr>
                <tr>
                  <td><font color="#993366">Height</font></td>
                  <td><font color="#993366">
                    <input type="text" name="txheight" size="2" value="<?php echo $graphheight?>" class=s1>
                    </font></td>
                </tr>
                <tr>
                  <td><font color="#993366">Y-Points</font></td>
                  <td><font color="#993366">
                    <input type="text" name="txypoints" size="2" value="<?php echo $graphypoints?>" class=s1>
                    </font></td>
                </tr>
                <tr>
                  <td><font color="#993366">Background</font></td>
                  <td><font color="#993366">
                    <input type="text" name="txbkcolor" ID="graphbkcolor" size="2" class=s1 readonly=1 <?php fillValues($graphbkhexcolor, $graphbkcolor); ?> >
                    <a class=s1 href="javascript:openColorPicker('colorpicker.php?', 'graphbkcolor');COLOR_PICKER.focus();" title="color picker"><img src="images/choose.gif" border=0 alt="@"></a>
                    </font></td>
                </tr>
                <tr>
                  <td><font color="#993366">Line Color</font></td>
                  <td><font color="#993366">
                    <input type="text" name="txlinecolor" ID="graphlinecolor" size="2" class=s1 readonly=1 <?php fillValues($graphlinehexcolor, $graphlinecolor); ?> >
                    <a class=s1 href="javascript:openColorPicker('colorpicker.php?', 'graphlinecolor');COLOR_PICKER.focus();" title="color picker"><img src="images/choose.gif" border=0 alt="@"></a>
                    </font></td>
                </tr>
                <tr>
                  <td><font color="#993366">Axis Color</font></td>
                  <td><font color="#993366">
                    <input type="text" name="txaxiscolor" ID="graphaxiscolor" size="2" class=s1 readonly=1 <?php fillValues($graphaxishexcolor, $graphaxiscolor); ?> >
                    <a class=s1 href="javascript:openColorPicker('colorpicker.php?', 'graphaxiscolor');COLOR_PICKER.focus();" title="color picker"><img src="images/choose.gif" border=0 alt="@"></a></font></td>
                </tr>
                <tr>
                  <td><font color="#993366">Text Color</font></td>
                  <td><font color="#993366">
                    <input type="text" name="txtextcolor" ID="graphtextcolor" size="2" class=s1 readonly=1 <?php fillValues($graphtexthexcolor, $graphtextcolor); ?> >
                    <a class=s1 href="javascript:openColorPicker('colorpicker.php?', 'graphtextcolor');COLOR_PICKER.focus();" title="color picker"><img src="images/choose.gif" border=0 alt="@"></a></font></td>
                </tr>
                <tr>
                  <td><font color="#993366">Border</font></td>
                  <td><font color="#993366">
                    <input type="text" name="txborder" size="2" value="<?php echo $graphborder?>" class=s1>
                    </font></td>
                </tr>
                <tr>
                  <td><font color="#993366">Type</font></td>
                  <td><font color="#993366">
                    <select name="gtype" class=s1>
                      <option value="LINE">LINE</option>
                      <option value="BAR">BAR</option>
                    </select>
                    </font></td>
                </tr>
              </table>
            <td></tr>
            <tr><td colspan=2><div align=center style="color:#006699;font-size:7pt;"><i><?php echo $msg?></i></div></td></tr>
          <tr>
            <td colspan="2"><div align="center"> <font color="#993366">
                <input type="hidden" name="action" value="">
                <input type="hidden" name="defgtype" value="<?php echo $gtype?>">
                <input type="hidden" name="showadvance" value="<?php echo $showadvance;?>">
                <input type="button" name="Refresh" value="Refresh" onclick="javascript:openGraph('statistics_monthly.php?month=<?php echo $currentmonth?>&year=<?php echo $currentyear?>');CURRENT_WINDOW.focus();">
                <input type="reset" name="Reset" value="Reset" onclick="operationSettings('LOAD');">
                </font></div></td>
          </tr>
        </table>
	  </form>

    </td>

  <td align=center width=60%>

<table border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
    <td width="10%"><a href="?month=<?php echo $lastMonth?>&year=<?php echo $lastYear?>" title="Prev. month"><img src="images/previousdown.gif" border=0></a></td>
    <td width="80%">

        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>

          <td width="40%"><div align="center"><a href="main.php" title="Main" class=s1><font size="-2">Main</font></a>&nbsp;|&nbsp;<a href="?" title="Current" class=s1><font size="-2">Current</font></a>

            &nbsp;|&nbsp;<a title="log off" class=s1 href="logout.php">log
			off</a></div></td>
                <td width="20%" bgcolor="#FFFFFF">
                  <div align=center style="font-size:18pt"><?php echo $currentyear?></div></td>
                  <td width="40%" align="right" valign="center"><a href="javascript:openNewWindow('stock_add.php','500','500');" title="Edit Stocks" class=s1><font size="-2">Edit Stocks</font></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="javascript:openNewWindow('calculator_new.html','350','400');" title="Financial Calculator" class=s1><font size="-2">Financial Calculator</font></a> </td>
          </tr>
        </table>

      </td>
    <td width="10%"><div align="right"><a href="?month=<?php echo $nextMonth?>&year=<?php echo $nextYear?>" title="Next month"><img src="images/nextdown.gif" border=0></a></div></td>
  </tr>
  <tr>
    <td colspan=3>
	<p align="center"><b>Stocks</b></td>
  </tr>
  <tr>
    <td colspan="3"><table border=1 align="center" cellpadding=1 cellspacing=0 bgcolor="#FFFF99" style="border-collapse: collapse" bordercolor="#cccccc">
        <tr>
          <td colspan=7 bgcolor="#52637B"><div align=center style="color:#FFCC00;font-size:18pt"><?php echo $monthname?></div></td>
        </tr>
        <TR align=center BGCOLOR="#CCCCCC">
          <TD><div align=center class=t2 style="color:#FF0033;font-size:12pt"><b>Sun</b></div></TD>
          <TD><div align=center class=t2 style="color:#000000;font-size:12pt"><b>Mon</b></div></TD>
          <TD><div align=center class=t2 style="color:#000000;font-size:12pt"><b>Tue</b></div></TD>
          <TD><div align=center class=t2 style="color:#000000;font-size:12pt"><b>Wed</b></div></TD>
          <TD><div align=center class=t2 style="color:#000000;font-size:12pt"><b>Thu</b></div></TD>
          <TD><div align=center class=t2 style="color:#000000;font-size:12pt"><b>Fri</b></div></TD>
          <TD><div align=center class=t2 style="color:#FF0033;font-size:12pt"><b>Sat</b></div></TD>
        </TR>
        <?php
     require('constants.php');

     $num = 1;
     $newrow = 1;
     $numofcell = 0;
     $rowcount = 0;
     $totalamount = 0;

     while ($num <= $totaldays)
     {
		  if ( ($numofcell % ROW_MAX == 0) || ($numofcell == 0) ) {
		     echo '<tr>';
		     $rowcount=0;
		     }
		  else
		     $rowcount++;

          if ( ($num == $currentday) && ($highlight_today) ) $bgcolor = "#ffcc66"; else $bgcolor="#ffff99";
		  if ($numofcell<$firstday_dayofweek)
			echo $BLANK_CELL;
		  else {
		    $datestr = $currentyear.'-'.$currentmonth.'-'.$num;
		    $window = "javascript:openNewWindow('stock_transaction_add.php?date=$datestr','700','750');";


			/////////////////////////////
			// Add Total Amount
			///////////////////////////////
			$sql = "select distinct(a.stock_name) as 'str1' from stocks a, stocks_transaction b
			       where b.UserID = $userid
			       and a.stock_id = b.stock_id
                   and b.buy_date = '$datestr'";

             //echo $sql;
			if ( !($result = $db->sql_query($sql)) )
				  echo "SQL error.";

			$stock_name = "";
			if( $row = $db->sql_fetchrow($result) )
			 {
			 do
			 {
				  if ($stock_name=="")
				  	$stock_name = $row['str1'];
				  else
				    $stock_name = $stock_name.'<br>'.$row['str1'];

		     }
		    while ( $row = $db->sql_fetchrow($result) );
		    $db->sql_freeresult($result);
		    }

		    if (strlen($stock_name) !=0) $stock_name = '<span style="color:#996666;font-size:7pt;font-family:arial;">'.$stock_name.'</span>';
		    else $stock_name = '&nbsp;';

			echo '<td bgcolor="'.$bgcolor.'"><div align=center class=t3><table cellpadding=0 cellspacing=0><tr><td align=center>'.($num++).'</td></tr>
			      <tr><td align=center><a href="'.$window.
			      '" title="Input"><img src="images/new.gif" border=0></a></td></tr>
			      <tr><td align=left>'.$stock_name.'</td></tr>
			      </table></div></td>';

		  }

		  if ($rowcount == (ROW_MAX-1)) echo '</tr>';
		  $numofcell++;
	 }

    ///////////////
    // Finished up
    ///////////////
    //echo '<tr><td>'.$rowcount.'</td></tr>';
    for ($i=($rowcount+1); $i<= (ROW_MAX-1); $i++) {
       echo $BLANK_CELL;
       if ($i == (ROW_MAX-1) ) echo '</tr>';
    }

 ?>
      </table></td>
  </tr>
</table>


</td>

    <td align="left" width="20%">
	  <div class="t1">
	    <table width="80%"  cellspacing="0" cellpadding="0" border="1" style="border-collapse:collapse">
          <tr bgcolor="#9999CC">
            <td colspan="2"> <div align="center"><font color="#FFFFCC">Monthly
                Summary </font></div></td>
          </tr>
          <tr bgcolor="#FFFFCC">
            <td><b><font color="#993366">Profit/Loss</font></b></td>
            <td><font color="#993366"><b>$<span id="totalamount"></span></b></font></td>
          </tr>

	  <?php
			$sql = "select a.stock_name, SUM(b.profit) 'profit' from stocks a, stocks_transaction b
			        where b.UserID = $userid
			        and a.stock_id = b.stock_id
					and month(b.buy_date) = $currentmonth
					and year(b.buy_date) = $currentyear
					and b.status = 'CLOSED'
					group by a.stock_name
					order by a.stock_name";

			if ( !($result = $db->sql_query($sql)) ) {
			  echo "SQL error.";
			}

			 //echo $sql;
		   $totalamount = 0.0;
		   if ( $row = $db->sql_fetchrow($result) )
			 {
				do
				{
				 $name = $row['stock_name'];
		 		 $profit = $row['profit'];
		 		 $value = sprintf("%01.2f", $profit);
                 $totalamount += $value;
	   		 	 echo '<tr><td><font color=#993366>'.$name.'</font></td><td><font color=#993366> $'.$value.'</font></td></tr>';
	      	}
	      	while ( $row = $db->sql_fetchrow($result) );

	        $db->sql_freeresult($result);
	       }
    ?>
        </table>
<br>

  <table width="80%"  cellspacing="0" cellpadding="0" border="1" style="border-collapse:collapse">
          <tr bgcolor="#9999CC">
            <td colspan="4"> <div align="center"><font color="#FFFFCC">Outstanding Position</font></div></td>
          </tr>

          <tr bgcolor="#FFFFCC">
            <td><b><font color="#993366">Stock</font></b></td>
            <td><b><font color="#993366">Type</font></b></td>
            <td><b><font color="#993366">Buy Date</font></b></td>
            <td><b><font color="#993366">Amt</font></b></td>
          </tr>

	  <?php
			$sql = "select a.stock_name, b.payment_medium, b.buy_total, b.buy_date
					from stocks a, stocks_transaction b
			        where b.UserID = $userid
			        and a.stock_id = b.stock_id
					and month(b.buy_date) = $currentmonth
					and year(b.buy_date) = $currentyear
					and b.status <> 'CLOSED'
					order by a.stock_name";

			if ( !($result = $db->sql_query($sql)) ) {
			  echo "SQL error.";
			}

			 //echo $sql;
		   //$totalamount = 0.0;
		   if ( $row = $db->sql_fetchrow($result) )
			 {
				do
				{
				 $name = $row['stock_name'];
				 $payment_medium = $row['payment_medium'];
		 		 $buy_total = $row['buy_total'];
		 		 $buy_date = $row['buy_date'];
		 		 $buy_date = date('Y-m-d', strtotime($buy_date));
		 		 $value = sprintf("%01.2f", $buy_total);
           //      $totalamount += $value;
	   		 	 echo '<tr><td><font color=#993366>'.$name.'</font></td><td><font color=#993366>'.$payment_medium.'</font></td><td><font color=#993366>'.$buy_date.'</font></td><td><font color=#993366>$'.$value.'</font></td></tr>';
	      	}
	      	while ( $row = $db->sql_fetchrow($result) );

	        $db->sql_freeresult($result);
	       }
    ?>
    </table>
<br>

<table width="80%"  cellspacing="0" cellpadding="0" border="1" style="border-collapse:collapse">
 <tr bgcolor="#9999CC">
     <td> <div align="center"><font color="#FFFFCC">Transaction Volume</font></div></td>
 </tr>
 <tr><td align='center'>


  <?php
 			$sql = "select b.quantity
 					from stocks a, stocks_transaction b
 			        where b.UserID = $userid
 			        and a.stock_id = b.stock_id
 					and month(b.buy_date) = $currentmonth
 					and year(b.buy_date) = $currentyear
 					and b.status = 'CLOSED'";

 			if ( !($result = $db->sql_query($sql)) ) {
 			  echo "SQL error.";
 			}

 //echo $sql;
 		   $total = 0.0;
 		   if ( $row = $db->sql_fetchrow($result) )
			 {
				do
				{
				 $quantity = $row['quantity'];
				 $total +=$quantity*2;

	      	}
	      	while ( $row = $db->sql_fetchrow($result) );

	        $db->sql_freeresult($result);
	       }

	       echo '<font color=#993366>'.$total.'</font>';
?>



 </td></tr>
 </table>


	</div>
    </td>
</tr></table>

 <script language="javascript">
  <!--
	writeTotalAmount(<?php echo $totalamount?>);
  -->
  </script>

</body>
<?php
$db->sql_close();
require('footer.inc')
?>