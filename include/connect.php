<?php
include_once("utils.php");
try {
	$dbh = new PDO(DEBUG_MODE ? 'sqlite:..\db\tcmc.sqlite' : 'sqlite:/home/tcmc01/public_html/site/db/tcmc.sqlite'); 
} catch(PDOException $e) {
	echo $e->getMessage();
}
?>