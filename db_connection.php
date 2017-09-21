<?php
include_once('constants.php');

echo 'sqlhost='.mysql_host.' password='.mysql_password;
$con = mysqli_connect(mysql_host,mysql_user,mysql_password,mysql_dbname);

// Check connection
if (mysqli_connect_errno())
{
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
 
echo "<br/>database connected";

$sql = "SELECT * From Stocks";

echo '<br/>Querying..'.$sql;
$result = $con->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "stock id: " . $row["stock_id"]. " - Name: " . $row["stock_name"]. "<br>";
    }
	
	// Free result set
	mysqli_free_result($result);
} else {
    echo "<br/>0 results";
}

// sql to delete a record
$sql = "DELETE FROM Stocks WHERE stock_id=1";
echo '<br/>Querying..'.$sql;

if ($con->query($sql) === TRUE) {
    echo "<br/>Record deleted successfully";
} else {
    echo "<br/>Error deleting record: " . $con->error;
}


$sql = "UPDATE Stocks SET stock_name='intRoller' WHERE stock_id=2";
echo '<br/>Querying..'.$sql;

if ($con->query($sql) === TRUE) {
    echo "<br/>Record updated successfully";
} else {
    echo "<br/>Error updating record: " . $con->error;
}


echo "<br/>Closing SQL Conection";
$con->close();
?>