<?php
	require_once('user_auth_fns.php');
	check_valid_user();
	include_once('constants.php');
?>
<?php require('header.inc')?>
<?php
      $msg = '';
	  $id = $_REQUEST['id'];
	  $act = $_REQUEST['action'];

      include_once('db_new.php');

      if ($act == "DELETE") {

      	$sql = "delete from user where UserID = $id";
      	//echo $sql;
		//if( !$db->sql_query($sql) )
		 //   $msg = 'SQL error.';
		if ($con->query($sql) === TRUE) {
			$msg = 'User Successfully Deleted.';
		 } else {
			$msg = "Error deleting record: " . $con->error;
		 }

		$sql = "delete from settings where UserID = $id";

		if ($con->query($sql) === TRUE) {
			$msg = 'Setting \''.$id. '\' Successfully Deleted.';
		 } else {
			$msg = "Error deleting record: " . $con->error;
		 }
		//if( !$db->sql_query($sql) )
		//    $msg = 'SQL error.';

		$sql = "delete from expenses where UserID = $id";
		
		if ($con->query($sql) === TRUE) {
			$msg = 'User '.$id.' , his/her Settings & Expenses Successfully Deleted.';
		 } else {
			$msg = "Error deleting record: " . $con->error;
		 }
		/*if( !$db->sql_query($sql) )
		    $msg = 'SQL error.';
		else
		    $msg = 'User '.$id.' , his/her Settings & Expenses Successfully Deleted.';
*/
      }
?>
<html>
<head><title>Expenses - User</title></head>
<link rel="stylesheet" href="style.css">
<SCRIPT language="JavaScript" src="functions.js"></SCRIPT>

<body>
<table width="60%" border="1" align="center" cellpadding="5" cellspacing="0" bgcolor="#B0C4DE" style="border-collapse:collapse">
  <tr bgcolor="#52637B">
    <td bgcolor="#52637B"> <div align="center"><font color="#FFCC00" size="3"><strong>USER
        List</strong></font> </div></td>
  </tr>
  <tr>
    <td>
	  <div> <a href="main.php" title="main menu" class="s1">Main Menu</a> | <a <?php checkUser("javascript:openNewWindow('user_add.php','500','340');CURRENT_WINDOW.focus()"); ?> title="add" class="s1">Add</a>
        | <a href="logout.php" title="log off" class="s1">Log Off</a></div>
		<br>

	<form name="frm" method="post" action="">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan=2><div align="left" style="margin-bottom:2pt"><font color="#006699"><?php echo $msg?></font></div></td>
          </tr>
          <tr>
            <td colspan="2">

			<table width="100%" border="1" cellspacing="0" cellpadding="0" style="border-collapse:collapse" bordercolor="#bbbbbb">
                <tr bgcolor="#666666">
                  <td width="15%"> <div align="center"><font color="#6699CC">Name</font></div></td>
                  <td width="13%"> <div align="center"><font color="#6699CC">Login ID</font></div></td>
                  <td width="22%"> <div align="center"><font color="#6699CC">Role</font></div></td>
                  <td width="23%"> <div align="center"><font color="#6699CC">Last Modified</font></div></td>
                  <td width="5%"> <div align="center"><font color="#6699CC">A1</font></div></td>
                  <td width="22%"> <div align="center"><font color="#6699CC">A2</font></div></td>
                </tr>

                <?php

				$sql = 'select * from user';
				if ($_SESSION['role'] != SUPERUSER)
				   $sql = $sql.' where userid = '.$_SESSION['userid'].' order by Name';

				//echo $sql;
				$result = $con->query($sql);
				$i = 0;
				if ($result->num_rows > 0) {
					// output data of each row
					while($row = $result->fetch_assoc()) {
						$name = $row['Name'];
				 		$loginid = $row['LoginID'];
				 		$role = $row['Role'];
				 		$mdate = $row['ModifiedDate'];
				 	    $i++;
					//} //end while
				//} 

				 /*if( !($result = $db->sql_query($sql)) )
				    echo 'SQL Error';

                 $i = 0;
				 if ( $row = $db->sql_fetchrow($result) )
				 {
				 	do
				 	{
				 		$name = $row['Name'];
				 		$loginid = $row['LoginID'];
				 		$role = $row['Role'];
				 		$mdate = $row['ModifiedDate'];
				 	    $i++;*/
                ?>
                <tr bgcolor="#FFFFCC">
                  <td>
				  <div class="t1"><?php echo $name?></div></td>
                  <td><div class="t1"><?php echo $loginid?></div></td>
                  <td><div class="t1"><?php echo $role?></div></td>
                  <td><div class="t1"><?php echo $mdate?></div></td>
                  <td><div align="center"><a href="javascript:openNewWindow('user_edit.php?id=<?php echo $row[UserID]?>','500','370');CURRENT_WINDOW.focus();" title="edit" class="s1">Edit</a></div></td>
                  <td><div align="center">
                  <a class='s1' title='Delete Record' <?php checkUser("javascript:ConfirmDeleteA('?','$row[UserID]')"); ?> >delete</a></div></td>
                </tr>

                <?php

                	} //end while
				} 

                //$db->sql_close();
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
<?php 
$con->close();
require('footer.inc')
?>
</body>
</html>
