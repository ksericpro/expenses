<?php

include('mysql.php');

$dbhost = '172.30.14.107';
$dbname = 'personal';
$dbuser = 'personaluser';
$dbpasswd = 'personaluser321';

// Make the database connection.

$db = new sql_db($dbhost, $dbuser, $dbpasswd, $dbname, false);

if(!$db->db_connect_id)
{
   echo 'Could not connect to the database';
}


?>