<?php
function addStr(&$arraystr, $amount) {
   if (strlen($arraystr) == 0)
 	$arraystr = $amount;
   else
	$arraystr = $arraystr.'@'.$amount;
}

function createColor(&$im, $colorstr) {
    $color_array = explode(',', $colorstr);
    reset($color_array);
	return ImageColorAllocate ($im, $color_array[0], $color_array[1], $color_array[2]);
}

function createGraph($month, $year, $totaldays, $sql,
                     $height = 300, $width = 600,
					 $border = 20, $numofypoints = 6,
					 $ytitle = 'Date', $xtitle = '$',
					 $maintitle = '-Untitled-',
					 $graphbkcolor = '0,0,0',
					 $graphlinecolor = '237,52,28',
					 $graphaxiscolor = '237,232,228',
					 $graphtextcolor = '255,244,96' ) {

    include_once('db.php');
    $titlesize= GRAPH_TITLE_SIZE; // point

	if ( !($result = $db->sql_query($sql)) ) {
		  echo "SQL error.";
	}

	$arraystr = '';
	$current = $previous = 0;
	if ( $row = $db->sql_fetchrow($result) )
	 {
		do
		{
		 $no = $row['no'];
		 $current = $no;
		 //echo '('.$current.' '.$previous.') ';
		 if ( ($current - $previous) > 1) {
				$amount = 0.00;
				for($i = 1; $i < ($current - $previous); $i++)
					addStr($arraystr, $amount);

		 }

		 $amount = $row['amt'];
		 addStr($arraystr, $amount);

		 $previous = $current;
		}
		while ( $row = $db->sql_fetchrow($result) );

		$db->sql_freeresult($result);
	}

	//echo $arraystr;

	$array = explode('@', $arraystr);
	$highest = $array[0];
	foreach ($array as $current)
	   if ($current > $highest) $highest = $current;

	//echo '<br>'.$highest;
	$acheight = $height - (2*$border);
	$acwidth =  $width - (2*$border);
	$sizeofarray = count($array);
	$widthinterval = $acwidth/$sizeofarray;
	$heightinterval = $acheight/$numofypoints;

	// set up image
	$im = ImageCreateTrueColor($width, $height);
	/*$white = ImageColorAllocate ($im, 255, 255, 255);
	$blue = ImageColorAllocate ($im, 0, 0, 64);
	$black = ImageColorAllocate ($im, 0, 0, 0);
	$cyan = ImageColorAllocate ($im, 0, 255, 255);
	$custom1 = ImageColorAllocate ($im, 255, 244, 96);
	$custom2 = ImageColorAllocate ($im, 237, 232, 228);
	$custom3 = ImageColorAllocate ($im, 237, 52, 28);
	$lighblue = ImageColorAllocate ($im, 45, 45, 137);*/

	$custom_axis_color = createColor($im, $graphaxiscolor);
	$custom_text_color = createColor($im, $graphtextcolor);

	///////////////////
	// draw on image
	///////////////////

	ImageFill($im, 0, 0, createColor($im,$graphbkcolor));

	/////////////////////
	// Draw Axis
	//////////////////////

	ImageLine($im, $border, $border, $border, $height - $border, $custom_axis_color);
	ImageLine($im, $border, $height - $border, $width - $border, $height - $border, $custom_axis_color);
	ImageString($im, $titlesize, $acwidth/2, $height - 13, $ytitle, $custom_axis_color);
	ImageString($im, $titlesize, $border-2, 5, $xtitle, $custom_axis_color);
	$heightvalueinterval = $highest/$numofypoints;
	for($i = $numofypoints; $i>=0; $i--) {
		$value = sprintf("%01.2f", $i*$heightvalueinterval);
		ImageLine($im, $border-2, $border + ($acheight - $i*$heightinterval), $border+3, $border + ($acheight - $i*$heightinterval), $custom_axis_color);
		ImageString($im, 1, $border-13, $border-3 + ($acheight - $i*$heightinterval), $value, $custom_text_color);
	}

	for($i = 0; $i<$sizeofarray;$i++) {
		$value = $i+1;
		ImageLine($im, $border + ($i * $widthinterval), $height - $border - 2, $border + ($i * $widthinterval), $height - $border + 1, $custom_axis_color);
		ImageString($im, 1, $border + ($i * $widthinterval) - 2, $height - $border + 2, $value, $custom_text_color);
	}

	// interpotate & draw graph
	$prevx = $border;
	$prevy = $border;
	$valuewidth = -$widthinterval + $border;
	$value = reset($array);
	while ($value>-1) {
	 $valueheight = $acheight - ($value/$highest * $acheight) + $border;
	 $valuewidth += $widthinterval;
	 if ($valuewidth > $prevx)
		ImageLine($im, $prevx, $prevy, $valuewidth, $valueheight, createColor($im,$graphlinecolor));
	 $value = next($array);
	 $prevy = $valueheight;
	 $prevx = $valuewidth;
	}


	// Title
	ImageString($im, 2, $acwidth/2, 5, $maintitle, $custom_text_color);

	// output image
	Header ('Content-type: image/png');
	ImagePng ($im);

	// clean up
	ImageDestroy($im);

    $db->sql_close();
}


