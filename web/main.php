<?php
	require_once('user_auth_fns.php');
	check_valid_user();
	//include_once('button_functions');
//print ($_SERVER['DOCUMENT_ROOT']);
?>
<?php require('header.inc')?>
<link rel="stylesheet" href="style.css">
<SCRIPT language="JavaScript" src="functions.js"></SCRIPT>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>
<table width="40%" border="1" align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse">
  <tr><td>
	<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#ffffff">
        <tr>
          <td bgcolor="#52637B"> <div align="center"> <strong><font color="#FFCC00" size="3">PERSONAL
              Planner</font></strong></div></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><div align=center>
		  
		      <table border="0"><tr><td style="margin-top:5pt;font-size:5pt;">
		      <a href="calendar.php" title="expenses" class="s2" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('expenses','','make_button.php?button_text=Expenses&color=red',1)">
		      <img name="expenses" src="make_button.php?button_text=Expenses&color=blue&fontsize=16" border="0" alt="&gt;Expenses&lt;"></a>
              <br>
			  <a href="creditcard.php" title="creditcard" class="s2" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('creditcard','','make_button.php?button_text=Credit+Card&color=red',1)">
			  <img name="creditcard" src="make_button.php?button_text=Credit+Card&color=blue&fontsize=16" border="0" alt="&gt;Credit Card&lt;"></a>
			  <br>
			  <!--<a href="assets.php" title="assets" class="s2" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('assets','','make_button.php?button_text=Assets&color=red',1)">
			  <img name="assets" src="make_button.php?button_text=Assets&color=blue&fontsize=16" border="0" alt="&gt;Assets&lt;"></a>
			  <br>-->
			  <a href="reports.php" title="reports" class="s2" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('reports','','make_button.php?button_text=Reports&color=red',1)">
			  <img name="reports" src="make_button.php?button_text=Reports&color=blue&fontsize=16" border="0" alt="&gt;Reports&lt;"></a>
			  <br>
              <a href="category.php" title="category" class="s2" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('category','','make_button.php?button_text=Category&color=red',1)">
              <img name="category" src="make_button.php?button_text=Category&color=blue&fontsize=16" border="0" alt="&gt;Category&lt;"></a>
			  <br>
              <a href="stocks.php" title="stocks" class="s2" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('stocks','','make_button.php?button_text=Stocks&color=red',1)">
              <img name="stocks" src="make_button.php?button_text=Stocks&color=blue&fontsize=16" border="0" alt="&gt;Stocks&lt;"></a>
			  <br>
              <a href="user.php" title="users" class="s2" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('users','','make_button.php?button_text=Users&color=red',1)">
              <img name="users" src="make_button.php?button_text=Users&color=blue&fontsize=16" border="0" alt="&gt;Users&lt;"></a>
			  </td></tr></table>
			</div>
			</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td bgcolor="#52637B"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><div class=t1><font color="#FFFFCC"><?php echo 'User : '.$_SESSION['valid_user']?></font></div></td>
                <td><div align="center"><font color="#FFFFCC"><?php echo 'Role : '.$_SESSION['role']?></font></div></td>
                <td><div align="right" class=t1><font color="#FFFFCC"><a href="logout.php" title="log out" class="s2">Log
                    Off</a>&nbsp;</font></div></td>
              </tr>
            </table></td>
        </tr>
      </table>
	  </td></tr></table>
<?php require('footer.inc')?>
