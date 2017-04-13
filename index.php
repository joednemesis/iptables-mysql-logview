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
echo '<a href="config.php">Configure</a>';
echo "</div> <!-- end menu -->";
  
#Navi section for applying filters for main section
echo "<div id=\"navi\">"; 
#Read all prefix for the selection list
$oob_prefix_list = filter_prefix();

#Form which uses the previously read out prefixes as a filter for the table
echo '<form action="index.php" method="post">';
echo "Select filter:  ";
echo '<select name="oob_filter">';
foreach ( $oob_prefix_list as $oob_prefix_value) {
	echo '<option value"'.filter_key($oob_prefix_value).'">'.$oob_prefix_value."</option>";
}
echo "</select>   ";
 echo '<input type="submit" value="select">';
echo '</form>';



#$array_search = $_POST['oob_filter']." ";
#$array_key = array_search($array_search, $oob_prefix_list);

if(isset($_POST['oob_filter'])) {

#echo "Active Filter: " . $array_key ." - ". $_POST['oob_filter'];		
}
echo "</div> <!-- end navi -->";

#Main section for showing table with the results
echo "<div id=\"main\">";
display_results($_POST['oob_filter']);
echo "</div> <!-- end main -->";

html_end()
?> 