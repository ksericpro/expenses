<?php
session_unset();
if (isset($_SESSION['valid_user'])) session_destroy();
?>

<body bgcolor="#F0F0F0" vlink="#48576C" link="#48576C" alink="#000000" >

<br><br><br><br><br>

<center>
  <font size="-2" face="Arial, Helvetica, sans-serif">You are currently not logged in. <a href="login.php"><b>Click
  here</b></a> to login.</font>
</center>

<?php require('footer.inc')?>
</body>

