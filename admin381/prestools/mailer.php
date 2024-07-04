<?php 
/* This script is meant for managing subscriber lists and sending newsletters */
if(!@include 'approve.php') die( "approve.php was not found!");

/* get default language */
$query="select value, l.name from ". _DB_PREFIX_."configuration f, ". _DB_PREFIX_."lang l";
$query .= " WHERE f.name='PS_LANG_DEFAULT' AND f.value=l.id_lang";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$id_lang = $row['value'];

$create_table = 'CREATE TABLE IF NOT EXISTS '._DB_PREFIX_._PRESTOOLS_PREFIX_.'mail_address( email VARCHAR(200) NOT NULL, firstname VARCHAR(100),lastname VARCHAR(100), gender CHAR(5), id_customer INT(8) unsigned, shopname VARCHAR(64), newsletter CHAR(1), birthday DATE DEFAULT NULL, date_add DATE, PRIMARY KEY(email))';
$create_tbl = dbquery($create_table);
$create_table = 'CREATE TABLE IF NOT EXISTS '._DB_PREFIX_._PRESTOOLS_PREFIX_.'mail_list( id_list INT(11) unsigned NOT NULL AUTO_INCREMENT, list_name VARCHAR(200), list_comment VARCHAR(200), PRIMARY KEY(id_list), UNIQUE(list_name))';
$create_tbl = dbquery($create_table);
$create_table = 'CREATE TABLE IF NOT EXISTS '._DB_PREFIX_._PRESTOOLS_PREFIX_.'mail_list_entry( email VARCHAR(200) NOT NULL, id_list INT(6) unsigned NOT NULL, PRIMARY KEY(email,id_list))';
$create_tbl = dbquery($create_table);
$create_table = 'CREATE TABLE IF NOT EXISTS '._DB_PREFIX_._PRESTOOLS_PREFIX_.'mail_blacklist( email VARCHAR(200) NOT NULL, reason VARCHAR(15), startdate DATE, enddate DATE, comment VARCHAR(500), PRIMARY KEY(email))';
$create_tbl = dbquery($create_table);
$create_table = 'CREATE TABLE IF NOT EXISTS '._DB_PREFIX_._PRESTOOLS_PREFIX_.'mail_project( id_project VARCHAR(15),id_list INT(11), sender VARCHAR(100), subject VARCHAR(120), message VARCHAR(3000))';
$create_tbl = dbquery($create_table);


