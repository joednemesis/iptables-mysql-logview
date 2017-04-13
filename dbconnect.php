<?php

//Debugging

#debug mode
$debug=1;

//Database configuration

# Host of the MySQL database
$db_host="192.168.1.119";

# User of the MySQL database
$db_user="iptables_www";

# Password of the MySQL database
$db_password="1q2w3e4r";

# Name of the database
$db_name="iptables";

#Connect to the mysql server and select the database.
mysql_connect($db_host,$db_user,$db_password);
mysql_select_db($db_name) or die ("Konnte Datenbank nicht finden!");

