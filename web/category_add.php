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
         $remarks = $_POST['txRemarks'];
         $accountno = $_POST['txAccountNo'];
         if (!get_magic_quotes_gpc()) {
            $name = addslashes($name);
            $remarks = addslashes($remarks);
            $accountno = addslashes($accountno);
         }

         //echo '<br>'.$name.', '.$remarks;

         include_once('db.php');

         $datetime = date('Y-m-d H:i:s');
         $sql = "insert into category(Name, Remarks, AccountNo, UserID, ModifiedDate)
                 values('".$name."', '".$remarks."', '".$accountno."', ".
                 $_SESSION['userid'].", '".$datetime."')";

         //echo '<br>'.$sql;

         if( !$db->sql_query($sql) )
		    $msg = 'SQL error.';
		 else
		    $msg = 'Category \''.$name. '\' Successfully Added.';

         $db->sql_close();
      }
?>
<html>
<head><title>Expenses - Category</title></head>
<link rel="stylesheet" href="style.css">
<script language="javascript">
<!--
   function check() {
      if (document.frm.txName.value=="") {
          alert("Name cannot be empty!");
          document.frm.txName.focus();
          return false;
      }
      return true;
   }

   function init() {
   	  opener.window.location.reload();
   }
-->
</script>
<body onload="init()">
<table width="90%" border="1" align="center" cellpadding="5" cellspacing="0" bgcolor="#B0C4DE" style="border-collapse:collapse">
  <tr bgcolor="#52637B"><td>
<div align="center"><strong><font color="#FFCC00" size="3">CATEGORY Add</font></strong></div>
  </td></tr>
  <tr>
    <td><div align="right" style="margin-top:5pt;margin-bottom:5pt"><A href="javascript:this.self.close();" title="Close" class="s1">&gt;Close&lt;</A>
      </div>
      <form name="frm" method="post" action="" onsubmit="return check();">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" >
          <tr>
            <td colspan=2><div align="left" style="margin-bottom:2pt"><font color="#006699"><?php echo $msg?></font></div></td>
          </tr>
          <tr>
            <td width="40%">Name</td>
            <td width="60%"><input type="text" name="txName" size="48"></td>
          </tr>
          <tr>
            <td valign=top>Account No.</td>
            <td><input type="text" name="txAccountNo" size="48"></td>
          </tr>
          <tr>
            <td valign=top>Remarks&nbsp;</td>
            <td><textarea name="txRemarks" cols="47" rows="3"></textarea></td>
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