$query = "SELECT id_list, list_name, list_comment FROM `". _DB_PREFIX_._PRESTOOLS_PREFIX_."mail_list`";
$res=dbquery($query);
$listblock = '<select name=id_list onchange="list_change()">';
if(mysqli_num_rows($res) > 0)
{ $listblock .= '<option value="none">Select a list</option>';
  while ($row=mysqli_fetch_array($res)) 
  { $squery = "SELECT COUNT(*) AS listcount FROM `". _DB_PREFIX_._PRESTOOLS_PREFIX_."mail_list_entry` WHERE id_list=".$row['id_list'];
	$sres=dbquery($squery);
	$srow=mysqli_fetch_array($sres);
    $listblock .= '<option value="'.$row["id_list"].'" data-comment="'.str_replace('"','&quot;',$row['list_comment']).'">'.$row["list_name"].' ('.$srow["listcount"].')</option>';
  }
}
$listblock .= '</select>';
?><!DOCTYPE html> 
<html lang="en"><head><meta charset="utf-8">
<title>Prestashop Mailer</title>
<link rel="stylesheet" href="style1.css" type="text/css" />
<style>
div.leader { background-color: #BBFF99; }
</style>
<script type="text/javascript" src="utils8.js"></script>
<script type="text/javascript" src="sorter.js"></script>
<script src="tinymce/tinymce.min.js"></script> 
<script>
/* the arguments for this version were derived from source code of the "classic" example on the TinyMCE website */
/* some buttons were removed but all plugins were maintained */
function useTinyMCE2()
{ if (typeof tinymce == 'undefined')
  { alert("TinyMCE could not be loaded! Please check your internet connection!"); return false; }
  tinymce.init({
  	selector: "#mailbody", 
	plugins: [
		"advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker save",
		"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
		"table contextmenu directionality emoticons template textcolor paste fullpage textcolor colorpicker textpattern"
	],
	toolbar1: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
	toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview",
	toolbar3: "forecolor backcolor | table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking | save",
	menubar: false,
	toolbar_items_size: 'small',
	style_formats: [
		{title: 'Bold text', inline: 'b'},
		{title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
		{title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
		{title: 'Example 1', inline: 'span', classes: 'example1'},
		{title: 'Example 2', inline: 'span', classes: 'example2'},
		{title: 'Table styles'},
		{title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
	],
	width: 660,
	autosave_ask_before_unload: false,
	save_onsavecallback: function() {alert('weqwrq');}
  });
}  

function list_change()
{ switch_list_buttons();
  var span = document.getElementById("listcomment_span");
  span.innerHTML = maillistform.id_list.options[maillistform.id_list.selectedIndex].getAttribute("data-comment");
}

function switch_list_buttons()
{ var span = document.getElementById("listbutton_span");
  var buttons = span.getElementsByTagName("button");
  for(var i=0; i<buttons.length; i++)
  { if(maillistform.id_list.selectedIndex == 0)
	  buttons[i].disabled = true;
    else
	  buttons[i].disabled = false;	
  }
}

function LoadPage(url, callback)
{ var request =  new XMLHttpRequest("");
  request.open("GET", url, true); /* delaypage must be a global var; changed from POST to GET */
  request.onreadystatechange = function() 
  { if (request.readyState == 4 && request.status == 404) /* done = 4, ok = 200 */
	alert("ERROR "+request.status+" "+request.responseText) 
    if (request.readyState == 4 && request.status == 200) /* done = 4, ok = 200 */
    { if (request.responseText) 
        callback(request.responseText);
    };
  }
  request.send(null);
}

function add_list()
{ var div = document.getElementById("listdiv");
  var tmp = '<center><b>Add a new list</b></center><br/>Give a name for the list: <input name=list_name>';
  tmp += ' &nbsp; &nbsp; comment: <textarea name=list_comment rows=5 cols=40 style="vertical-align: top;"></textarea>';
  tmp += '<input type=hidden name=action value="add_list">';
  tmp += ' &nbsp; &nbsp; <input type=submit value="Submit">';
  div.innerHTML = tmp;
  return false;
}

function delete_list()
{ var div = document.getElementById("listdiv");
  var tmp = '<center><b>Are you sure that you want to delete this list? &nbsp; ';
  tmp += '<input type=hidden name=action value="delete_list">';
  tmp += ' &nbsp; &nbsp; <button onclick="return set_detailfield(\'listdiv\',\'\');">No</button>';  
  tmp += ' &nbsp; &nbsp; <input type=submit value="Yes">';
  div.innerHTML = tmp;
  return false;	
}

function rename_list()
{ var div = document.getElementById("listdiv");
  var selected_list = maillistform.id_list.selectedIndex;
  var comment = maillistform.id_list.options[selected_list].getAttribute("data-comment");
  var name = maillistform.id_list.options[selected_list].text.replace(/ \([0-9]*\)$/,'');
  var tmp = '<center><b>Rename or comment this list</b></center><br/>';
  tmp += 'Name for the list: <input name=list_name value="'+name+'">';
  tmp += ' &nbsp; &nbsp; comment: <textarea name=list_comment rows=5 cols=40 style="vertical-align: top;">'+comment+'</textarea>';
  tmp += '<input type=hidden name=action value="rename_list">'; 
  tmp += ' &nbsp; &nbsp; <button onclick="return set_detailfield(\'listdiv\',\'\');">Cancel</button>';  
  tmp += ' &nbsp; &nbsp; <input type=submit value="Submit">';
  div.innerHTML = tmp;
  return false;	
}

/* purge_list removes data from another list or the blacklist from the present list */
function purge_list()
{
  return false;	
}

function empty_list()
{ var div = document.getElementById("listdiv");
  var tmp = '<center><b>Are you sure that you want to empty this list? &nbsp; ';
  tmp += '<input type=hidden name=action value="empty_list">';
  tmp += ' &nbsp; &nbsp; <button onclick="return set_detailfield(\'listdiv\',\'\')">No</button>';  
  tmp += ' &nbsp; &nbsp; <input type=submit value="Yes"><center>';
  div.innerHTML = tmp;
  return false;	
}

function add_addresses()
{ var div = document.getElementById("listdiv");
  var tmp = '<center><b>Import email addresses in this list</b></center>';
  tmp += '<input type=hidden name=action value="add_addresses">';
  tmp += '<table width="100%"><tr width="100%"><td width="33%"><b>Addresses source</b><br>';
  tmp += '<input type=radio name="listsource" value="newsletter">Newsletter subscribers<br>';
  tmp += '<input type=radio name="listsource" value="customers">Webshop customers<br>';
  tmp += '<input type=radio name="listsource" value="csvfile">CSV file<br>';
  tmp += '<input type=radio name="listsource" value="textwindow">Text window<br>';
  tmp += '</td><td width="33%">';
  tmp += 'Period (yyyy-mm-dd): <input size=5 name=startdate> till <input size=5 name=enddate><br/>';
  tmp += '</td><td width="33%">';
  tmp += '<input type=submit value="Import">';
  tmp += '</td></tr></table>';
  div.innerHTML = tmp;
  return false;	
}

function edit_list()
{ var div = document.getElementById("listdiv");
  var listname = maillistform.id_list.options[maillistform.id_list.selectedIndex].text;
  var tmp = '<center><b>Edit mailing list '+listname.replace(/.$/," entries)")+'</b></center>';
  tmp += 'Startrec: <input size=3 name=startrec>';
  tmp += ' &nbsp &nbsp; Number of recs: <input size=3 name=numrecs>';
  tmp += ' &nbsp &nbsp; Search term: <input size=7 name=searchterm>';
  tmp += ' &nbsp &nbsp; Order by: <SELECT name=mailorder><option value="1">Last name</option><option value="2">Email address</option><option value="3">Date added</option><SELECT>';
  tmp += ' &nbsp; &nbsp; <SELECT name=rising><option>ASC</option><option>DESC</option></select>';
  tmp += ' &nbsp &nbsp; <button onclick="show_edit_list(); return false;">Submit</button><p>';
  tmp += '<div id="editfield"></span>';
  div.innerHTML = tmp;
  return false;	
}

function show_edit_list()
{ LoadPage("mailer-ajax.php?task=edit_list_form&id_list="+maillistform.id_list.value+"&startrec="+maillistform.startrec.value+"&numrecs="+maillistform.numrecs.value+"&searchterm="+maillistform.searchterm.value+"&mailorder="+maillistform.mailorder.value+"&rising="+maillistform.rising.value,dynamo1);
}

function dynamo1(data)  /* get product name */
{ var editfield=document.getElementById("editfield");
  editfield.innerHTML = data;
}

function export_list()
{ var div = document.getElementById("listdiv");
  var tmp = '<center><b>Do you want export this list? &nbsp; ';
  tmp += '<input type=hidden name=action value="export_list">';
  tmp += ' &nbsp; &nbsp; <button onclick="return set_detailfield(\'listdiv\',\'\');">No</button>';  
  tmp += ' &nbsp; &nbsp; <input type=submit value="Yes"><center>';
  div.innerHTML = tmp;
  return false;
}

function add_to_blacklist()
{ var div = document.getElementById("blacklistdiv");
  var tmp = '<center><b>Add to Blacklist</b></center>';
  tmp += 'The csv format is: email;reason (changed, bounced, opt-out, other or unknown);startdate(yyyy-mm-dd);enddate(yyyy-mm-dd);comment(no linefeeds)<br>';
  tmp += 'You can extract email addresses from freetexts such as error reports.<br>'; 
  tmp += '<textarea name=blacklist_text rows=10 cols=100></textarea><br>';
  tmp += ' &nbsp; &nbsp; <button onclick="return blacklist_extract();">Extract emails from free text</button>';
  tmp += ' &nbsp; &nbsp;<input name=reason style="width:"> <button onclick="return blacklist_add_reason();">Add Reason</button>';
  tmp += ' &nbsp; &nbsp; <button onclick="return blacklist_csv_submit();">Submit csv formatted text</button>';  
  div.innerHTML = tmp;
  return false;
}

function blacklist_extract()
{ var text = blacklistform.blacklist_text.value;
  var emails = text.match(/([a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z0-9._-]+)/gi);
  if(emails == null)
    blacklistform.blacklist_text.value = "";
  else
    blacklistform.blacklist_text.value = emails.join('\n');	  
  return false;
}

function blacklist_add_reason()
{ var text = blacklistform.blacklist_text.value;
  var reason = blacklistform.reason.value;
  var lines = text.split('\n');
  var length = lines.length;
  for(i=0; i<lines.length;i++)
  { if(lines[i].length < 6) continue;
    var parts = lines[i].split(';');
	if(parts.length == 1)
	  lines[i] += ";"+reason
    else
	{ 
      if(parts[1]=="") parts[1] = reason;
	  lines[i] = parts.join(';');
	}
  }
  blacklistform.blacklist_text.value = lines.join('\n');	  
  return false;
}

function blacklist_csv_submit()
{ blacklistform.target="tank";
  blacklistform.action = "mailer-iframe.php";
  blacklistform.task.value = "blacklist_csv_add";
  blacklistform.returnfunc.value = "dynamo4";
  blacklistform.verbose.value = maillistform.verbose.value; 
  blacklistform.submit();
  return false;
}

function dynamo4(data)
{ var countfield=document.getElementById("blacklistcount");
  countfield.innerHTML = data;
}

function edit_blacklist()
{ var div = document.getElementById("blacklistdiv");
  var tmp = '<center><b>Edit blacklist</b></center>';
  tmp += 'Startrec: <input size=3 name=startrec>';
  tmp += ' &nbsp &nbsp; Number of recs: <input size=3 name=numrecs>';
  tmp += ' &nbsp &nbsp; Search term: <input size=7 name=searchterm>';
  tmp += ' &nbsp &nbsp; Order by: <SELECT name=mailorder><option value="1">Email address</option><option value="2">Date blacklisted</option><SELECT>';
  tmp += ' &nbsp; &nbsp; <SELECT name=rising><option>ASC</option><option>DESC</option></select>';
  tmp += ' &nbsp &nbsp; <button onclick="edit_blacklist_in_editor(); return false;">Edit in editor</button>';
  tmp += ' &nbsp &nbsp; <button onclick="edit_blacklist_in_table(); return false;">Edit as table</button><p>';
  tmp += 'The csv format is: email;reason (changed, bounced, opt-out, other or unknown);startdate(yyyy-mm-dd);enddate(yyyy-mm-dd);comment(no linefeeds)<br>';
  tmp += ' <textarea name="blacklist_text" rows="10" cols="100"></textarea><br>';
  tmp += ' &nbsp; &nbsp; <button onclick="return blacklist_csv_submit();">Submit csv formatted text</button>';  
  div.innerHTML = tmp;
  return false;	
}

function edit_blacklist_in_editor()
{ LoadPage("mailer-ajax.php?task=edit_blacklist_editor&startrec="+blacklistform.startrec.value+"&numrecs="+blacklistform.numrecs.value+"&searchterm="+blacklistform.searchterm.value+"&mailorder="+blacklistform.mailorder.value+"&rising="+blacklistform.rising.value,dynamo5);
  
}

function dynamo5(data)
{ blacklistform.blacklist_text.value = data;
}

function edit_blacklist_in_table()
{
	
}

function empty_blacklist()
{ var div = document.getElementById("blacklistdiv");
  var tmp = '<center><b>Are you sure that you want to empty this list? &nbsp; ';
  tmp += ' &nbsp; &nbsp; <button onclick="set_detailfield(\'blacklistdiv\',\'\');">No</button>';  
  tmp += ' &nbsp; &nbsp; <button onclick="empty_blacklist2();">Yes</button>';
  div.innerHTML = tmp;
}

function empty_blacklist2()
{ blacklistform.action.value = "empty_blacklist";
  blacklistform.verbose.value = maillistform.verbose.checked;
  blacklistform.submit();
}

function export_blacklist()
{ blacklistform.action.value = "export_blacklist";
  blacklistform.verbose.value = maillistform.verbose.checked;
  blacklistform.submit();
  return false;
}

function set_detailfield(block, value)
{ var div = document.getElementById(block);
  div.innerHTML = value;
}

function startsendmails()
{	mySender = setInterval(sendmails, 3*1000);
	return false;
}

function sendmails()
{ var sender = sendmailform.sender.value;
  var subject = sendmailform.subject.value;
  var startrec = sendmailform.startrec.value;
  var numrecs = sendmailform.numrecs.value;
  if(sendmailform.startrec.value == "")
	  sendmailform.startrec.value = 0;
  LoadPage("mailer-ajax.php?task=sendmails&startrec="+startrec+"&numrecs="+numrecs+"&sender="+sender+"&subject="+subject,dynamo7);
  return false;
}

function exportcsv()
{
	
}

function sendtestmail()
{ var sender = sendmailform.sender.value;
  var subject = sendmailform.subject.value;
  var email = sendmailform.email.value; 
  var firstname = sendmailform.firstname.value;  
  var lastname = sendmailform.lastname.value;  
  LoadPage("mailer-ajax.php?task=sendtestmail&sender="+sender+"&subject="+subject+"&email="+email+"&firstname="+firstname+"&lastname="+lastname,dynamo7);
  return false;
}

function dynamo7(data)
{ var tmp = data.split('<br>');
  var fld = document.getElementById("sentlist");
  fld.innerHTML += data;
  if(tmp[0].substring(0,21)== "Now sending test mail")
	  return;
  if(tmp.length-2 < parseInt(sendmailform.numrecs.value))
	  clearInterval(mySender);
  /* update the counters only when this is not a testmail */
  var sent = 0;
  var failed = 0;
  var blacklisted = 0;
  for(var i=0; i<tmp.length-2; i++)
  { if(tmp[i].charAt(0)=="M")
	  sent++;
    else if(tmp[i].charAt(0)=="T")
	  blacklisted++;
    else
      failed++;
  }
  sendmailform.startrec.value = sent+blacklisted+parseInt(sendmailform.startrec.value);
  fld = document.getElementById("failed_field");
  fld.innerHTML = failed+parseInt(fld.innerHTML);
  fld = document.getElementById("blacklisted_field"); 
  fld.innerHTML = blacklisted+parseInt(fld.innerHTML);

}

function init()
{ maillistform.id_list.selectedIndex = 0;
  switch_list_buttons();
  useTinyMCE2();
}
</script>
</head><body onload="init()">
<?php
print_menubar();
echo '<form name=maillistform action="mailer-proc.php">';
echo '<table width="100%"><tr><td width="90%"><center><b><font size="+1">Prestools Mailer for Prestashop</font></b></center></td><td rowspan=2 style="text-align:right"><iframe name=tank width="220" height="140"></iframe></td></tr>';
echo '<tr><td style="text-align:right"><input type=checkbox name=verbose value="on"> Verbose</td></tr></table>';
/*** get data from newsletter list, customers, csv file, window ***/
echo '<div width="100%" class="leader" id="list_leader">Mailing lists <div style="float:right"><img src="close.png" onclick="return set_detailfield(\'listdiv\',\'\');"></div></div>';
if(strlen($listblock) < 70)
  echo "<b>No list available</b>".$listblock;
else
  echo $listblock;
echo ' &nbsp; &nbsp; <button onclick="return add_list();">Add a list</button>';
echo '<span id=listbutton_span>';
echo ' &nbsp; &nbsp; <button onclick="return delete_list();">Delete list</button>';
echo ' &nbsp; &nbsp; <button onclick="return rename_list();">Rename/comment</button> &nbsp; &nbsp; <button onclick="return purge_list();">Purge list</button>';
echo ' &nbsp; &nbsp; <button onclick="return add_addresses();">Add addresses to list</button> &nbsp; &nbsp; <button onclick="return empty_list();">Empty list</button>';
echo ' &nbsp; &nbsp; <button onclick="return edit_list();">Edit list</button> &nbsp; &nbsp; <button onclick="return export_list();">Export</button>';
echo '</span>';
echo '<br><span id=listcomment_span></span>';
echo '<p><div id="listdiv"></div></form>';
echo '<p></form>';

/*** blacklist ***/
echo '<div width="100%" class="leader" id="blacklist_leader">Blacklist<div style="float:right"><img src="close.png" onclick="return set_detailfield(\'blacklistdiv\',\'\');"></div></div>';
echo '<form name=blacklistform target="tank" onsubmit="return false;" action="mailer-proc.php">';
echo '<input type=hidden name=action><input type=hidden name=verbose><input type=hidden name=returnfunc>';
echo "With the blacklist you can filter out addresses that are no longer valid or have opted out. ";
echo "Format: email;reason;startdate;enddate;comment. Only email is obligatory.";
$query = "SELECT count(*) AS blackcount FROM `"._DB_PREFIX_._PRESTOOLS_PREFIX_.'mail_blacklist`';
$res=dbquery($query);
list($blackcount) = mysqli_fetch_row($res); 
echo ' It has <span id="blacklistcount">'.$blackcount.'</span> entries.<br>';
echo '<button onclick="return add_to_blacklist();">Add addresses</button> &nbsp; &nbsp; <button onclick="return edit_blacklist();">Edit blacklist</button>';
echo ' &nbsp; &nbsp; <button onclick="empty_blacklist();">Empty blacklist</button>';
echo '&nbsp; &nbsp; <button onclick="alert(\'HIIJ\');">Export</button>';
echo '<p><div id="blacklistdiv"></div></form>';

/*** Edit email addresses ***/
echo '<div width="100%" class="leader" id="email_leader">Addresses</div>';
echo '<br>';

/*** Edit template ***/
echo '<div width="100%" class="leader" id="template_leader">Template</div>';
echo '<br>';

/*** Send mails ***/
echo '<div width="100%" class="leader" id="send_leader">Send mails</div>';
echo '<form name=sendmailform >';
echo '<br><table><tr><td>';
echo '<textarea name="mailbody" rows="20" cols="50" id="mailbody"></textarea></td><td>';
echo 'Sender: <input name=sender style="width:250px;"><p>';
echo 'Subject: <input name=subject style="width:250px;"><p>';
echo 'Startrec: <input name=startrec style="width:50px;"> &nbsp; ';
echo 'Numrecs: <input name=numrecs style="width:50px;"><p>';
echo 'failed: <span id="failed_field">0</span> &nbsp; blacklisted: <span id="blacklisted_field">0</span> ';
echo '<button onclick="return startsendmails();">Send email(s)</button>';
echo '<hr>';
echo 'Testaddress <input name=email style="width:200px;" value="wimroffel@gmail.com"><p>';
echo 'first name <input name=firstname style="width:125px;"> Last name <input name=lastname style="width:125px;"><p>';
echo '<button onclick="return sendtestmail();">Send test mail</button></td></tr></table>';
echo '<div id="sentlist">';
echo '</form>';
?>