<?php

// connect to the mlm database
function db_connect()
{
   include('constants.php');
   $result = mysql_connect($dbhost, $dbuser, $dbpasswd)
   or die('Could not connect: ' . mysql_error());
   //echo 'DB...'.$dbname.' Connected successfully<br>';
   mysql_select_db($dbname) or die('2.Could not select database');
   if (!$result)
      return false;
   return $result;
}

?>