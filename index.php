
<?php
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