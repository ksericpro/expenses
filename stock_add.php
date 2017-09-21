<?php
	require_once('user_auth_fns.php');
	check_valid_user();
	include('include_fns.php');
?>
<?php require('header.inc')?>
<?php

    $currentpage = $_REQUEST['page'];
    $totalrecords = $_REQUEST['totalrecords'];
    $totalpages = $_REQUEST['totalpages'];
?>

<?php
      $msg = '<br>';
      $nric = $_SESSION['userid'];

      $success = 0;
      $act = $_REQUEST['act'];

      if (DEBUG==1) echo $act.'<br>';
      switch ($act) {
        case 'SAVE':
     	case 'EDIT':
     	    $res = insert_Stock($_POST);
     	    $res_array = explode('@', $res);
		    reset($res_array);
			$ct = 0;
		  	foreach ($res_array as $current) {
		     switch($ct) {
		        case 0: $id = $current; break;
		        case 1: $msg = $current; break;
		        case 2: $success = $current; break;
		     }
		     $ct++;
		  	}

		  	//echo 'dd'.$id;

		 	$act = "LOAD";
     	break;

     	case 'DELETE':
            $did = $_REQUEST['Stock'];
	 		if (delete_Stock($did))
      			$msg = "Stock record ".$did." Successfully Deleted.";
      		$CURRENT_ACTION = "SAVE";
      		$success = 1;
     	break;

	 	case 'LOAD':
	 		$id = $_REQUEST['Stock'];
	 	    $msg = "Stock record ".$id." Successfully Loaded.";
	 	break;

	 	default :$CURRENT_ACTION = "SAVE"; break;
      }

      if ($act=="LOAD") {

         $stock = load_Stock($id);
         $mdate = $stock['modifieddate'];
		 if ($id==-1)$CURRENT_ACTION = "SAVE";
         else $CURRENT_ACTION = "EDIT";
	  }

      if (DEBUG == 1) echo  '<center>'.$act.' : '.$sql.'</center><br>';

?>
<html>
<head><title>Stock</title>
</head>
<link rel="stylesheet" href="style.css">
<script language="JavaScript" src="functions.js"></script>
<script language="javascript">
<!--
   ////////////////////
   // Validation
   ////////////////////
   function check() {

      if (document.frm.stockname.value=="") {
		  alert("Stock Name cannot be empty!");
		  document.frm.stockname.focus();
		  return false;
      }

       if (document.frm.market_shares.value!="")
         if (isNaN(document.frm.market_shares.value=="") ){
         	alert("Market Shares must be a Number!");
		 	document.frm.market_shares.focus();
		     return false;
          }

      return true;
   }

   /////////////////
   // Initialise
   /////////////////
   function init() {
     if (document.frm.reloadmaster.value=="1")
   	  	opener.window.location.reload();
   	 setDefault("document.frm.d_Stock", "document.frm.Stock");
   }

-->
</script>
<body onload="init();">

	<table width="90%" border="1" align="center" cellpadding="5" cellspacing="0" bgcolor="#B0C4DE" style="border-collapse:collapse">
		<tr bgcolor="#52637B">
			<td>
			<div align="center"><strong><font size="3" color="#FFCC00">Stock</font></strong></div>
			</td>
		</tr>

		<tr><td bgcolor=#cccccc>

		    <table width=100% cellpadding=0 cellspacing=0><tr><td>

		    </td><td>
		    <div align="right" class=t1>
				<A href="javascript:this.self.close();" title="Close" class="s1">Close</A>
			</div>
			</td></table>

		</td></tr>

		<tr>
			<td>
<br>
			<form name="frm" method="post" action="" onsubmit="return check();">

				<table width="100%" border="0" cellspacing="0" cellpadding="0" >
					<tr>
						<td colspan=2>
						<div align="left" style="margin-bottom:2pt;margin-left:2pt;">
							<font color="#006699"><?php echo $msg?></font></div>

						</td>
					</tr>

					<tr>
						<td valign=top>Stock Name</td>
						<td>
						<input type = 'text' name = 'stockname' value = "<?php echo $stock['stock_name']?>" maxlength = 50 size=50 class=s2>
              			</td>
					</tr>
					<tr>
						<td>PE Ratio</td>
						<td>
						<input type = 'text' name = 'pe' value = "<?php echo $stock['pe']?>" maxlength = 20 size=20 class=s2></td>
					</tr>
					<tr>
						<td>Market Shares</td>
						<td>
						<input type = 'text' name = 'market_shares' value = "<?php echo $stock['market_shares']?>" maxlength = 20 size=20 class=s2></td>
					</tr>
					<tr>
						<td>Remarks</td>
						<td>
						<textarea name = 'remarks' rows=5 cols=40  ><?php echo $stock['remarks']?></textarea></td>
					</tr>
					 <tr>
				       <td colspan="2">


				       <u><b>Existing Stocks</b></u><p>
						Stocks <select name="Stock" class=s2>
               <option value=""></option>
               <?php

 			 $tstr = getStocks();
 			  if ($tstr !=null) {
 				//echo '<option>'.$tstr.'</option>';
 			  $t_array = explode(':',$tstr);
 			  reset($t_array);
 			  foreach ($t_array as $current) {
 				$sep_pos = strpos($current, '@');
				$cid = substr($current, 0, $sep_pos);
				echo '<option value="'.$cid.
					 '"'.$selectstr.'>'.substr($current, $sep_pos+1).'</option>';
 			   }
 		      }
             ?>
          </select>&nbsp;<input type="button" name="edit" value="&lt; Edit" onclick="document.frm.act.value='LOAD';document.frm.submit();" >&nbsp;<input type="button" name="delete" value="&lt; Delete" onclick="document.frm.act.value='DELETE';document.frm.submit();" ></td>
                    </tr>
					 <tr>
				       <td colspan="2"><div style="color:#993366;font-size:7pt;"><i>Last Modified Date : <?php echo $mdate?></i></div></td>
                    </tr>
					<tr>
						<td colspan="2">

							<p align="center">

							<input type="submit" name="Save" value="Save" onclick="javascript:document.frm.act.value='<?php echo $CURRENT_ACTION?>';" >
							<input type="button" name="Reset" value="New" onclick="javascript:document.frm.act.value='';document.frm.submit();">
							<input type="hidden" name="reloadmaster" value="<?php echo $success?>">
							<input type="hidden" name="id" value="<?php echo $id?>">
							<input type="hidden" name="act" value="<?php echo $act?>">
							<input name="d_Stock" type=hidden value="<?php echo $id?>">
							<input name="page" type=hidden value="<?php echo $currentpage?>">
							<input name="totalpages" type=hidden value="<?php echo $totalpages?>">
                			<input type="hidden" name="reinit" value="">
						</td>
					</tr>
					</table>

			</form></td>
		</tr>

         <tr height=5>
            <td bgcolor="#cccccc"> <table width=100% border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><div class=t1 align=left><font color="#cc3300" size=-2><?php echo 'User : '.$_SESSION['valid_user']?></font></div></td>
                  <td><div align="right"><font color="#cc3300" size=-2>&nbsp;</font></div></td>

                </tr>
              </table></td>
        </tr>

	</table>

<?php
if ($conn) mysql_close();
require('footer.inc')?>
</body>
</html>