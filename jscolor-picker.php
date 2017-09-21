<?php
$subject = $_REQUEST['subject'];
$original = $_REQUEST['original'];
include_once('constants.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>onFineChange Color Picker</title>
</head>
<link rel="stylesheet" href="style.css">
<body>


<script src="jscolor.js"></script>

<p>Rectangle color:
<input class="jscolor {onFineChange:'update(this)'}" value="<?php echo $original?>">

<p id="rect" style="border:1px solid gray; width:161px; height:100px;">

<script>
<!--
var selectedcolor="";

function update(jscolor) {
    // 'jscolor' instance can be used as a string
    document.getElementById('rect').style.backgroundColor = '#' + jscolor
	
	selectedcolor = jscolor;
}

function updateandclose()
{
	//alert(selectedcolor);
	updateParent(selectedcolor);
	window.close();
}

function updateParent(str) {
    //var str = document.myColor.getSelectedColor();
    //alert(str);
    var orig = eval("opener.document.frm." + document.frm.subject.value);
    orig.style.backgroundColor = '#' + str;
    orig.style.color = '#' + str;
}
-->
</script>

<form name="frm" method="post">
<input type="button" onclick="updateandclose();" value="Update Color & Close"/>
&nbsp;<input type="button" value="exit" onclick="window.close();"/>
<input type="hidden" name="subject" value="<?php echo $subject?>"> 

</form>
</body>
</html>