<?php
include("tcmcConnect.php")
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Artist Records</title>
</head>

<body>
<h1>Artist's Database</h1>
<form id="insert" name="insert" method="post" action="dbprocessphone.php">
<fieldset class="subtleSet">
	    <h2>Insert new Artist's details:</h2>
    <p>
      <label for="name">Name: </label>
      <input type="text" name="name" id="name">
    </p>
    <p>
      <label for="info">Info: </label>
      <input type="text" name="Info" id="info">
    </p>
    <p>
      <br>
      Featured Artists:
	  <input type="checkbox" name="Featured Artists" value="">1
      <input type="checkbox" name="Featured Artists" value="">0<br><br>
    </p>
    <p>
      <input type="submit" name="submit" id="submit" value="Insert Entry">
    </p>
</fieldset>
</form>
</body>
</html>