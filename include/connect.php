<?php
include_once("utils.php");
try {
	$dbh = new PDO(DEBUG_MODE ? 'sqlite:..\db\tcmc.sqlite' : 'sqlite:/home/tcmc01/public_html/m2/db/tcmc.sqlite'); 
} catch(PDOException $e) {
	echo $e->getMessage();
}
?>