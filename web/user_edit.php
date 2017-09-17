<?php
	require_once('user_auth_fns.php');
	check_valid_user();
?>
<?php require('header.inc')?>
<?php
      $msg = '';
      $id = $_REQUEST['id'];
      $act = $_POST['act'];

      include_once('db.php');

      if ($act == "EDIT") {
      	 $name = $_POST['txName'];
		 $loginid = $_POST['txLoginID'];
		 $epassword = $_POST['epassword'];
		 $changepassword = $_POST['changepassword'];
         $role = $_POST['role'];
	     if (!get_magic_quotes_gpc()) {
	        $name = addslashes($name);
	        $loginid = addslashes($loginid);
	        $password = addslashes($password);
            $role = addslashes($role);
         }
         $mdate = date('Y-m-d H:i:s');
         $sql = "update user set Name = '$name', LoginID = '$loginid',
                 Role = '$role', ModifiedDate = '$mdate' ";

         if ($changepassword=="1")
       	   $sql = $sql.", Password=sha1('$epassword')";

         $sql = $sql." where UserID = $id";

         //echo $sql;
         if( !$db->sql_query($sql) )
		    $msg = 'SQL error.';
		 else
		    $msg = 'User \''.$name. '\' Successfully Updated.';
      }

      else {

		  $sql = "select * from user WHERE UserID = $id";
		  if ( !($result = $db->sql_query($sql)) )
			  $msg = "SQL error.";

		  if( $row = $db->sql_fetchrow($result) )
		  {
			  $name = $row['Name'];
			  $loginid = $row['LoginID'];
			  $role = $row['Role'];
			  $password = $row['Password'];
			  $mdate = $row['ModifiedDate'];

			  $db->sql_freeresult($result);
			  $msg = 'Record Successfully Loaded.';
		  }

	  }

      $db->sql_close();
?>
<html>
<head><title>Expenses - Category</title></head>
<link rel="stylesheet" href="style.css">
<SCRIPT language="JavaScript" src="functions.js"></SCRIPT>
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

      if (document.frm.changepassword.checked)
      if (document.frm.epassword.value.length <= MIN_LENGTH) {
          alert("Password must be greater than " + MIN_LENGTH + " !");
          document.frm.epassword.focus();
          return false;
      } else if (document.frm.epassword.value!=document.frm.repassword.value) {
	      alert("Passwords do not match!");
	      document.frm.repassword.focus();
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
   	 setDefault("document.frm.drole", "document.frm.role");
   	 var act = document.frm.act.value;
   	 if (act == "LOAD") CHANGEP = false;
   	 else document.frm.changepassword.checked = 1;
   	 document.frm.epassword.disabled = !CHANGEP;
     document.frm.repassword.disabled = !CHANGEP;
   }

    function passwordchange() {
      CHANGEP = !CHANGEP;
      document.frm.epassword.disabled = !CHANGEP;
      document.frm.repassword.disabled = !CHANGEP;
   }
-->
</script>
<body onload="init()">
<table width="90%" border="1" align="center" cellpadding="5" cellspacing="0" bgcolor="#B0C4DE" style="border-collapse:collapse">
<tr bgcolor="#52637B"><td>
<div align="center"><strong><font color="#FFCC00" size="3">USER Edit</font></strong></div>
  </td></tr>
  <tr>
    <td>
       <div align="right" style="margin-top:5pt;margin-bottom:5pt"><A href="javascript:this.self.close();" title="Close" class="s1">&gt;Close&lt;</A>
      </div>
	<form name="frm" method="post" action="" onsubmit="return check();">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
            <td colspan=2><div align="left" style="margin-bottom:2pt"><font color="#006699"><?php echo $msg?></font></div></font></td>
          </tr>
          <tr>
            <td width="25%">Name</td>
            <td width="75%"><input type="text" name="txName" size="50" value="<?php echo $name ?>"></td>
          </tr>
          <tr>
            <td valign=top>Login ID</td>
            <td><input type="text" name="txLoginID" size="50" value="<?php echo $loginid?>"></td>
          </tr>
          <tr>
            <td valign=top colspan="2"><i><input type="checkbox" name="changepassword" value="1" onclick="passwordchange()">change
						password</i></td>
          </tr>
          <tr>
            <td valign=top>Password</td>
            <td><input type="password" name="epassword" size="50" value=""></td>
          </tr>
          <tr>

            <td valign=top>Retype Password&nbsp;</td>
		     <td valign="top"><input type="password" name="repassword" size="50"></td>
          </tr>
          <tr>
            <td valign=top>Role</td>
            <td><select name="role">
                <option value=""></option>
                <option value="NORMAL">NORMAL</option>
                <?php checkUser("<option value='SUPER USER'>SUPER USER</option>"); ?>
              </select></td>
          </tr>
          <tr>
            <td colspan="2"><div style="color:#993366;font-size:7pt;"><i>Last Modified Date : <?php echo $mdate?></i></div></td>
          </tr>
          <tr>
            <td colspan="2"> <div align="center">
                <input type="submit" name="Submit2" value="Submit" onclick="javascript:document.frm.act.value='EDIT';">
                <input type="reset" name="Reset" value="Reset" onclick="javascript:document.frm.submit();">
                <input type="hidden" name="act" value="LOAD">
                <input type="hidden" name="drole" value="<?php echo $role?>">
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