<?php
require_once 'dbconnect.php';
require_once 'functions.php';

html_header();

echo "<div id=\"menu\">"; 
echo '<a href="index.php">Go to log</a>';
echo "</div> <!-- end menu -->";
  
#Navi section for applying filters for main section
echo "<div id=\"navi\">"; 

#configuration parameter: number of displayed rows
if (!empty($_GET['rows'])) {
	anz_rows_update($_GET['rows']);
	print "Number of displayed lines set to ".$_GET['rows']."<br>";
}
if (!empty($_GET['css'])) {
	update_css_file($_GET['css']);
	print "Active css set to ".$_GET['css']." go to log.<br>";
}  

print '<TABLE BORDER="0"><TR><form>';
print '<TD><label for="rows">Edit number of displayed lines: </label></TD>';
print '<TD><input type="number" id="rows" name="rows" value="'.anz_rows_read().'"></TD>';
print '<TD><button type="submit">submit</button></TD>';
print '</form></TR>';
#configuration parameter: used css
print '<TR><form>';
print '<TD><label for="css">Select css (Active='.set_css_file().'): </label></TD>';
print '<TD><select name="css">';
foreach (get_css_files() as $css_options) {
	print '<option>'.$css_options.'</option>';
}
print '</select></TD>';
print '<TD><button type="submit">submit</button></TD>';
print '</form></TR>';
#next configuration parameter can start here

echo "</TABLE></div> <!-- end navi -->";

html_end()
?> 