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

//Debugging

#debug mode, no function , still ..
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

