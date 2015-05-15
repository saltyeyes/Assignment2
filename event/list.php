<?php
include("../include/connect.php");

$page_title = "Events";

$sql = $dbh->prepare("SELECT * FROM (events AS e JOIN event_artists AS ea ON e.id = ea.eventid) JOIN artists AS a ON ea.artistid = a.id ORDER BY date_time;");
$sql->execute();
include("../include/header.php");
?>
<h2>Current Events:</h2>
<?php

foreach ($sql->fetchAll() as $row) {
    ?>
        <?php
        echo "<input type='hidden' name='id' value='$row[id]' />";
        echo "<fieldset" . ($row['is_featured'] ? " class='featured'" : "") . "><a name='a$row[id]'></a>" ?>
        <table width="200">
            <tr>
                <td width="32" rowspan=2><?php echo getImageElement($row['image']); ?></td>
                <td width="110"><?php echo getLink($row['date']);?>
                <td width="42"><b><?php echo sprintf('<a href="%s">%s</a>', getLink("/artists/".$row['id']), $row['name']); ?></b></td>
            </tr>
            <tr>
                <td><p><?php echo array_shift((explode("\n", $row['info']))); ?></p></td>
            </tr>
        </table>
    </fieldset>
<?php
}
$dbh = null;
include("../include/footer.php");
?>