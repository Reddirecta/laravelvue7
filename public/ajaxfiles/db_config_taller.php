<?php
$localhost = "localhost"; // YOUR LOCAL HOST, USUALLY localhost
$dbuser = "root"; // YOUR DATABASE USERNAME
$dbpass = ""; // YOUR DATABASE PASSWORD
$dbtable = "taller";// THE NAME OF YOUR DATABASE , THIS SHOULD HAVE BEEN SET WHEN YOU INSTALLED dbuserdb.sql, SO YOU CAN LEAVE THIS
$mysqli = new mysqli($localhost, $dbuser, $dbpass, $dbtable);
if ($mysqli->connect_errno) {
	echo json_encode("Lo sentimos, este sitio web está experimentando problemas.");
	echo "Error: Fallo al conectarse a MySQL debido a: \n";
	echo "Errno: " . $mysqli->connect_errno . "\n";
	echo "Error: " . $mysqli->connect_error . "\n";
	exit;
}
mysqli_set_charset($mysqli,'utf8');
setlocale(LC_TIME, 'es_ES');
setlocale(LC_MONETARY, 'en_US');
?>