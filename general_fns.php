<?php
function dec2Hex($dec) {
	$r = sprintf("%x", $dec);
	if (strlen($r) == 1) $r = '0'.$r;
	return $r;
}

function convertColorStr(&$cstr) {

    if (!strchr($cstr,'#')) {
    	$carray = explode(',', $cstr);
   		return dec2Hex($carray[0]).dec2Hex($carray[1]).dec2Hex($carray[2]);
   	}
   	return  substr_replace($cstr,'',0,1);
}

function fillValues(&$hexcolor, &$deccolor) {
    $str = "style='background-color:#$hexcolor;color:#$hexcolor;' value='$deccolor'";
    echo $str;
}
?>