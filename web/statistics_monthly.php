<?php
	require_once('user_auth_fns.php');
	check_valid_user();
?>
<?php
include_once('graph_fns.php');

$userid = $_SESSION['userid'];
$month = $_REQUEST['month'];
$year = $_REQUEST['year'];
$lineorbar = $_REQUEST['lineorbar'];
$graphtype = $_REQUEST['graphtype'];


if ($lineorbar=='LINE') {
	//////////////////
	// LINE
	//////////////////
	$height = $_REQUEST['height'];;
	$width = $_REQUEST['width'];;
	$border = $_REQUEST['border'];;
	$numofypoints = $_REQUEST['ypoints'];;

	$graphbkcolor = $_REQUEST['graphbkcolor'];
	$graphlinecolor = $_REQUEST['graphlinecolor'];
	$graphaxiscolor = $_REQUEST['graphaxiscolor'];
	$graphtextcolor = $_REQUEST['graphtextcolor'];

	// sql
	switch($graphtype) {
	case 'monthly' :
		$sql = "select day(ExpensesDate) as 'no', SUM(Amount) as 'amt' from expenses
				where UserID = $userid
				and month(ExpensesDate) = '$month'
				and year(ExpensesDate) = '$year'
				group by day(ExpensesDate)";
		$ytitle = 'Date';
		$xtitle = '$';
		$date = mktime(0,0,0, $month, 1, $year);
		$monthname = date('M', $date);
		$totaldays = date('t', $date);
		$maintitle = $monthname.' '.$year;
		break;

	case 'yearly' :
		$sql = "select month(ExpensesDate) as 'no', SUM(Amount) as 'amt' from expenses
				where UserID = $userid
				and year(ExpensesDate) = '$year'
				group by month(ExpensesDate)";
		$ytitle = 'Month';
		$xtitle = '$';
		$maintitle = $year;
		$totaldays = 12;
		break;
	}

	createGraph($month, $year, $totaldays, $sql, $height, $width,
				$border, $numofypoints, $ytitle , $xtitle,
				$maintitle,
				$graphbkcolor, $graphlinecolor,
				$graphaxiscolor, $graphtextcolor);

} else {
    //////////////////////
    // BAR
    /////////////////////
    include_once('db.php');

	$userid = $_SESSION['userid'];
    $currentyear = date('Y');

	switch($graphtype) {
	case 'monthly' :

        $date = mktime(0,0,0, $month, 1, $year);
		$monthname = date('M', $date);

		$title = 'Summary Expenses of Month '.$monthname.' '.$year;

		$sql = "select c.Name, SUM(e.Amount) as 'amt' from expenses e, category c where
		  	    c.CategoryID = e.CategoryID and c.UserID = $userid
			   and month(e.ExpensesDate) = $month
			   group by c.Name
			   order by c.Name";
		break;

	case 'yearly' :
		$sql = "select c.Name, SUM(e.Amount) as 'amt' from expenses e, category c where
		  	    c.CategoryID = e.CategoryID and c.UserID = $userid
			    and year(e.ExpensesDate) = $currentyear
			    group by c.Name
			    order by c.Name";

		$title = 'Summary Expenses of Year '.$year;
		break;
	}

	if ( !($result = $db->sql_query($sql)) )
		  die( "SQL error.");

	//echo $sql;
	$ct = 0;
	if ( $row = $db->sql_fetchrow($result) )
	 {
		do
		{
		 $name = $row['Name'];
		 $amount = $row['amt'];
		 $value = sprintf("%01.2f", $amount);
		 if ($ct==0)
			$arraydata = array($name=>$value);
		 else
			$arraydata[$name] = $value;
		 $ct++;
	}
	while ( $row = $db->sql_fetchrow($result) );

	$db->sql_freeresult($result);

	createBar($arraydata, $title);
	}
	else echo '<center>Nothing to display.<br>Click <a href="javascript:this.self.close()">here </a>to exit</center>';
	$db->sql_close();
}
?>