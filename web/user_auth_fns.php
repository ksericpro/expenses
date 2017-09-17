<?php

function login($username, $password)
{
  // check if username is unique
  include('db.php');
  $sql = "select CONCAT(u.userID ,'@',u.Name ,'@',u.Role,'@', s.SettingsID) as 'str1' from user u, settings s
         where u.UserID = s.UserID
         and u.LoginID='$username'
         and u.password=sha1('$password')";

  //echo $sql;
  if ( !($result = $db->sql_query($sql)) ) {
     $db->sql_close();
     return null;
   }

  if( $row = $db->sql_fetchrow($result) ) {
     $str = $row['str1'];
     $db->sql_freeresult($result);
     $db->sql_close();
     return $str;
     }

  return null;
}

///////////////////////
// Check valid user
/////////////////////
function check_valid_user()
{
  include_once('constants.php');
  session_start();

  if (isset($_SESSION['valid_user']))
  {
      //echo 'Logged in as '.$_SESSION['valid_user'].'.';
      //echo '<br />';
      ;
  }
  else
  {
     // they are not logged in
     echo 'You are not logged in.<br />';
     header($MAINDIR.'/logout.php');
  }
}

///////////////////
// Check Super User
///////////////////
function checkUser($str) {
	if ($_SESSION['role'] == SUPERUSER)
	  echo 'href="'.$str.'"';
	else {
	  $msg = 'alert("You are not authorised.");';
	  echo "href='javascript:$msg'";
	  }
}

?>
