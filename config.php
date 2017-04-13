<?php
/*	iptables-mysql-logview
    Copyright (C) 2017  Jochen.Dehm@freenet.de

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see http://www.gnu.org/licenses/.
*/

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