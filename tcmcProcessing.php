<?php
include("dbconnect.php");
$debugOn = true;

if ($_REQUEST['submit'] == "X")
{
	$sql = "DELETE FROM artist WHERE id = '$_REQUEST[id]'";
	if ($dbh->exec($sql))
		header("Location: tcmcDeleteArtist.php");
}
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Deleting/Updating Processing</title>
</head>

<body>
<h1>Results</h1>
<?php
echo "<h2>Artist's Data</h2>";
echo "<pre>";
print_r($_REQUEST);
echo "</pre>";

if ($_REQUEST['submit'] == "Insert Entry")
{
	$sql = "INSERT INTO artist (name, info, is_featured) VALUES ('$_REQUEST[name]', '$_REQUEST[phone]', '$_REQUEST[is_featured]')";
	echo "<p>Query: " . $sql . "</p>\n<p><strong>"; 
	if ($dbh->exec($sql))
		echo "Inserted $_REQUEST[name]";
	else
		echo "Not inserted";
}
else if ($_REQUEST['submit'] == "Delete Entry")
{
	$sql = "DELETE FROM artist WHERE id = '$_REQUEST[id]'";
	echo "<p>Query: " . $sql . "</p>\n<p><strong>"; 
	if ($dbh->exec($sql))
		echo "Deleted $_REQUEST[name]";
	else
		echo "Not deleted";
}
else if ($_REQUEST['submit'] == "Update Entry")
{
	$sql = "UPDATE artist SET name = '$_REQUEST[name]', info = '$_REQUEST[info]', is_featured = '$_REQUEST[is_featured] WHERE id = '$_REQUEST[id]'";
	echo "<p>Query: " . $sql . "</p>\n<p><strong>"; 
	if ($dbh->exec($sql))
		echo "Updated $_REQUEST[name]";
	else
		echo "Not updated";
}
else {
	echo "This page did not come from a valid submission.<br />\n";
}
echo "</strong></p>\n";

echo "<h2>Current Artist records in database</h2>\n";
$sql = "SELECT * FROM artist";
$result = $dbh->query($sql);
$resultCopy = $result;

if ($debugOn) {
	echo "<pre>";	

	$rows = $result->fetchall(PDO::FETCH_ASSOC);
	echo count($rows) . " records in table<br />\n";
	print_r($rows);
	echo "</pre>";
	echo "<br />\n";
}
foreach ($dbh->query($sql) as $row)
{
	print $row[name] .' - '. $row[info] . $row[is_featured] . "<br />\n";
}
$dbh = null;
?>
<p><a href="tcmcDeleteArtist">Return to database test page</a></p>
</body>
</html>