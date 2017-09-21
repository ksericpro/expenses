<?php
	require_once('user_auth_fns.php');
	check_valid_user();
?>
<?php
include_once('constants.php');
require_once('graph_fns.php');
include_once('db.php');


$userid = $_SESSION['userid'];
$date = $_REQUEST['date'];

$sql = "select c.Name, SUM(e.Amount) as 'amt' from expenses e, category c where
	    c.CategoryID = e.CategoryID and c.UserID = $userid
	    and e.ExpensesDate = '$date'
	    group by c.Name
	    order by c.Name";

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


//foreach ($arraydata as $key=>$value)
//echo '<br>'.$key.'=>'.$value;
$title = 'Summary Expenses of Day '.$date;
createBar($arraydata, $title);
}
else echo '<center>Nothing to display.<br>Click <a href="javascript:this.self.close()">here </a>to exit</center>';
$db->sql_close();
?>