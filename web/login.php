<?php

   $act = $_POST['act'];


   $msg = '<br>';
   if ($act == "LOGIN") {
		include_once('constants.php');

		$userid = $_POST['userid'];
		$password = $_POST['password'];

        require_once('user_auth_fns.php');
		$loginstr = login($userid, $password);
		//echo "loginstr=".$loginstr;
		if ( $loginstr != null ) {
		  $msg = 'Success.';

		  $login_array = explode('@', $loginstr);
		  reset($login_array);

		  session_start();
	      session_unset();
		  $ct = 0;
		  foreach ($login_array as $current) {
		     switch($ct) {
		        case 0: $_SESSION['userid'] = $current; break;
		        case 1: $_SESSION['valid_user'] = $current; break;
		        case 2: $_SESSION['role'] = $current; break;
		        case 3: $_SESSION['settingsid'] = $current; break;
		     }
		     $ct++;
		  }
          header($MAINDIR.'/main.php');
		  }
		else {
		  $msg = 'Could not log you in.';
		  //unset($_SESSION['valid_user']);
		  session_unset();
		  if (isset($_SESSION['valid_user'])) session_destroy();
		  }
	}
?>
<html>
<head>
<title>Personal PLANNER</title>

<LINK href="./style.css" type="text/css" rel="stylesheet">

</head>
<body bgcolor="#F0F0F0" vlink="#48576C" link="#48576C" alink="#000000" >
<div align="center"><br>
  <br>
  <br>
  <br>
  <br>

  <div align="center" style="color:#006699;font-size:8pt;font-family:verdana;"><?php echo $msg?></div>

<form action="login.php" Method="Post" name="frm">
  <table border="0" cellpadding="0" cellspacing="0" align="center">
<tr>
<td bgcolor="#000000">
<table border="0" cellpadding="2" cellspacing="1" align="center">
<tr>
<td bgcolor="#52637B" align="center"><font face="Verdana" size="3" color="#FFCC00"><b>Personal PLANNER</b></font></td>
</tr>
<tr>
<td bgcolor="#B0C4DE">

<table border="0" cellpadding="2" cellspacing="0" align="center">
<tr>
<td align="right"><font face="Verdana" size="1">User :</font></td>
<td><input type="Text" name="userid" size="25" class="s1"></td>
</tr>
<tr>
<td align="right"><font face="Verdana" size="1">Password :</font></td>
<td><input type="Password" name="password" size="25" class="s1"></td>
</tr>
<tr>
<td>&nbsp;</td>
<td><input type="submit" size="20" value="Login" class="s1" onclick="javascript:document.frm.act.value='LOGIN';">
<input type="hidden" name="act" value="">
</td>
</tr>
</table>


</tr>
</table>
</td>
</tr>
</table>
</form>
 <?php require('footer.inc')?>
</body>
</html>