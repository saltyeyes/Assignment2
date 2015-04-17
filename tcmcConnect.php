<?php
try {
    $dbh = new PDO("sqlite:tcmc.sqlite"); 
}
catch(PDOException $e)
{
    echo $e->getMessage();
}
?>