function createBar(&$arraydata, &$title) {

	/*******************************************
	  Initial calculations for graph
	*******************************************/
	// set up constants
	putenv(FONT_PATH);
	$width=500;        // width of image in pixels - this will fit in 640x480
	$left_margin = 50; // space to leave on left of graph
	$right_margin= 50; // ditto right
	$bar_height = BAR_HEIGHT;
	$bar_spacing = $bar_height/2;
	$font = FONT_NAME;
	$title_size= BAR_TITLE_SIZE; // point
	$main_size= BAR_MAIN_SIZE; // point
	$small_size= BAR_SMALL_SIZE; // point
	$text_indent = 10; // position for text labels from edge of image

	// Data
	$total = 0;
	foreach ($arraydata as $current)
	$total += $current;
	$num = sizeof($arraydata);

	if ($num == 0) return;

	// set up initial point to draw from
	$x = $left_margin + 60;  // place to draw baseline of the graph
	$y = 50;		  // ditto
	$bar_unit = ($width-($x+$right_margin)) / 100;   // one "point" on the graph

	// calculate height of graph - bars plus gaps plus some margin
	$height = $num * ($bar_height + $bar_spacing) + 50;

	/*******************************************
	  Set up base image
	*******************************************/
	// create a blank canvas
	$im = ImageCreateTrueColor($width,$height);

	// Allocate colors
	$white=ImageColorAllocate($im,255,255,255);
	$blue=ImageColorAllocate($im,0,64,128);
	$black=ImageColorAllocate($im,0,0,0);
	$pink = ImageColorAllocate($im,255,78,243);

	$text_color = $black;
	$percent_color = $black;
	$bg_color = $white;
	$line_color = $black;
	$bar_color = $blue;
	$number_color = $pink;

	// Create "canvas" to draw on
	ImageFilledRectangle($im,0,0,$width,$height,$bg_color);

	// Draw outline around canvas
	ImageRectangle($im,0,0,$width-1,$height-1,$line_color);

	// Add title
	$title_dimensions = ImageTTFBBox($title_size, 0, $font, $title);
	$title_length = $title_dimensions[2] - $title_dimensions[0];
	$title_height = abs($title_dimensions[7] - $title_dimensions[1]);
	$title_above_line = abs($title_dimensions[7]);
	$title_x = ($width-$title_length)/2;  // center it in x
	$title_y = ($y - $title_height)/2 + $title_above_line; // center in y gap
	ImageTTFText($im, $title_size, 0, $title_x, $title_y,
				 $text_color, $font, $title);

	// Draw a base line from a little above first bar location
	// to a little below last
	ImageLine($im, $x, $y-5, $x, $height-15, $line_color);

	/*******************************************
	  Draw data into graph
	*******************************************/
	// Get each line of db data and draw corresponding bars

	foreach( $arraydata as $key => $value)
	{
	  //echo '<br>'.$key.'=>'.$value;
	  if ($total > 0)
	    $percent = intval(round(($value/$total)*100));
	  else
	    $percent = 0;

	  // display percent for this value
	  $percent_dimensions = ImageTTFBBox($main_size, 0, $font, $percent.'%');
	  $percent_length = $percent_dimensions[2] - $percent_dimensions[0];
	  ImageTTFText($im, $main_size, 0, $width-$percent_length-$text_indent,
	               $y+($bar_height/2), $percent_color, $font, $percent.'%');

	  if ($total > 0)
	    $right_value = intval(round(($value/$total)*100));
	  else
	    $right_value = 0;

	  // length of bar for this value
	  $bar_length = $x + ($right_value * $bar_unit);

	  // draw bar for this value
	  ImageFilledRectangle($im, $x, $y-2, $bar_length, $y+$bar_height, $bar_color);

	  // draw title for this value
	  ImageTTFText($im, $main_size, 0, $text_indent, $y+($bar_height/2),
	               $text_color, $font, $key);

	  // draw outline showing 100%
	  ImageRectangle($im, $bar_length+1, $y-2,
	                ($x+(100*$bar_unit)), $y+$bar_height, $line_color);

	  // display numbers
	  ImageTTFText($im, $small_size, 0, $x+(100*$bar_unit)-100, $y+($bar_height/2),
	               $number_color, $font, $value.'/'.$total);

	  // move down to next bar
	  $y=$y+($bar_height+$bar_spacing);
	}

	/*******************************************
	  Display image
	*******************************************/
	Header('Content-type:  image/png');
	ImagePNG($im);

	/*******************************************
	  Clean up
	*******************************************/
	ImageDestroy($im);
	}

?>