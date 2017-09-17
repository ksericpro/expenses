<?php
	require_once('user_auth_fns.php');
	check_valid_user();
?>
<?php require('header.inc')?>
<?php
      $msg = '';
	  $id = $_REQUEST['id'];
	  $act = $_REQUEST['action'];

      include_once('db.php');

      if ($act == "DELETE") {

      	$sql = "delete from category where CategoryID = $id";
      	//echo $sql;
		if( !$db->sql_query($sql) )
		    $msg = 'SQL error.';
	    else
			$msg = 'Category '.$id.' Successfully Deleted.';
      }
?>
<html>
<head><title>Expenses - Category</title></head>
<link rel="stylesheet" href="style.css">
<SCRIPT language="JavaScript" src="functions.js"></SCRIPT>

<body>
<table width="60%" border="1" align="center" cellpadding="5" cellspacing="0" bgcolor="#B0C4DE" style="border-collapse:collapse">
  <tr bgcolor="#52637B">
    <td bgcolor="#52637B"> <div align="center"><font color="#FFCC00" size="3"><strong>CATEGORY
        List</strong></font> </div></td>
  </tr>
  <tr>
    <td>
	  <div> <a href="main.php" title="main menu" class="s1">Main Menu</a> | <a href="javascript:openNewWindow('category_add.php','500','300');CURRENT_WINDOW.focus();" title="add" class="s1">Add</a>
        | <a href="javascript:openNewWindow('category_add.php','500','300');CURRENT_WINDOW.focus();" title="Budget Planning" class="s1">Budget Planning</a> | <a href="logout.php" title="log off" class="s1">Log Off</a></div>
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
                  <td>
                    <div align="center"><font color="#6699CC">Name</font></div></td>
                  <td>
                    <div align="center"><font color="#6699CC">Account No.</font></div></td>
                  <td>
                    <div align="center"><font color="#6699CC">Remarks</font></div></td>
                  <td>
                    <div align="center"><font color="#6699CC">Last Modified</font></div></td>
                  <td>
                    <div align="center"><font color="#6699CC">A1</font></div></td>
                  <td>
                    <div align="center"><font color="#6699CC">A2</font></div></td>
                </tr>

                <?php

				 $sql = 'select * from category where userid = '.$_SESSION['userid']." order by Name";

				 if( !($result = $db->sql_query($sql)) )
				    echo 'SQL Error';

				 $i = 0;
				 if ( $row = $db->sql_fetchrow($result) )
				 {
				 	do
				 	{
				 		$name = $row['Name'];
				 		$remarks = $row['Remarks'];
				 		$accountno = $row['AccountNo'];
				 		$mdate = $row['ModifiedDate'];
				 	    $i++;
                ?>
                <tr bgcolor="#FFFFCC">
                  <td><div class="t1"><?php echo $name?></div></td>
                  <td><div class="t1"><?php echo $accountno?></div></td>
                  <td><div class="t1"><?php echo $remarks?></div></td>
                  <td><div class="t1"><?php echo $mdate?></div></td>
                  <td><div align="center"><a href="javascript:openNewWindow('category_edit.php?id=<?php echo $row[CategoryID]?>','500','330');CURRENT_WINDOW.focus();" title="edit" class="s1">Edit</a></div></td>
                  <td><div align="center">
                  <a class='s1' title='Delete Record' href='javascript:ConfirmDeleteA("?", "<?php echo $row[CategoryID]?>");' >delete</a></div></td>
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
        </table>
	  </form>

</td>
  </tr>
</table>
<?php require('footer.inc')?>
</body>
</html>
