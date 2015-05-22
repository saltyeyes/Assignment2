<?php
include("../include/connect.php");

$sql = $dbh->prepare("SELECT * FROM (events AS e JOIN event_artists AS ea ON e.id = ea.eventid) JOIN artists AS a ON ea.artistid = a.id ORDER BY date_time;");
$sql->execute();
$row = $sql->fetch();

if($row == null) {
    redirect("/404");
}

$name = $row['name'];
$date = $row['date'];

$page_title = "Event";

include("../include/header.php");
?>

<h2><?php echo $eventid; ?></h2>

<?php echo "<input type='hidden' name='id' value='$row[id]' />"; ?>
    <table>
    	<tr>
            <td><?php echo getLink($row['name']); ?></td>
        </tr>
        <tr>
 			<td><?php echo getLink($row['date']); ?></td>
		</tr>
    </table>
    
<?php
    include("../include/footer.php");
?>
		






