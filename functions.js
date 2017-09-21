var CURRENT_WINDOW = null;
var COLOR_PICKER = null;

function openNewWindow(url, wd, ht) {
    if (wd == "") wd = "500";
    if (ht == "") ht = "350";
    CURRENT_WINDOW = window.open(url, 'Misc', 'width=' + wd + ',height=' + ht + ',resizable=1, left=0, top=0, scrollbars=1, menubar=0');
}

function openNewWindow2(url, wn, wd, ht) {
    if (wd == "") wd = "500";
    if (ht == "") ht = "350";
    CURRENT_WINDOW = window.open(url, wn, 'width=' + wd + ',height=' + ht + ',resizable=1, left=0, top=0, scrollbars=1, menubar=0');
}

function openColorPicker(pg, subject, original) {
    wd = "380";
    ht = "280";
    COLOR_PICKER = window.open(pg + '&subject=' + subject + '&original='+original, 'color', 'width=' + wd + ',height=' + ht + ',resizable=0, left=' + screen.width/3 + ', top=10, scrollbars=1, menubar=0');   
}

function ConfirmDeleteA(pg, id)
{
   if (confirm("Confirm Delete Record (" + id + ") ?")) {
      var urlstr = pg + "action=DELETE&id=" + id;
      window.location = urlstr;
    }
}

function ConfirmDeleteB(pg, id, date)
{
   if (confirm("Confirm Delete Record (" + id + ") ?")) {
      var urlstr = pg + "action=DELETE&id=" + id + "&date=" + date;
      window.location = urlstr;
    }
}

function setDefault(subject, match) {
var t1 = eval(subject);
var t2 = eval(match);

if (t1.value != "")
  for (var i=0; i < t2.length; i++)
	if (t2[i].value == t1.value) {
	t2[i].selected = 1;
	break;
}

}


function setDefault2(subject, match) {

var t1 = eval(subject);
var t2 = eval(match);
if (t1.value != "")
  for (var i=0; i < t2.length; i++)
	if (t2[i].value == t1.value) {
	t2[i].checked = 1;
	break;
  }
}

///////////////////
// Hex Dec Functions
///////////////////

function d2h(d) {
   var hD="0123456789ABCDEF";
   var h = hD.substr(d&15,1);
   while(d>15) {d>>=4;h=hD.substr(d&15,1)+h;}
   return h;
}

function convertColorStr(str) {
    return h2d(str.substr(1,2)) + ',' + h2d(str.substr(3,2)) + ',' + h2d(str.substr(5,2));
}

function h2d(h) {
   return parseInt(h,16);
}

var PERIOD_YEAR = 3;

//////////////////////////
//Determine Browser Type
//////////////////////////

if (document.layers) {navigator.family = "nn4"}
if (document.all) {navigator.family = "ie4"}
if (window.navigator.userAgent.toLowerCase().match("gecko")) {navigator.family = "gecko"}

function writeSelectYear() {
	var right_now=new Date();
  	var yr = right_now.getYear();	
	if (navigator.family !="ie4") yr += 1900;
	  
	for (var i = yr; i > yr - PERIOD_YEAR ; i--) 
	 document.write('<option value="' + i + '">' + i + '</option>');
}