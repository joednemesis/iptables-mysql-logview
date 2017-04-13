<?php
function html_header()
{
	#Generate standard html header
	print '<html><head><meta http-equiv="expires" content="0">';
	print '<link rel="stylesheet" href="'.set_css_file().'">';
	print '</head><body><div id="wrapper"><div id="header">JOEDs Firewall Log Watch</div> <!-- Ende kopfbereich -->';
}
function html_end()
{
	#Generate standard html header end
	print'</div> <!-- End wrapper --></body></html>';
}

function get_css_files() {
	#Read all available css files from filesystem in an array and return it.		
	$css_source_dir = "css"; // dir with .css files 
	return glob($css_source_dir.'/*.css', GLOB_BRACE);
	
}

function set_css_file() {
	#read actually used css from db and return it
	$result_css = mysql_result(mysql_query("select parameter_value from config where parameter='css'"),0);
	return $result_css;
}

function update_css_file($css_new_value) {
	#update css file in db
	mysql_result(mysql_query("update config set parameter_value='".$css_new_value."' where parameter='css'"),0);
}

function anz_rows_read()
/*Read value for rows from db 
 */
{
	$result_rows = mysql_result(mysql_query("select parameter_value from config where parameter='rows'"),0);
	return $result_rows;
}

function anz_rows_update($rows_new_value)
/*Update value for rows in db 
 */
{	
	mysql_result(mysql_query("update config set parameter_value=".$rows_new_value." where parameter='rows'"),0);
}

function reverse_IP($ipAddress)
/*Oddly, the addresses written by ulogd2 in the table are IP addresses backward. 
This function converts a.b.c.d into d.c.b.a.*/
{
	$arrIP = explode('.', $ipAddress);
	$arrIPReverse = array_reverse($arrIP);
	$ipAddressReverse = join('.', $arrIPReverse);
	return $ipAddressReverse;
}

function filter_prefix()
/*To always only see the logfile of a certain iptables gateway, the selection list is written to an array. 
For this reason, the name of the firewall should always be in the prefix of the iptables rules.*/
{
	$filter_prefix_sql = 'select oob_prefix from ulog group by oob_prefix;';
	$filter_prefix_result = mysql_query($filter_prefix_sql);
	
	$filter_prefix_array = array();
	$Z = 0;
	
	while ($filter_prefix_fetch = mysql_fetch_array($filter_prefix_result, MYSQL_NUM))
	{
		$filter_prefix_array[$Z]= $filter_prefix_fetch[0];
		
		$Z++;
	}
	sort($filter_prefix_array);
	return $filter_prefix_array;
}

function filter_key($fk_in)
{
/* Usese function filter_prefix() to generste array and search for correspondending key from $fk_in */
	$array_key = array_search($fk_in, filter_prefix());
	return $array_key;
}

