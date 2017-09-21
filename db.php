<?php

include_once('mysql.php');
include_once('constants.php')
/*$dbhost = 'us-cdbr-iron-east-05.cleardb.net';
$dbname = 'heroku_4b241068d3bb4b6';
$dbuser = 'b3f987052cc4f0';
$dbpasswd = '26a37061';*/

echo 'Connecting to host:'.$dbhost.'<br/>database:'.$dbname.'<br/>User:'.$dbuser.'<br/>Password:'.$dbpasswd;

// Make the database connection.

$db = new sql_db($dbhost, $dbuser, $dbpasswd, $dbname, false);

if(!$db->db_connect_id)
{
   echo 'Could not connect to the database';
}


?>