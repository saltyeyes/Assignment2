<?php
include("tcmcConnect.php")
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Deleting/Updating Artist</title>
</head>

<body>
<fieldset class="subtleSet">
<h2>Current Artists:</h2>
<?php
$sql = "SELECT * FROM artists";
foreach ($dbh->query($sql) as $row)
{
?>
<form id="deleteArtist" name="deleteArtist" method="post" action="tcmcProcessing.php">
<?php
	echo "<input type='hidden' name='id' value='$row[id]' />";
?>
<input type="submit" name="submit" value="Update Entry" />
<input type="submit" name="submit" value="Delete Entry" class="deleteButton">
<input type="submit" name="submit" value="X" class="deleteButton">
</form>
<?php
}
echo "</fieldset>\n";
$dbh = null;
?>
</body>
</html>