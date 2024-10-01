<?php
define("DBHOST", "localhost");
define("DBUSER", "root");
define("DBPASS", "");
define("DBNAME", "nevjegyzek");

$dbconn = @mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
mysqli_query($dbconn, "SET NAMES utf8");
?>