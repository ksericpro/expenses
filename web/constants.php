<?php

   ////////////////////////
   // Database Connection
   ////////////////////////
	$dbhost = 'us-cdbr-iron-east-05.cleardb.net';
	$dbname = 'heroku_4b241068d3bb4b6';
	$dbuser = 'b3f987052cc4f0';
	$dbpasswd = '26a37061';

   /////////////////
   // Constants
   /////////////////
   $BLANK_CELL = '<td bgcolor="#eeeeee">&#160;</td>';
   $MAINDIR = 'Location:';
   $IMGDIR = 'images/';
   define ('ROW_MAX', 7);
   define ('PRINT_COMMENTS', 0);
   define ('FONT_SIZE', 33);
   define ('GRAPH_TITLE_SIZE', 3);
   define ('BAR_TITLE_SIZE', 14);
   define ('BAR_MAIN_SIZE', 10);
   define ('BAR_SMALL_SIZE', 8);
   define ('BAR_HEIGHT', 30);
   define ('DEBUG', 0);

   define ('COLOR_PALETTE_WIDTH', 306);
   define ('COLOR_PALETTE_HEIGHT', 200);
   define ('SUPERUSER', 'SUPER USER');
   define ('FONT_PATH', 'GDFONTPATH=c:\windows\fonts');
   //define ('FONT_PATH', 'fonts');
   //define ('FONT_NAME', $_SERVER['DOCUMENT_ROOT'].'\expenses\fonts\arial.ttf');
   define ('FONT_NAME', $_SERVER['DOCUMENT_ROOT'].'fonts/arial.ttf');

   $TRANSACTION_MODE_BUY = "BUY";
   $TRANSACTION_MODE_SELL = "SELL";
   $TRANSACTION_MODE_CONTRA = "CONTRA";
   $TRANSACTION_MODE_DIVIDEND = "DIVIDEND";

   define ('PERIOD', 5);

?>