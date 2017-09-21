<?php
	require_once('user_auth_fns.php');
	check_valid_user();
?>
<?php require('header.inc')?>
<?php
      $msg = '';
      $act = $_POST['act'];
      if ($act == 'SAVE') {
         //echo 'Saving....';
         $name = $_POST['txName'];
         $loginid = $_POST['txLoginID'];
         $password = $_POST['txPassword'];
         $role = $_POST['role'];
         if (!get_magic_quotes_gpc()) {
            $name = addslashes($name);
            $loginid = addslashes($loginid);
            $password = addslashes($password);
            $role = addslashes($role);
         }

         include_once('db_new.php');

         $mdate = date('Y-m-d H:i:s');
         $sql = "insert into user(Name, LoginID, Password , Role, ModifiedDate)
                 values('".$name."', '".$loginid."', sha1('".$password."'), '".$role."', '".$mdate."')";

         //echo '<br>'.$sql;
		 if ($con->query($sql) === TRUE) {
			$msg = "Record added successfully";
		} else {
			 $msg = 'SQL error. Probably due to duplicate records.';
		}

        /* if( !$db->sql_query($sql) )
		    $msg = 'SQL error. Probably due to duplicate records.';*/

		 $sql = "SELECT LAST_INSERT_ID() as 'id'";
		 $result = $con->query($sql);

		 if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				$insertedid = $row["id"];
			}
		 } 
		 
		 //$insertedid = $db->sql_nextid();
		 if ($insertedid != 0)
		 	$sql = "insert into settings(UserID, ModifiedDate) values($insertedid, '$mdate')";

	     if ($con->query($sql) === TRUE) {
			$msg = 'User \''.$name. '\' & his/her Settings Successfully Added.'
		} else {
			$msg = 'SQL Error';
		}
		
		/* if( !$db->sql_query($sql) )
		    $msg = 'SQL error.';
		 else
		    $msg = 'User \''.$name. '\' & his/her Settings Successfully Added.';
*/
		 //echo '<br>'.$insertedid;
		  $con->close();
         //$db->sql_close();
      }
?>
<html>
<head><title>Expenses - User</title></head>
<link rel="stylesheet" href="style.css">
<script language="javascript">
<!--
   var MIN_LENGTH = 3;
   function check() {
      if (document.frm.txName.value=="") {
          alert("Name cannot be empty!");
          document.frm.txName.focus();
          return false;
      }
      if (document.frm.txLoginID.value=="") {
		  alert("Login ID cannot be empty!");
		  document.frm.txLoginID.focus();
		  return false;
      }
      if (document.frm.txPassword.value.length <= MIN_LENGTH) {
          alert("Password must be greater than " + MIN_LENGTH + " !");
          document.frm.txPassword.focus();
          return false;
      } else if (document.frm.txPassword.value!=document.frm.txPassword2.value) {
	      alert("Passwords do not match!");
	      document.frm.txPassword2.focus();
	      return false;
      }

      if (document.frm.role.value=="") {
		  alert("Role cannot be empty!");
		  return false;
      }
      return true;
   }

   var CHANGEP = true;

   function init() {
     opener.window.location.reload();
   	 var act = document.frm.act.value;
   	 if (act == "LOAD") CHANGEP = false;
   	 else document.frm.changepassword.checked = 1;
   	 document.frm.txPassword.disabled = !CHANGEP;
     document.frm.txPassword2.disabled = !CHANGEP;
   }

    function passwordchange() {
      CHANGEP = !CHANGEP;
      document.frm.txPassword.disabled = !CHANGEP;
      document.frm.txPassword2.disabled = !CHANGEP;
   }
-->
</script>
<body onload="init()">
<table width="90%" border="1" align="center" cellpadding="5" cellspacing="0" bgcolor="#B0C4DE" style="border-collapse:collapse">
<tr bgcolor="#52637B"><td>
<div align="center"><strong><font color="#FFCC00" size="3">USER Add</font></strong></div>
  </td></tr>
  <tr>
    <td>
<div align="right" style="margin-top:5pt;margin-bottom:5pt"><A href="javascript:this.self.close();" title="Close" class="s1">&gt;Close&lt;</A>
      </div>

	<form name="frm" method="post" action="" onsubmit="return check();">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan=2><div align="left" style="margin-bottom:2pt"><font color="#006699"><?php echo $msg?></font></div></td>
          </tr>
          <tr>
            <td width="25%">Name</td>
            <td width="75%"><input type="text" name="txName" size="50"></td>
          </tr>
          <tr>
            <td valign=top>Login ID</td>
            <td><input type="text" name="txLoginID" size="50"></td>
          </tr>

          <tr>
            <td valign=top>Password</td>
            <td><input type="password" name="txPassword" size="50"></td>
          </tr>
          <tr>
            <td valign=top>Retype Password&nbsp;</td>
            <td valign="top"><input type="password" name="txPassword2" size="50"></td>
          </tr>
          <tr>
            <td valign=top>Role</td>
            <td valign="top"><select name="role">
                <option value=""></option>
                <option value="NORMAL">NORMAL</option>
                <option value="SUPER USER">SUPER USER</option>
              </select></td>
          </tr>
          <tr>
            <td colspan="2"> <div align="center">
                <input type="submit" name="Submit2" value="Submit" onclick="javascript:document.frm.act.value='SAVE';">
                <input type="reset" name="Reset" value="Reset" onclick="javascript:document.frm.submit();">
                <input type="hidden" name="act" value="">
              </div></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
	  </form>

</td>
  </tr>
</table>
<?php require('footer.inc')?>
</body>
</html>