function display_results($oob_prefix_selected)
{
	#How many rows should be shown in table? Getting value from DB, table config
	 
	$anz_rows = anz_rows_read();
		
			
	#echo "oob_prefix=".$_GET['oob_prefix']." and ";
	#echo "limit=".$_GET['limit']."<br>";
	
	if (empty($_GET['limit'])) {
	 #echo "no nav link used, used select button. oob_prefix and limit have to be empty! <br>";	  
	 $sql = "SELECT id, from_unixtime(oob_time_sec) as date_time, oob_prefix, oob_in as Interface_in, oob_out as Interface_out, inet_ntoa(ip_saddr) as ip_src_addr, IFNULL(tcp_sport,IFNULL(udp_sport,0)) AS port_src, inet_ntoa(ip_daddr) as ip_dst_addr, IFNULL(tcp_dport,IFNULL(udp_dport,0)) AS port_dst, name as protocol FROM ulog, protos where num = ip_protocol and oob_prefix = '" . $oob_prefix_selected ."' order by date_time DESC LIMIT 0, $anz_rows";
	 $sql_anz_rows = "SELECT count(*) FROM ulog where oob_prefix = '" . $oob_prefix_selected."'";
	 $sql_anz_rows_display = "SELECT COUNT(*) FROM (SELECT id FROM ulog where oob_prefix = '" . $oob_prefix_selected."' LIMIT 0, $anz_rows) as c";
	 
	 #Generate parm for selection button urls at the buttom of the table
	 $param = '?&oob_prefix='.filter_key($_POST['oob_filter']." ");
	 $activated_filter = $oob_prefix_selected;
	 $display_start=1;	 
	}
	else {
		#echo "nav link used.<br>"; 
		$array_filter=filter_prefix();
		
		#echo print_r($array_filter)."<br>";
		$help = intval($_GET['oob_prefix']);
		
		$sql_start = substr($_GET['limit'],0,-1);
		$sql = "SELECT id, from_unixtime(oob_time_sec) as date_time, oob_prefix, oob_in as Interface_in, oob_out as Interface_out, inet_ntoa(ip_saddr) as ip_saddr, tcp_sport, udp_sport, inet_ntoa(ip_daddr) as ip_daddr, tcp_dport, udp_dport,name as protocol FROM ulog, protos where num = ip_protocol and oob_prefix = '" . substr($array_filter[$help],0, -1) ."' order by date_time DESC  LIMIT $sql_start, $anz_rows";
		
		#echo "DEBUG: " . $_GET['oob_prefix'] ."-". substr($array_filter[$_GET['oob_prefix']],0, -1) . "<br>";
		#echo "DEBUG: " . $help ."-". $array_filter[$help] . "<br>";
		
		$sql_anz_rows = "SELECT count(*) FROM ulog where oob_prefix = '" . substr($array_filter[$help],0, -1)."'";
		$sql_anz_rows_display = "SELECT COUNT(*) FROM (SELECT id FROM ulog where oob_prefix = '" . substr($array_filter[$help],0, -1)."' LIMIT $sql_start, $anz_rows) as c";
		#Generate parm for selection button urls at the buttom of the table
		$param = '?&oob_prefix='.$_GET['oob_prefix']."";
		$activated_filter = substr($array_filter[$help],0, -1);
		$display_start=$sql_start+1;
	}
	
	#echo "-- Looking for total number rows over all selected -- <br>";
	#echo $sql_anz_rows."<br>";
	$result_anz_rows = mysql_query($sql_anz_rows);
	$a_anz_rows = mysql_fetch_array($result_anz_rows);
	$i_anz_rows = $a_anz_rows[0];
	#echo "Total number of rows: " . $i_anz_rows .". <br>";
	
	#echo "-- Looking for total number rows over all selected for display -- <br>";
	#echo $sql_anz_rows_display."<br>";
	$result_anz_rows_display = mysql_query($sql_anz_rows_display);
	$a_anz_rows_display = mysql_fetch_array($result_anz_rows_display);
	$i_anz_rows_display = $a_anz_rows_display[0];
	#echo "Total number of rows to display: " . $i_anz_rows_display .". <br>";
	$display_end = $display_start + $i_anz_rows_display-1;
	
	
	#echo "<br>" . $sql;	
	$result = mysql_query($sql);
	
	$output ="<div class=\"datagrid\"><table>";
	$Z = 0;	
		
	#Read all prefix for the selection list and search for the correspondending id to the value of oob_filter
	$oob_prefix_list_dr = filter_prefix();
	
	#Generate parm for selection button urls at the buttom of the table
	#hier noch was anpassen, falls link angeklickt wurde.	
	#$param = '?&oob_prefix='.filter_key($_POST['oob_filter']." ");
	
		
	#How many pages should table have?
	$max_pages = ceil($i_anz_rows / $anz_rows);
	#print "<br><br>Total number of pages: " . $max_pages."<br>";

	#print "Display: " .$display_start+1 . " to " . $display_start + $i_anz_rows_display;
	#$display_start++; 
	echo "Active Filter: " . $activated_filter;
	print '<br>Showing rows from ' . $display_start .' to '. $display_end;
	
	
	#.$_GET['limit']+1 ." to " . $_GET['limit'] + $i_anz_rows_display
	
	#used for column number in table footer	
	$i_span=0; 
	$i_lauf=1;
	
	while ($i_lauf<$i_anz_rows_display+1)
	{
		$i_lauf++;
		$fetch = mysql_fetch_array($result, MYSQL_NUM);
		
		$Z++;
	
		#Zeile für den Header
		if ($Z == 1)
		{
			$output .= "<thead><tr>";
		
			for ($i=0; $i<=count($fetch) -1;$i++)
			{
				$output .= "<th>" . mysql_field_name($result, $i) . "</th>";
			}
			$output .= "</tr></thead><tbody>";
		}
		
	#Zeilen mit den Werten
	if ($Z % 2 != 0) {
		$output .= "<tr>";
	} else {
		$output .= "<tr class=\"alt\">";
	}
		
			
		for ($i=0; $i<=count($fetch) -1;$i++)
			{
				if (mysql_field_name($result, $i)=='ip_saddr' || mysql_field_name($result, $i)=='ip_daddr')
					$output .= "<td>" . reverse_IP($fetch[$i]) . "</td>";
				else
					$output .= "<td>" . $fetch[$i] . "</td>";
			$i_span = $i; #used for column number in table footer
			}
		$output .= "</tr>";	
	}

	#Generate Footer with navigation buttons
	
	$output .= '</tbody><tfoot><tr> <td colspan="';
	$output .=  $i_span+1; #this is the column number in table footer +1
	#generate "previous"-Button, has no functionality jet
	#OLD: $output .= 	'"><div id="paging"><ul><li><a href=""><span>Previous</span></a></li><li>';
	$output .= 	'"><div id="paging"><ul>';
	#calculate values for previous and next-buttons'
	if($display_start==1) {
		#we don't need a Previous Button if here		
		$limit_next = "10\\";
	}
	else {
		#generate previous button
		$limit_previous = $display_start - $anz_rows -1 ."\\";
		$output .= '<li><a href="'.$_SERVER['PHP_SELF'].$param.'&limit='.$limit_previous.'"><span>Previous</span></a></li>';
		
		
		$limit_next = $display_start + $anz_rows -1;
		#print '<br>limit_next='.$limit_next.' i_anz_rows='.$i_anz_rows.'<br>no next button';
		if ($limit_next>$i_anz_rows) {
			$no_next_button=1;
		$limit_next .= "\\";
			
		}
	}
	
	
	
	
	#generate numbered button
	for ($i=1; $i<=$max_pages; $i++) {		
		$limit = ($i-1) * $anz_rows;
		$output .= '<li><a href="'.$_SERVER['PHP_SELF'].$param.'&limit='.$limit.'\"><span>'. $i ."</span></a></li><li>";
	}
	#generate "next"-Button, problem = didn't stop	
	if ($no_next_button<>1) {
		$output .= '<a href="'.$_SERVER['PHP_SELF'].$param.'&limit='.$limit_next.'"><span>Next</span></a></li>';	
	}
	
	#rest of table
	$output .= '</ul></div></tr></tfoot></table></div>';
	
	
	echo $output;
}