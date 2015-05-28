<?php
include("../include/utils.php");
include("../include/connect.php");

$page_title = "Bulletin Board";

$sql = $dbh->prepare("SELECT * FROM notices WHERE date $dateop :now ORDER BY date;");
$sql->bindValue(":now", time());
$sql->execute();

include("../include/header.php");
?>
<h2>Notices: </h2>

<?php
foreach ($sql->fetchAll() as $row) {
?>
<form>
	<input type='hidden' name='id' value='$row[id]' />
<?php
    echo "<fieldset" . "><a name='e$row[id]'></a>";
?>
	<table>
        <tr>
            <td><strong><?php echo dateFromTimestamp($row['date']); ?></strong> at <strong><?php echo $row['location'] ?></strong></td>
        </tr>
        <tr>
        <td><p><?php echo array_shift((explode("\n", $row['content']))); ?></p></td>
    	</tr>       
	</table>
</fieldset></form>
<?php
}
include("../include/footer.php");
?>