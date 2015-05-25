<?php
include("../include/utils.php");
include("../include/connect.php");

$page_title = "Events";

$isPastEvents = isset($_REQUEST["which"]) && $_REQUEST["which"] == "past";
$dateop = $isPastEvents ? "<" : ">=";

$sql = $dbh->prepare("SELECT * FROM events WHERE date $dateop :now ORDER BY date;");
$sql->bindValue(":now", time());
$sql->execute();
include("../include/header.php");

echo "<h2>" . ($isPastEvents ? "Past" : "Current") . " Events:</h2>";
?>

<?php
// print_r($sql->fetchAll());
foreach ($sql->fetchAll() as $row) {
?>
    <form>
        <input type='hidden' name='id' value='$row[id]' />
<?php
    echo "<fieldset" . ($row['is_featured'] ? " class='featured'" : "") . "><a name='e$row[id]'></a>";
?>
    <table>
        <tr>
            <td rowspan=4><?php echo getImageElement($row['image']); ?></td>
            <td><h3><?php echo sprintf('<a href="%s">%s</a>', getLink("/events/".$row['id']), $row['name']); ?></h3></td>
        </tr>
        <tr>
            <td><strong><?php echo dateFromTimestamp($row['date']); ?></strong> at <strong><?php echo $row['location'] ?></strong></td>
        </tr>
        <?php
            $artistsql = $dbh->prepare("SELECT a.id, a.name FROM artists AS a JOIN event_artists AS ea ON a.id = ea.artistid WHERE ea.eventid=:id");
            $artistsql->bindValue(":id",$row['id']);
            $artistsql->execute();
            $artists = $artistsql->fetchAll();
            if (count($artists) > 0) {
                ?>
                <tr><td><em>Featuring: <?php 
                    foreach ($artists as $id=>$artist) {
                        echo sprintf('<a href="%s">%s</a>', getLink("/artists/".$artist['id']), $artist['name']) . (count($artists) > $id+1 ? ", " : "");
                    }
                ?> </em></td></tr>
            <?php
            }
        ?>
        <tr>
            <td><p><?php echo array_shift((explode("\n", $row['info']))); ?></p></td>
        </tr>
    </table></fieldset></form>
<?php
}
include("../include/footer.php");
?>