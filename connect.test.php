<?php
require_once("connect.php");

// Check connection
if (!$dbconn -> connect_error) {
  die("Connection failed: " . $dbconn -> connect_error);
}
echo "Connected successfully";
?> 