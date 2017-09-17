<?php
	require_once('user_auth_fns.php');
	check_valid_user();
	$userid = $_SESSION['userid'];
	//include('db.php');
	include_once('constants.php');
?>
<?php

$currentmonth = $_REQUEST['month'];
$currentyear = $_REQUEST['year'];


if ($currentmonth == "-1") {
   $startmonth = 1;
   $endmonth = 12;
   $title = "Expenses Yearly Report $currentyear";
   }
 else {
   $startmonth = $currentmonth;
   $endmonth = $currentmonth;
   $title = 'Expenses Monthly Report';
   }

require 'PDF.php';                         // Require the lib.
$pdf = &PDF::factory('p', 'a4');       // Set up the pdf object.
$pdf->open();                             // Start the document.
$pdf->setCompression(true);         // Activate compression.
$pdf->addPage();                        // Start a page.
$pdf->setFont('Arial', '', 36);        // Set font to arial 8 pt.
$pdf->text(100, 100, 'Personal Planner');  // Text at x=100 and y=100.
$pdf->setFontSize(20);                 // Set font size to 20 pt.
$pdf->text(100, 200, $title); // Text at x=100 and y=200.
$pdf->setFontSize(10);
$mdate = date('Y-m-d H:i:s');
$pdf->text(100, 400, "Generated on - ".$mdate);

//echo 'Range'.$startmonth.' '.$endmonth;
for ($currentmonth = $startmonth; $currentmonth<=$endmonth; $currentmonth++)  {


 if ($currentmonth == null) {
	$currentmonth = date('m');
	$currentyear = date('Y');
  }


 // echo 'm='.$currentmonth.', yr='.$currentyear;
  $firstday = mktime(0,0,0, $currentmonth, 1, $currentyear);
  $totaldays = date('t', $firstday);
  $monthname = date("M", $firstday);

   ///////////////////
   // Last Month
   ////////////////////
  $lastMonth = $currentmonth - 1;
  $lastYear = $currentyear;
  if ($lastMonth < 1) {
  	$lastMonth = 12;
  	--$lastYear;
  }

  ///////////////////
  // Next Month
  ////////////////////
 $nextMonth = $currentmonth + 1;
 $nextYear = $currentyear;
 if ($nextMonth > 12) {
  	 $nextMonth = 1;
      ++$nextYear;
  }

$sql = "select c.Name, SUM(e.Amount) as 'amt' from expenses e, category c where
		 c.CategoryID = e.CategoryID and c.UserID = $userid
		 and month(e.ExpensesDate) = $currentmonth
		 and year(e.ExpensesDate) = $currentyear
		 group by c.Name
		 order by c.Name";

//echo '<span style="font-size:7pt">'.$sql.'</span><br>';
$MTHS_ABR = array(1 => 'JAN', 'FEB', 'MAR',
               		    'APR', 'MAY',  'JUN', 'JUL',
               		    'AUG', 'SEP', 'OCT', 'NOV', 'DEC');
$mthname = $MTHS_ABR[$currentmonth];
//echo $mthname;
require('db.php');
if ( !($result = $db->sql_query($sql)) ) echo "SQL ERROR<br>";



$pdf->addPage();                         // Add a new page.
$pdf->setFont('Arial', '', 24);
$pdf->text(100, 60, $mthname.' '.$currentyear);
$pdf->setFont('Arial', 'BI', 10);
$y = 80;
$ct = 0;
$amt=0;
if ( $row = $db->sql_fetchrow($result) )
{
	do
	{
	 $ct ++;
	 //$visa_cardno = $row['visa_cardno'];
	 //$transaction_date = $row['transaction_date'];
	 //$bank = $row['bank'];
	 //$purpose = $row['purpose'];
	 $name = $row['Name'];
	 $amount = $row['amt'];
	 $amt+=$amount;
	 $value = sprintf("%01.2f", $amount);
	 $y =$y + 20;
     //$pdf->text(100, $y, $ct.'. '.$transaction_date.' '.$visa_cardno.'('.$bank.') $'.$value);
     //$pdf->text(100, $y + 10, $purpose);
     $pdf->text(100, $y, $name.' $'.$amount);
	}
	while ( $row = $db->sql_fetchrow($result) );

$db->sql_freeresult($result);
}

$db->sql_close();
$pdf->text(100, $y + 40, 'Total - $'.$amt);


}

$pdf->output('report_monthly.pdf');

?>